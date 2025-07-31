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

    public function insertSalesOrder()
    {
        // $countSql = "SELECT id, entityid, email, isperson FROM customer WHERE entityid LIKE '%JUAN%'";

        // $countResp = $this->netsuite->suiteqlQuery($countSql);
        // dd($countResp);

        return view('salesOrders.create');
    }

    public function searchCustomers(Request $request)
    {
        $term = $request->input('q'); // TomSelect uses 'q' by default

        if (!$term || strlen($term) < 2) {
            return response()->json([]);
        }

        $sql = "SELECT id, entityid, custentity_rfc, isperson 
            FROM customer 
            WHERE entityid LIKE '%" . addslashes($term) . "%' 
            ORDER BY entityid";

        $results = $this->netsuite->suiteqlQuery($sql);

        $customers = collect($results['items'] ?? [])
            ->take(20) // Limita aquí
            ->map(function ($item) {
                return [
                    'value' => $item['id'],
                    'text' => $item['entityid'],
                    'rfc' => $item['custentity_rfc'],
                ];
            });

        Log::info('Clientes encontrados:', $customers->toArray());

        return response()->json($customers);
    }

    public function searchItems(Request $request)
    {
        $term = $request->input('q'); // TomSelect uses 'q' by default

        if (!$term || strlen($term) < 2) {
            return response()->json([]);
        }

        $sql = "SELECT id, itemid FROM item
            WHERE itemid LIKE '%" . addslashes($term) . "%' 
            ORDER BY itemid";

        $results = $this->netsuite->suiteqlQuery($sql);

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
        $term = $request->input('q'); // TomSelect uses 'q' by default

        if (!$term || strlen($term) < 2) {
            return response()->json([]);
        }

        $sql = "SELECT id, name FROM location
            WHERE name LIKE '%" . addslashes($term) . "%' 
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


    private function queryClientes()
    {

    }
}
