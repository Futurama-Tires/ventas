<?php

namespace App\Http\Controllers;

use App\Exports\MaximosMinimos\MaxMinExport;
use Illuminate\Http\Request;
use App\Models\Factor;
use App\Services\MaxMinService\CalculosMaxMinService;
use Maatwebsite\Excel\Facades\Excel;

class MaximosMinimosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $factores = Factor::all();

        return view('maximos_minimos.index', compact('factores'));
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
    public function update(Request $request, Factor $factor)
    {
        /*$validated = $request->validate([
            'factor' => 'required',
        ]);

        try {
            $factor->update($validated);
            return redirect()->route('maxmin.index')->with('editar', 'ok');
        } catch (\Exception $e) {
            return redirect()->route('maxmin.index')->with('editar', 'error');
        }*/
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function descargarReporte()
    {
        $service = new CalculosMaxMinService();
        //dd(end($service->obtenerDatos('marca')['hoja_2']));
        /*$datos = $service->obtenerDatos('marca'); // Obtiene el array con 'hoja_1' y 'hoja_2'
        $filas = end($datos['hoja_1']);

        dd($datos['hoja_1']);*/
        $fecha = date("d-m-Y"); // Fecha actual
        $fechaReporte = $this->obtenerFecha($fecha);
        $nomReporte = "MAXMIN MARCA-RIN " . $fechaReporte . ".xlsx";
        //dd($nomReporte );
        return Excel::download(new MaxMinExport($service), $nomReporte);
    }

    private function obtenerFecha($fecha)
    {
        // Obtener el día de la semana (0 para domingo, 1 para lunes, ..., 6 para sábado)
        $dia_semana = date('w', strtotime($fecha));

        // Verificar si es lunes
        if ($dia_semana == 1) {
            // Si es lunes, tomar el número del día del sábado (2 días atrás)
            $fecha_ayer = date('d-m-Y', strtotime('-2 days', strtotime($fecha)));
        } else {
            // Si no es lunes, simplemente tomar el número del día anterior
            $fecha_ayer = date('d-m-Y', strtotime('-1 day', strtotime($fecha)));
        }

        return $fecha_ayer;
    }
}
