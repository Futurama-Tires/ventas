<?php

namespace App\Jobs\Cotizador;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Cotizador\InventarioExport;
use App\Models\User;
use App\Notifications\Cotizador\ExportacionCompleta;
use App\Services\Netsuite\NetsuiteService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Exports\Cotizador\CotizadorExport;

class ExportarInventarioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User $user,

    ) {}

    /**
     * Execute the job.
     */
    public function handle(NetsuiteService $netsuite)
    {
        // Obtener los datos de NetSuite
        $rowsRaw = $this->obtenerDatosParaExportar($netsuite);

        // Consolidar
        $rows = $this->consolidateInventory($rowsRaw);

        // Aplanar para DataTables
        $data = $rows->map(fn($i) => $this->flattenForDatatables($i))->toArray();

        $nombreArchivo = 'inventario_' . now()->format('Ymd_His') . '.xlsx';
        $ruta = 'exports/' . $nombreArchivo;

        $nombreArchivo = 'inventario_' . now()->format('Ymd_His') . '.xlsx';
        $ruta = 'exports/' . $nombreArchivo;

        // Guarda el Excel en storage/app/exports
        Excel::store(new CotizadorExport($data), $ruta);
        Log::info("Exportación de inventario completada: {$ruta}");
        //$this->user->notify(new ExportacionCompleta($ruta, $nombreArchivo));
    }

    protected function obtenerDatosParaExportar(NetsuiteService $netsuite)
    {
        //Consulta de NetSuite
        $query = "SELECT 
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

        $detailResp  = $netsuite->suiteqlQueryAll($query);

        return $detailResp;
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
}
