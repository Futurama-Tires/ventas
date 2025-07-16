<?php

namespace App\Livewire\Cotizador;

use Livewire\Component;
use App\Services\Netsuite\NetsuiteService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CotizadorLlantas extends Component
{
  public array $inventario = [];
  public ?string $filtroMarca = null;
  public ?string $filtroAncho = null;
  public ?string $filtroAltura = null;
  public ?string $filtroRin = null;
  public ?string $filtroPrecioMin = null;
  public ?string $filtroPrecioMax = null;
  public Collection $inventarioConsolidado;
  public Collection $datosConsolidados;

  /* ────────────────────  Servicio Netsuite  ──────────────────── */
  protected NetsuiteService $netsuite;

  /** 
   * Livewire inyecta dependencias en `mount()` igual que lo hace
   * Laravel en un controlador.  :contentReference[oaicite:1]{index=1}
   */
  public function boot(NetsuiteService $netsuite): void   // ← corre SIEMPRE
  {
    $this->netsuite = $netsuite;
  }

  public function mount()
  {
    $this->datosConsolidados = $this->loadInventario();
  }

  protected function loadInventario(): Collection
  {
    $allItems = $this->getAllPaginatedItems();

    return $this->consolidateInventory($allItems);
  }

  protected function getAllPaginatedItems(): array
  {
    $query = $this->getBaseQuery();
    $allItems = [];
    $offset = 0;
    $limit = 1000;
    $hasMore = true;
    $count = 0;
    while ($hasMore) { // Limitar a 10 páginas para evitar saturación
      $response = $this->netsuite->suiteqlQuery($query, $limit, $offset);

      $allItems = array_merge($allItems, $response['items'] ?? []);

      // Verificar si hay más páginas
      $hasMore = $response['hasMore'] ?? false;
      $offset += $limit;

      // Pequeño delay para no saturar la API
      usleep(200000); // 200ms
      $count++;
      //Log::info([$offset, $limit, $response['offset'], $response['totalResults'], $count]);
    }

    return $allItems;
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
    return
      collect($items)
      ->groupBy('itemid')
      ->map(function ($group) {
        $first = $group->first();
        return [
          'itemid' => $first['itemid'] ?? "NO SE ENCONTRÓ",
          'descripcion' => $first['descripcion'] ?? "NO SE ENCONTRÓ",
          'aplicacion' => $first['aplicacion'] ?? "",
          'oe' => $first['oe'] ?? "",
          'medida_equivalente' => $first['medida_equivalente'] ?? "",
          'marca' => $first['marca'] ?? "",
          'promocion' => $first['promocion'] ?? "",

          'ubicaciones' => $group->unique('ubicacion')->map(function ($item) {
            return [
              'ubicacion' => $item['ubicacion'] ?? "NO SE ENCONTRÓ",
              'disponible' => $item['disponible'] ?? 0,
              'stock_fisico' => $item['stock_fisico'] ?? 0,
            ];
          })->values(),

          'precios' => $group->whereNotNull('pricelevelname')->unique('pricelevelname')->map(function ($item) {
            return [
              'nivel' => $item['pricelevelname'] ?? "",
              'precio' => $item['price'] ?? "0",
            ];
          })->values(),

        ];
      })->values();
  }

  public function render()
  {
    return view('livewire.cotizador.cotizador-llantas');
  }
}
