<?php

namespace App\Http\Controllers\ODV;

use App\Http\Controllers\Controller;
use App\Services\Netsuite\NetsuiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesOrdersController extends Controller
{
    protected NetsuiteService $netsuite;

    public function __construct(NetsuiteService $netsuite)
    {
        $this->netsuite = $netsuite;
    }

    public function index()
    {
        return view('salesOrders.index');
    }

    public function create()
    {
        // $countSql = "SELECT id, entityid, email, isperson FROM customer WHERE entityid LIKE '%JUAN%'";

        // $countResp = $this->netsuite->suiteqlQuery($countSql);
        // dd($countResp);

        return view('salesOrders.create');
    }
    public function store(Request $request)
    {
        dd($request->all());
    }

    public function searchCustomers(Request $request)
    {
        $term = strtoupper(trim($request->input('q'))); // TomSelect uses 'q' by default

        if (!$term || strlen($term) < 3) {
            return response()->json([]);
        }

        $sql = "SELECT 
                DISTINCT BUILTIN.DF(Customer.altname) AS entityid, 
                Customer.custentity_rfc AS custentity_rfc, 
                Customer.ID AS id 
                FROM Customer LEFT JOIN employee ON Customer.salesrep = employee.ID 
                WHERE employee.subsidiary IN ('3') 
                AND Customer.altname IS NOT NULL 
                AND Customer.custentitycodigo_cliente IS NOT NULL 
                AND Customer.custentity_rfc IS NOT NULL 
                AND Customer.altname LIKE '%" . addslashes($term) . "%' ORDER BY altname ASC";

        $results = $this->netsuite->suiteqlQuery($sql);

        $customers = collect($results['items'] ?? [])
            ->take(30) // Limita aquí
            ->map(function ($item) {
                return [
                    'value' => $item['id'],
                    'text' => $item['entityid'], //Nombre del cliente
                    'rfc' => $item['custentity_rfc'],
                ];
            });

        Log::info('Clientes encontrados:', $customers->toArray());

        return response()->json($customers);
    }

    public function searchItems(Request $request)
    {
        $term = strtoupper($request->input('q')); // TomSelect uses 'q' by default
        $location = $request->input('location');
        if (!$term || strlen($term) < 3) {
            return response()->json([]);
        }

        //Obtiene todas las llantas
        $sql = "SELECT id, itemid FROM item
            WHERE itemid LIKE '%" . addslashes($term) . "%' 
            AND class = 1
            ORDER BY itemid";

        $results = $this->netsuite->suiteqlQuery($sql);

        $itemidsPage = array_column($results['items'] ?? [], 'itemid');
        $inList = "'" . implode("','", $itemidsPage) . "'";


        $ubicacionesFiltro = ['65', '68', '64', '67', '54', '62', '61', '53', '55', '63', '59', '75', '74', '73', '5', '8', '7', '6', '11', '1', '12', '3', '56', '57', '14', '13', '76', '9', '4', '2', '52'];
        $ubicacionesStr = "'" . implode("','", $ubicacionesFiltro) . "'";

        if (empty($itemidsPage)) {
            $rows = collect();
        } else {
            //Obtener stocks y datos de las llantas solo que se buscan
            /*$stockLocation = "SELECT 
            item.itemid AS itemid, 
            aggregateItemLocation_SUB.quantityavailable AS disponible FROM item
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
                '{$location}'
                )
                AND aggregateItemLocation.quantityavailable > 0
            ) aggregateItemLocation_SUB ON item.ID = aggregateItemLocation_SUB.item AND item.itemid IN ({$inList})";

            $stocks = $this->netsuite->suiteqlQuery($stockLocation);*/

            $ubicacionesFiltro = ['65', '68', '64', '67', '54', '62', '61', '53', '55', '63', '59', '75', '74', '73', '5', '8', '7', '6', '11', '1', '12', '3', '56', '57', '14', '13', '76', '9', '4', '2', '52'];
            $ubicacionesStr = "'" . implode("','", $ubicacionesFiltro) . "'";

            $stockQuery = "SELECT 
                                item.itemid AS itemid,
                                COALESCE((
                                    SELECT SUM(ail.quantityavailable)
                                    FROM aggregateItemLocation ail
                                    WHERE ail.item = item.id
                                    AND ail.LOCATION = '{$location}'
                                    AND ail.quantityavailable > 0
                                ), 0) AS stock_seleccionada,
                                COALESCE((
                                    SELECT SUM(ail.quantityavailable)
                                    FROM aggregateItemLocation ail
                                    WHERE ail.item = item.id
                                    AND ail.LOCATION IN ({$ubicacionesStr})
                                    AND ail.quantityavailable > 0
                                ), 0) AS stock_general
                            FROM item
                            WHERE item.itemid IN ({$inList})";
            $stocks = $this->netsuite->suiteqlQuery($stockQuery);
            Log::info($stocks);
        }


        $items = collect($results['items'] ?? [])
            ->take(20) // Limita aquí
            ->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['itemid'],
                ];
            });

        Log::info('Ubicaciones encontradas:', $items->toArray());

        return response()->json($items);
    }




    public function searchLocations(Request $request)
    {
        $term = strtoupper($request->input('q')); // TomSelect uses 'q' by default

        if (!$term || strlen($term) < 2) {
            return response()->json([]);
        }

        $sql = "SELECT id, name FROM location
            WHERE name LIKE '%" . addslashes($term) . "%' 
            AND id IN('1', '2', '3', '4', '6', '7', '8', '9', '76')
            ORDER BY name";

        $results = $this->netsuite->suiteqlQuery($sql);

        $locations = collect($results['items'] ?? [])
            ->take(20) // Limita aquí
            ->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                ];
            });

        Log::info('Ubicaciones encontradas:', $locations->toArray());

        return response()->json($locations);
    }

    // public function searchFormasDePago(Request $request)
    // {
    //     $term = $request->input('q'); // TomSelect uses 'q' by default

    //     if (!$term || strlen($term) < 2) {
    //         return response()->json([]);
    //     }

    //     // $sql = "SELECT id, name FROM location
    //     //     WHERE name LIKE '%" . addslashes($term) . "%' 
    //     //     ORDER BY name";


    //     $sql = "
    //         SELECT DISTINCT custbody_cfdi_formadepago, BUILTIN.DF(custbody_cfdi_formadepago) AS formadepago_name
    //         FROM transaction
    //         WHERE type = 'SalesOrd'
    //         AND BUILTIN.DF(custbody_cfdi_formadepago) LIKE '%" . addslashes($term) . "%'
    //         ";

    //     $results = $this->netsuite->suiteqlQuery($sql);

    //     $locations = collect($results['items'] ?? [])
    //         ->take(20) // Limita aquí
    //         ->map(function ($item) {
    //             return [
    //                 'id' => $item['custbody_cfdi_formadepago'],
    //                 'name' => $item['formadepago_name'],
    //             ];
    //         });

    //     Log::info('Ubicaciones encontradas:', $locations->toArray());

    //     return response()->json($locations);
    // }


    private function queryClientes()
    {
    }
}
