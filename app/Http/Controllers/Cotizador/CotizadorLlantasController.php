<?php

namespace App\Http\Controllers\Cotizador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Netsuite\NetsuiteService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Jobs\Cotizador\ExportarInventarioJob;
use App\Exports\Cotizador\InventarioExport;
use Maatwebsite\Excel\Excel;

class CotizadorLlantasController extends Controller
{

  protected NetsuiteService $netsuite;

  public function __construct(NetsuiteService $netsuite)
  {
    $this->netsuite = $netsuite;
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('cotizador.index');
  }

  public function obtenerInventario(Request $request)
  {
    /*Log::info('Obteniendo inventario con filtros', [
      'request' => $request->all(),
      'ancho' => $request->input('ancho', 0),
      'alto' => $request->input('alto', 0),
      'rin' => $request->input('rin'),
      'marca' => $request->input('marca'),
      'aplicaciones' => $request->input('aplicacion'),
      'niveles_precio' => $request->input('niveles_precio', [])
    ]);*/

    // 1) Parámetros de DataTables
    $ancho = (int) $request->input('ancho', 0);
    $alto = (int) $request->input('alto', 0);
    $rin = $request->input('rin');
    $rinValue = (is_null($rin) || $rin === '') ? 0 : (float) $rin;
    $marca = $request->input('marca');
    $aplicaciones = $request->input('aplicacion');
    $nivelesPrecio = $request->input('niveles_precio', []); // Cambiado a array

    $search  = $request->input('search.value', '');
    $start   = (int) $request->input('start', 0);
    $length  = (int) $request->input('length', 50);
    $order   = $request->input('order.0', ['column' => 0, 'dir' => 'asc']);
    $dir     = strtoupper($order['dir']) === 'DESC' ? 'DESC' : 'ASC';

    $condicionNivelesPrecio = '';
    if (!empty($nivelesPrecio)) {
      // Escapar valores para SQL
      $nivelesEscapados = array_map(function ($nivel) {
        return "'" . addslashes($nivel) . "'";
      }, $nivelesPrecio);

      $listaNiveles = implode(',', $nivelesEscapados);
      $condicionNivelesPrecio = " AND itemPrice.pricelevelname IN ($listaNiveles)";
    }

    // Construir condición para aplicaciones
    $condicionAplicacion = '';
    if (!empty($aplicaciones)) {
      $aplicaciones = explode(',', $aplicaciones);

      if (is_array($aplicaciones)) {
        // Si es array (múltiples selecciones)
        $aplicacionesEscapadas = array_map(function ($app) {
          return "'%" . addslashes($app) . "%'";
        }, $aplicaciones);

        $condicionAplicacion = " AND (";
        $condicionAplicacion .= implode(" OR ", array_map(function ($app) {
          return "item.custitem_nso_uso LIKE {$app}";
        }, $aplicacionesEscapadas));
        $condicionAplicacion .= ")";
      } else {
        // Si es string (una sola selección - compatibilidad hacia atrás)
        $condicionAplicacion = " AND item.custitem_nso_uso LIKE '%" . addslashes($aplicaciones) . "%'";
      }
    }

    // 2) Contar cuántos itemid distintos hay (para recordsTotal)
    $countSql = "
    SELECT COUNT(DISTINCT item.itemid) AS total
    FROM item
    INNER JOIN (
      SELECT itemPrice.item, itemPrice.pricelevelname
      FROM itemPrice
      INNER JOIN currency 
        ON itemPrice.currencypage = currency.id
      WHERE currency.name = 'MEX'
        AND itemPrice.pricelevelname IN (
          'SEMI - MAYOREO','MAYOREO','PROMOCION DEL MES',
          'NK','PROMOCION POR PRONTO PAGO'
        )
          {$condicionNivelesPrecio}
    ) itemPrice_SUB ON item.id = itemPrice_SUB.item
    INNER JOIN (
      SELECT aggregateItemLocation.item
      FROM aggregateItemLocation
      INNER JOIN LOCATION 
        ON aggregateItemLocation.LOCATION = LOCATION.ID
      WHERE aggregateItemLocation.quantityavailable > 0
    ) aggregateItemLocation_SUB ON item.ID = aggregateItemLocation_SUB.item
    LEFT JOIN CUSTOMLIST_NSO_LIST_MARCA 
      ON item.custitem_nso_marca = CUSTOMLIST_NSO_LIST_MARCA.ID
    LEFT JOIN customlist_nso_list_diametro_rin 
      ON item.custitem_diametro_rin = customlist_nso_list_diametro_rin.id
    WHERE item.CLASS = '1'
    " . ($search !== ''
      ? " AND item.itemid LIKE '%{$search}%'"
      : '') . ($ancho !== 0
      ? " AND item.custitem_nso_ancho = '{$ancho}'"
      : '') . ($alto !== 0
      ? " AND item.custitem_nso_altura = '{$alto}'"
      : '') . ($rinValue !== 0
      ? " AND customlist_nso_list_diametro_rin.name = '{$rinValue}'"
      : '') . ($marca !== ''
      ? " AND CUSTOMLIST_NSO_LIST_MARCA.name LIKE '%{$marca}%'"
      : '') . $condicionAplicacion;

    //Log::info('SQL de conteo de itemid distintos:', ['sql' => $countSql]);

    $countResp    = $this->netsuite->suiteqlQuery($countSql);
    $totalDistinct = intval($countResp['items'][0]['total'] ?? 0);

    // 3) Obtener los itemid de la página actual
    $distinctSql = "
    SELECT DISTINCT item.itemid AS itemid
    FROM item
    INNER JOIN (
      SELECT itemPrice.item, itemPrice.pricelevelname
      FROM itemPrice
      INNER JOIN currency 
        ON itemPrice.currencypage = currency.id
      WHERE currency.name = 'MEX'
        AND itemPrice.pricelevelname IN (
          'SEMI - MAYOREO','MAYOREO','PROMOCION DEL MES',
          'NK','PROMOCION POR PRONTO PAGO'
        )
        {$condicionNivelesPrecio}
    ) itemPrice_SUB ON item.id = itemPrice_SUB.item
    INNER JOIN (
      SELECT aggregateItemLocation.item
      FROM aggregateItemLocation
      INNER JOIN LOCATION 
        ON aggregateItemLocation.LOCATION = LOCATION.ID
      WHERE aggregateItemLocation.quantityavailable > 0
    ) aggregateItemLocation_SUB ON item.ID = aggregateItemLocation_SUB.item
    LEFT JOIN CUSTOMLIST_NSO_LIST_MARCA 
      ON item.custitem_nso_marca = CUSTOMLIST_NSO_LIST_MARCA.ID
    LEFT JOIN customlist_nso_list_diametro_rin 
      ON item.custitem_diametro_rin = customlist_nso_list_diametro_rin.id
    WHERE item.CLASS = '1'
    " . ($search !== ''
      ? " AND item.itemid LIKE '%{$search}%'"
      : '') . ($ancho !== 0
      ? " AND item.custitem_nso_ancho = '{$ancho}'"
      : '') . ($alto !== 0
      ? " AND item.custitem_nso_altura = '{$alto}'"
      : '') . ($rinValue !== 0
      ? " AND customlist_nso_list_diametro_rin.name = '{$rinValue}'"
      : '') . ($marca !== ''
      ? " AND CUSTOMLIST_NSO_LIST_MARCA.name LIKE '%{$marca}%'"
      : '') . $condicionAplicacion . "
    ORDER BY item.itemid {$dir}";

    //log::info('SQL de itemid distintos:', ['sql' => $distinctSql]);

    $distinctResp = $this->netsuite->suiteqlQuery($distinctSql, $length, $start);
    $itemidsPage  = array_column($distinctResp['items'] ?? [], 'itemid');

    // 4) Si no hay itemid, devolvemos vacío
    if (empty($itemidsPage)) {
      $rows = collect();
    } else {
      // 4a) Detalle de esos itemid sin paginar
      $inList      = "'" . implode("','", $itemidsPage) . "'";
      $detailSql   = $this->getBaseQuery() . " AND item.itemid IN ({$inList})";
      $detailResp  = $this->netsuite->suiteqlQuery($detailSql);
      $rowsRaw     = $detailResp['items'] ?? [];
      // 4b) Consolidar
      $rows = $this->consolidateInventory($rowsRaw);
    }

    // 5) Aplanar para DataTables
    $data = $rows
      ->map(fn($i) => $this->flattenForDatatables($i))
      ->toArray();

    // 6) Respuesta JSON
    return response()->json([
      'draw'            => (int) $request->input('draw'),
      'recordsTotal'    => $totalDistinct,
      'recordsFiltered' => $totalDistinct,
      'data'            => $data,
    ]);
  }


  protected function getBaseQuery(): string
  {
    return "SELECT 
      item.itemid AS itemid,
      itemPrice_SUB.pricelevelname AS pricelevelname,
      itemPrice_SUB.price AS price,
      aggregateItemLocation_SUB.fullname AS ubicacion,
      aggregateItemLocation_SUB.quantityavailable AS disponible,
      aggregateItemLocation_SUB.quantityonhand AS stock_fisico,
      item.description AS descripcion,
      item.custitem20 AS iva,
      item.custitem15 AS medida_equivalente,
      item.custitem19 AS promocion,
      item.custitem4 AS oe,
      item.custitem_nso_uso AS aplicacion,
      item.custitem_nso_altura AS altura,
      item.custitem_nso_ancho AS ancho,
      CUSTOMLIST_NSO_LIST_MARCA.name AS marca
    FROM item
    INNER JOIN (
      SELECT 
        itemPrice.item,
        itemPrice.price,
        itemPrice.pricelevelname
      FROM itemPrice
      INNER JOIN currency ON itemPrice.currencypage = currency.id
      WHERE 
        currency.name = 'MEX'
        AND itemPrice.pricelevelname IN (
          'SEMI - MAYOREO', 
          'MAYOREO', 
          'PROMOCION DEL MES', 
          'NK', 
          'PROMOCION POR PRONTO PAGO'
        )
    ) itemPrice_SUB ON item.id = itemPrice_SUB.item
    INNER JOIN (
      SELECT 
        aggregateItemLocation.item,
        LOCATION.fullname,
        aggregateItemLocation.quantityavailable,
        aggregateItemLocation.quantityonhand
      FROM aggregateItemLocation
      INNER JOIN LOCATION 
        ON aggregateItemLocation.LOCATION = LOCATION.ID
      WHERE 
        aggregateItemLocation.LOCATION IN (
          '65','68','64','67','54','62','61','53','55','63','59','75','74','73','5','8','7','6','11','1','12','3','56','57','14','13','76','9','4','2','52'
        )
        AND aggregateItemLocation.quantityavailable > 0
    ) aggregateItemLocation_SUB ON item.ID = aggregateItemLocation_SUB.item
    LEFT JOIN CUSTOMLIST_NSO_LIST_MARCA 
      ON item.custitem_nso_marca = CUSTOMLIST_NSO_LIST_MARCA.ID
    WHERE 
      item.CLASS = '1'";
  }

  protected function consolidateInventory(array $items): Collection
  {

    $gruposUbicaciones = [
      'CHAMILPA' => [
        'BOD TEMPO 1',
        'BOD TEMPO 25',
        'BOD TEMPO 26',
        'BOD TEMPO 5',
        'CHAMILPA LOCAL 14',
        'CHAMILPA LOCAL 17',
        'CHAMILPA LOCAL 5',
      ],
      'AHUATEPEC' => [
        'AHUATEPEC 10',
        'AHUATEPEC',
      ],
      'OBREGON MAYOREO' => [
        'OBREGON MAY',
        'OBREGON A',
        'OBREGON B',
      ],

    ];

    return collect($items)
      ->groupBy('itemid')
      ->map(function ($group) use ($gruposUbicaciones) {
        $ubicacionesReales = $group
          ->unique('ubicacion')
          ->map(fn($i) => [$i['ubicacion'] => $i['disponible'] ?? 0])
          ->collapse();

        // Procesamos los grupos de ubicaciones
        $ubicacionesConsolidadas = [];
        foreach ($gruposUbicaciones as $nombreGrupo => $ubicacionesGrupo) {
          $stockTotal = 0;

          foreach ($ubicacionesGrupo as $ubicacion) {
            if (isset($ubicacionesReales[$ubicacion])) {
              $stockTotal += $ubicacionesReales[$ubicacion];
            }
          }

          // Aplicamos el límite de 100
          $ubicacionesConsolidadas[$nombreGrupo] = ($stockTotal > 100) ? 100 : $stockTotal;
        }

        // Agregamos las ubicaciones no consolidadas
        foreach ($ubicacionesReales as $ubicacion => $stock) {
          $perteneceAGrupo = false;

          foreach ($gruposUbicaciones as $ubicacionesGrupo) {
            if (in_array($ubicacion, $ubicacionesGrupo)) {
              $perteneceAGrupo = true;
              break;
            }
          }

          if (!$perteneceAGrupo) {
            $ubicacionesConsolidadas[$ubicacion] = ($stock > 100) ? 100 : $stock;
          }
        }

        return [
          'itemid'              => $group[0]['itemid'] ?? "",
          'descripcion'         => $group[0]['descripcion'] ?? "",
          'aplicacion'          => $group[0]['aplicacion'] ?? "",
          'oe'                  => $group[0]['oe'] ?? "",
          'medida_equivalente'  => $group[0]['medida_equivalente'] ?? "",
          'marca'               => $group[0]['marca'] ?? "",
          'promocion'           => $group[0]['promocion'] ?? "",
          'precios'             => $group
            ->whereNotNull('pricelevelname')
            ->unique('pricelevelname')
            ->map(fn($i) => [$i['pricelevelname'] => $i['price']])
            ->collapse(),
          'ubicaciones'         => collect($ubicacionesConsolidadas),
          'ubicaciones_reales'  => $ubicacionesReales,
        ];
      })->values();



    /*return collect($items)
      ->groupBy('itemid')
      ->map(fn($group) => [
        'itemid'              => $group[0]['itemid'] ?? "",
        'descripcion'         => $group[0]['descripcion'] ?? "",
        'aplicacion'          => $group[0]['aplicacion'] ?? "",
        'oe'                  => $group[0]['oe'] ?? "",
        'medida_equivalente'  => $group[0]['medida_equivalente'] ?? "",
        'marca'               => $group[0]['marca'] ?? "",
        'promocion'           => $group[0]['promocion'] ?? "",
        'precios'             => $group
          ->whereNotNull('pricelevelname')
          ->unique('pricelevelname')
          ->map(fn($i) => [$i['pricelevelname'] => $i['price']])
          ->collapse(),
        'ubicaciones'         => $group
          ->unique('ubicacion')
          ->map(function ($i) {
            $stock = $i['disponible'] ?? 0;
            return [
              $i['ubicacion'] => ($stock > 100) ? 100 : $stock
            ];
          })
          ->collapse(),
        'ubicaciones_reales' => $group // Mantenemos los valores reales por si se necesitan
          ->unique('ubicacion')
          ->map(fn($i) => [$i['ubicacion'] => $i['disponible'] ?? 0])
          ->collapse(),
      ])->values();*/
  }

  /**
   * Aplana cada registro para que DataTables lo consuma
   */
  protected function flattenForDatatables(array $item): array
  {
    // Niveles de precio fijos
    $niveles = [
      'SEMI - MAYOREO',
      'MAYOREO',
      'PROMOCION DEL MES',
      'NK',
      'PROMOCION POR PRONTO PAGO',
    ];

    // Ubicaciones fijas
    $ubis = [
      'MATRIZ',
      'VICENTE GUERRERO',
      'VALLEJO',
      'PONIENTE',
      'IXTAPALUCA',
      'QUERETARO',
      'GUADALAJARA',
      'OBREGON',
      'OBREGON MAYOREO',
      'JOJUTLA',
      'PLASTICOS',
      'PLASTICOS',
      'JORGES',
      'MISAEL',
      'CHAMILPA',
      'AHUATEPEC',
      'VINILOS',
      'BODEGA DE CAMION',
    ];

    $row = [
      'itemid'             => $item['itemid'],
      'descripcion'        => $item['descripcion'],
      'aplicacion'         => $item['aplicacion'],
      'oe'                 => $item['oe'],
      'medida_equivalente' => $item['medida_equivalente'],
      'marca'              => $item['marca'],
      'promocion'          => $item['promocion'],
    ];

    // Agrega precios en columnas fijas
    foreach ($niveles as $nivel) {
      $row[Str::slug($nivel, '_')]
        = number_format($item['precios'][$nivel] ?? 0, 2);
    }

    // Agrega stock por ubicación en columnas fijas
    foreach ($ubis as $ubi) {
      $key = Str::slug($ubi, '_');
      $stock = (int) ($item['ubicaciones'][$ubi] ?? 0);
      $row[$key] = ($stock > 100) ? 100 : $stock;
    }

    return $row;
  }

  public function exportarInventario(Request $request)
  {
    // Crear job para procesar en background
    ExportarInventarioJob::dispatch(auth()->user());

    return response()->json([
      'status' => 'queued',
      'message' => 'La exportación se está procesando en segundo plano. Se notificará cuando esté lista.'
    ]);
  }


  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
