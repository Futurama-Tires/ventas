<?php

namespace App\Services\MaxMinService;

use App\Models\Factor;
use App\Models\MaxMinExistencias;
use App\Models\MaxMinMarca;
use App\Models\MaxMinRin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculosMaxMinService
{
    /**
     * Obtiene los datos y calcula resultados por marca y por rin.
     */
    
    //Consulta original
    public function obtenerDatos2($tipo)
    {
        $tablaJoin = $tipo === 'marca' ? 'maxminmarca' : 'maxminrin';

        $datos = DB::table('maxminexistencias as e')
            ->leftJoin("$tablaJoin as m", function ($join) {
                $join->on('e.marca', '=', 'm.marca')
                    ->on('e.articulo', '=', 'm.articulo');
            })->select(
                'e.marca',
                'e.rin',
                'e.articulo',
                'e.descripcion',
                DB::raw('COALESCE(m.m1, 0) as m1'),
                DB::raw('COALESCE(m.m2, 0) as m2'),
                DB::raw('COALESCE(m.m3, 0) as m3'),
                DB::raw('COALESCE(m.m4, 0) as m4'),
                DB::raw('COALESCE(m.total, 0) as total'),
                'e.stock'
            )->orderBy($tipo === 'marca' ? 'e.marca' : 'e.rin')
            ->get();

        if ($tipo === 'marca') {
            return [
                'hoja_1' => $this->agruparPorMarca($datos),
                'hoja_2' => $this->obtenerSubtotalesPorMarca($datos)
            ];
        } else {
            return [
                'hoja_3' => $this->agruparPorRin($datos),
                'hoja_4' => $this->obtenerSubtotalesPorRin($datos)
            ];
        }
    }

    /*Obtiene todas las consultas
    public function obtenerDatos($tipo)
    {
        $tablaJoin = $tipo === 'marca' ? 'maxminmarca' : 'maxminrin';

        // Primera consulta: Obtiene datos de 'maxminexistencias' con datos de la otra tabla si existen
        $query1 = DB::table('maxminexistencias as e')
            ->leftJoin("$tablaJoin as m", function ($join) {
                $join->on('e.marca', '=', 'm.marca')
                    ->on('e.articulo', '=', 'm.articulo');
            })->select(
                'e.marca',
                'e.rin',
                'e.articulo',
                'e.descripcion',
                DB::raw('COALESCE(m.m1, 0) as m1'),
                DB::raw('COALESCE(m.m2, 0) as m2'),
                DB::raw('COALESCE(m.m3, 0) as m3'),
                DB::raw('COALESCE(m.m4, 0) as m4'),
                DB::raw('COALESCE(m.total, 0) as total'),
                'e.stock'
            );

        // Segunda consulta: Obtiene datos de $tablaJoin sin coincidencia en maxminexistencias
        $query2 = DB::table("$tablaJoin as m")
            ->leftJoin('maxminexistencias as e', function ($join) {
                $join->on('m.marca', '=', 'e.marca')
                    ->on('m.articulo', '=', 'e.articulo');
            })->whereNull('e.articulo') // Solo los que no están en 'maxminexistencias'
            ->select(
                'm.marca',
                DB::raw('NULL as rin'), // No tiene rin, porque está en la otra tabla
                'm.articulo',
                DB::raw('NULL as descripcion'),
                DB::raw('COALESCE(m.m1, 0) as m1'),
                DB::raw('COALESCE(m.m2, 0) as m2'),
                DB::raw('COALESCE(m.m3, 0) as m3'),
                DB::raw('COALESCE(m.m4, 0) as m4'),
                DB::raw('COALESCE(m.total, 0) as total'),
                DB::raw('0 as stock') // No tiene stock porque está en la otra tabla
            );

        // Unimos ambas consultas
        $datos = $query1->union($query2)->orderBy($tipo === 'marca' ? 'marca' : 'rin')->get();

        // Formato de salida
        if ($tipo === 'marca') {
            return [
                'hoja_1' => $this->agruparPorMarca($datos),
                'hoja_2' => $this->obtenerSubtotalesPorMarca($datos)
            ];
        } else {
            return [
                'hoja_3' => $this->agruparPorRin($datos),
                'hoja_4' => $this->obtenerSubtotalesPorRin($datos)
            ];
        }
    }*/

    public function obtenerDatos($tipo)
    {
        // Consultas para cada tabla
        $query1 = DB::table('maxminexistencias as e')
            ->leftJoin('maxminmarca as m', function ($join) {
                $join->on('e.marca', '=', 'm.marca')
                    ->on('e.articulo', '=', 'm.articulo');
            })
            ->leftJoin('maxminrin as r', function ($join) {
                $join->on('e.marca', '=', 'r.marca')
                    ->on('e.articulo', '=', 'r.articulo');
            })
            ->select(
                'e.marca',
                'e.rin',
                'e.articulo',
                'e.descripcion',
                DB::raw('COALESCE(e.stock, 0) as stock'),
                DB::raw('COALESCE(m.m1, r.m1, 0) as m1'),
                DB::raw('COALESCE(m.m2, r.m2, 0) as m2'),
                DB::raw('COALESCE(m.m3, r.m3, 0) as m3'),
                DB::raw('COALESCE(m.m4, r.m4, 0) as m4'),
                DB::raw('COALESCE(m.total, r.total, 0) as total')
            );

        $query2 = DB::table('maxminmarca as m')
            ->leftJoin('maxminexistencias as e', function ($join) {
                $join->on('m.marca', '=', 'e.marca')
                    ->on('m.articulo', '=', 'e.articulo');
            })
            ->leftJoin('maxminrin as r', function ($join) {
                $join->on('m.marca', '=', 'r.marca')
                    ->on('m.articulo', '=', 'r.articulo');
            })
            ->whereNull('e.articulo') // Excluye los que ya están en maxminexistencias
            ->select(
                'm.marca',
                'm.rin',
                'm.articulo',
                'm.descripcion',
                'm.stock',
                DB::raw('COALESCE(m.m1, r.m1, 0) as m1'),
                DB::raw('COALESCE(m.m2, r.m2, 0) as m2'),
                DB::raw('COALESCE(m.m3, r.m3, 0) as m3'),
                DB::raw('COALESCE(m.m4, r.m4, 0) as m4'),
                DB::raw('COALESCE(m.total, r.total, 0) as total')
            );

        $query3 = DB::table('maxminrin as r')
            ->leftJoin('maxminexistencias as e', function ($join) {
                $join->on('r.marca', '=', 'e.marca')
                    ->on('r.articulo', '=', 'e.articulo');
            })
            ->leftJoin('maxminmarca as m', function ($join) {
                $join->on('r.marca', '=', 'm.marca')
                    ->on('r.articulo', '=', 'm.articulo');
            })
            ->whereNull('e.articulo') // Excluye los que ya están en maxminexistencias
            ->whereNull('m.articulo') // Excluye los que ya están en maxminmarca
            ->select(
                'r.marca',
                'r.rin',
                'r.articulo',
                DB::raw('r.descripcion'),
                DB::raw('r.stock'),
                DB::raw('COALESCE(m.m1, r.m1, 0) as m1'),
                DB::raw('COALESCE(m.m2, r.m2, 0) as m2'),
                DB::raw('COALESCE(m.m3, r.m3, 0) as m3'),
                DB::raw('COALESCE(m.m4, r.m4, 0) as m4'),
                DB::raw('COALESCE(m.total, r.total, 0) as total')
            );

        // Combinar todas las consultas
        $datos = $query1->union($query2)->union($query3)
            ->orderBy($tipo === 'marca' ? 'marca' : 'rin')
            ->get();
            
        // Retornar datos según el tipo solicitado
        if ($tipo === 'marca') {
            return [
                'hoja_1' => $this->agruparPorMarca($datos),
                'hoja_2' => $this->obtenerSubtotalesPorMarca($datos)
            ];
        } else {
            return [
                'hoja_3' => $this->agruparPorRin($datos),
                'hoja_4' => $this->obtenerSubtotalesPorRin($datos)
            ];
        }
    }



    public function agruparPorMarca($datos)
    {
        $resultado = [];
        $totalesMarca = [];
        $totalGeneral = [
            'marca' => 'TOTAL GENERAL',
            'rin' => '',
            'articulo' => '',
            'descripcion' => '',
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'total' => 0,
            'stock' => 0,
        ];

        $marcaActual = null;
        $subtotal = [
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'total' => 0,
            'stock' => 0,
        ];

        foreach ($datos as $fila) {
            if ($marcaActual !== $fila->marca) {
                //Si hay una marca anterior agregar el subtotal
                if ($marcaActual !== null) {
                    $resultado[] = (object)[
                        'marca' => "$marcaActual TOTAL",
                        'rin' => '',
                        'articulo' => '',
                        'descripcion' => '',
                        'm1' => $subtotal['m1'],
                        'm2' => $subtotal['m2'],
                        'm3' => $subtotal['m3'],
                        'm4' => $subtotal['m4'],
                        'total' => $subtotal['total'],
                        'stock' => $subtotal['stock'],
                    ];
                }

                //Reiniciar subtotal
                $subtotal = ['m1' => 0, 'm2' => 0, 'm3' => 0, 'm4' => 0, 'total' => 0, 'stock' => 0,];
                $marcaActual = $fila->marca;
            }

            //Agregar fila normal
            $resultado[] = $fila;

            //Acumular subtotal por marca
            $subtotal['m1'] += $fila->m1;
            $subtotal['m2'] += $fila->m2;
            $subtotal['m3'] += $fila->m3;
            $subtotal['m4'] += $fila->m4;
            $subtotal['total'] += $fila->total;
            $subtotal['stock'] += $fila->stock;

            //Acumular total general
            $totalGeneral['m1'] += $fila->m1;
            $totalGeneral['m2'] += $fila->m2;
            $totalGeneral['m3'] += $fila->m3;
            $totalGeneral['m4'] += $fila->m4;
            $totalGeneral['total'] += $fila->total;
            $totalGeneral['stock'] += $fila->stock;
        }

        //Agregar último subtotal
        if ($marcaActual !== null) {
            $resultado[] = (object)[
                'marca' => "$marcaActual TOTAL",
                'rin' => '',
                'articulo' => '',
                'descripcion' => '',
                'm1' => $subtotal['m1'],
                'm2' => $subtotal['m2'],
                'm3' => $subtotal['m3'],
                'm4' => $subtotal['m4'],
                'total' => $subtotal['total'],
                'stock' => $subtotal['stock'],
            ];
        }

        // Agregar total general
        $resultado[] = (object) $totalGeneral;

        return $resultado;
    }

    //Agrupar por rin
    public function agruparPorRin($datos)
    {
        $resultado = [];
        $totalesMarca = [];
        $totalGeneral = [
            'marca' => '',
            'rin' => 'TOTAL GENERAL',
            'articulo' => '',
            'descripcion' => '',
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'total' => 0,
            'stock' => 0,
        ];

        $rinActual = null;
        $subtotal = [
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'total' => 0,
            'stock' => 0,
        ];

        foreach ($datos as $fila) {
            if ($rinActual !== $fila->rin) {
                //Si hay una marca anterior agregar el subtotal
                if ($rinActual !== null) {
                    $resultado[] = (object)[
                        'marca' => '',
                        'rin' => "$rinActual TOTAL",
                        'articulo' => '',
                        'descripcion' => '',
                        'm1' => $subtotal['m1'],
                        'm2' => $subtotal['m2'],
                        'm3' => $subtotal['m3'],
                        'm4' => $subtotal['m4'],
                        'total' => $subtotal['total'],
                        'stock' => $subtotal['stock'],
                    ];
                }

                //Reiniciar subtotal
                $subtotal = ['m1' => 0, 'm2' => 0, 'm3' => 0, 'm4' => 0, 'total' => 0, 'stock' => 0,];
                $rinActual = $fila->rin;
            }

            //Agregar fila normal
            $resultado[] = $fila;

            //Acumular subtotal por marca
            $subtotal['m1'] += $fila->m1;
            $subtotal['m2'] += $fila->m2;
            $subtotal['m3'] += $fila->m3;
            $subtotal['m4'] += $fila->m4;
            $subtotal['total'] += $fila->total;
            $subtotal['stock'] += $fila->stock;

            //Acumular total general
            $totalGeneral['m1'] += $fila->m1;
            $totalGeneral['m2'] += $fila->m2;
            $totalGeneral['m3'] += $fila->m3;
            $totalGeneral['m4'] += $fila->m4;
            $totalGeneral['total'] += $fila->total;
            $totalGeneral['stock'] += $fila->stock;
        }

        //Agregar último subtotal
        if ($rinActual !== null) {
            $resultado[] = (object)[
                'marca' => '',
                'rin' => "$rinActual TOTAL",
                'articulo' => '',
                'descripcion' => '',
                'm1' => $subtotal['m1'],
                'm2' => $subtotal['m2'],
                'm3' => $subtotal['m3'],
                'm4' => $subtotal['m4'],
                'total' => $subtotal['total'],
                'stock' => $subtotal['stock'],
            ];
        }

        // Agregar total general
        $resultado[] = (object) $totalGeneral;

        return $resultado;
    }

    public function obtenerSubtotalesPorMarca($datos)
    {
        $resultado = [];
        $totalGeneral = [
            'marca' => 'TOTAL GENERAL',
            'rin' => '',
            'articulo' => '',
            'descripcion' => '',
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'total' => 0,
            'stock' => 0,
        ];

        $subtotales = [];

        foreach ($datos as $fila) {
            $marca = $fila->marca;

            if (!isset($subtotales[$marca])) {
                $subtotales[$marca] = [
                    'marca' => $marca,
                    'rin' => '',
                    'articulo' => '',
                    'descripcion' => '',
                    'm1' => 0,
                    'm2' => 0,
                    'm3' => 0,
                    'm4' => 0,
                    'total' => 0,
                    'stock' => 0,
                ];
            }

            // Acumular subtotales por marca
            $subtotales[$marca]['m1'] += $fila->m1;
            $subtotales[$marca]['m2'] += $fila->m2;
            $subtotales[$marca]['m3'] += $fila->m3;
            $subtotales[$marca]['m4'] += $fila->m4;
            $subtotales[$marca]['total'] += $fila->total;
            $subtotales[$marca]['stock'] += $fila->stock;

            // Acumular total general
            $totalGeneral['m1'] += $fila->m1;
            $totalGeneral['m2'] += $fila->m2;
            $totalGeneral['m3'] += $fila->m3;
            $totalGeneral['m4'] += $fila->m4;
            $totalGeneral['total'] += $fila->total;
            $totalGeneral['stock'] += $fila->stock;
        }

        // Agregar los subtotales al resultado
        foreach ($subtotales as $subtotal) {
            $resultado[] = (object) $subtotal;
        }

        // Agregar total general
        $resultado[] = (object) $totalGeneral;

        return $resultado;
    }

    public function obtenerSubtotalesPorRin($datos)
    {
        $resultado = [];
        $totalGeneral = [
            'marca' => '',
            'rin' => 'TOTAL GENERAL',
            'articulo' => '',
            'descripcion' => '',
            'm1' => 0,
            'm2' => 0,
            'm3' => 0,
            'm4' => 0,
            'total' => 0,
            'stock' => 0,
        ];

        $subtotales = [];

        foreach ($datos as $fila) {
            $rin = $fila->rin;

            if (!isset($subtotales[$rin])) {
                $subtotales[$rin] = [
                    'marca' => '',
                    'rin' => $rin,
                    'articulo' => '',
                    'descripcion' => '',
                    'm1' => 0,
                    'm2' => 0,
                    'm3' => 0,
                    'm4' => 0,
                    'total' => 0,
                    'stock' => 0,
                ];
            }

            // Acumular subtotales por rin
            $subtotales[$rin]['m1'] += $fila->m1;
            $subtotales[$rin]['m2'] += $fila->m2;
            $subtotales[$rin]['m3'] += $fila->m3;
            $subtotales[$rin]['m4'] += $fila->m4;
            $subtotales[$rin]['total'] += $fila->total;
            $subtotales[$rin]['stock'] += $fila->stock;

            // Acumular total general
            $totalGeneral['m1'] += $fila->m1;
            $totalGeneral['m2'] += $fila->m2;
            $totalGeneral['m3'] += $fila->m3;
            $totalGeneral['m4'] += $fila->m4;
            $totalGeneral['total'] += $fila->total;
            $totalGeneral['stock'] += $fila->stock;
        }

        // Agregar los subtotales al resultado
        foreach ($subtotales as $subtotal) {
            $resultado[] = (object) $subtotal;
        }

        // Agregar total general
        $resultado[] = (object) $totalGeneral;

        return $resultado;
    }
}
