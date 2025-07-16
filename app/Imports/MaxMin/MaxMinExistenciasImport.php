<?php

namespace App\Imports\MaxMin;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\MaxMinExistencias;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Log;

class MaxMinExistenciasImport implements ToCollection, WithStartRow
{

    public function startRow(): int
    {
        return 2; // Empieza a procesar datos desde la fila 2
    }

    public function collection(Collection $rows)
    {
        //Crear array para amacenar articulos por marca y stok
        $articulosAgrupados = [];

        foreach ($rows as $row) {
            $marca = $row[1];
            $rin = $row[2];
            $articulo = $row[3];
            $descripcion = $row[4];
            $stock = $row[7];

            if (empty($marca)) {
                continue;
            }

            //Si el articulo ya existe en el array, sumar el stock
            if (isset($articulosAgrupados[$articulo])) {
                $articulosAgrupados[$articulo]['stock'] += $stock;
            } else {
                //Si no existe agregar el articulo al array
                $articulosAgrupados[$articulo] = [
                    'marca' => $marca,
                    'rin' => $rin,
                    'articulo' => $articulo,
                    'descripcion' => $descripcion,
                    'stock' => $stock,
                ];
            }
        }

        //Insertar en la base de datos
        foreach ($articulosAgrupados as $articulo) {
            MaxMinExistencias::create([
                'marca' => $articulo['marca'],
                'rin' => $articulo['rin'],
                'articulo' => $articulo['articulo'],
                'descripcion' => $articulo['descripcion'],
                'stock' => $articulo['stock'],
            ]);
        }
    }
}
