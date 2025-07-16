<?php

namespace App\Imports\MaxMin;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Models\MaxMinRin;

class MaxMinRinImport implements ToModel, WithStartRow
{

    private static $lastRowData = null; // Guardará la última fila

    public function startRow(): int
    {
        return 9; // Empieza a procesar datos desde la fila 9
    }

    public function model(array $row)
    {

        // Si la fila está vacía, la ignoramos
        if (empty(array_filter($row))) {
            return null;
        }

        // Si existe una última fila guardada, registramos la anterior
        if (self::$lastRowData !== null) {
            $previousRow = self::$lastRowData;
            self::$lastRowData = $row; // Guardamos la nueva última fila
            return new MaxMinRin([
                'marca'      => $previousRow[0] ?? "-",
                'rin'        => $previousRow[1] ?? 0,
                'articulo'   => $previousRow[2] ?? "-",
                'descripcion' => $previousRow[4] ?? "-",
                'stock'      => isset($previousRow[5]) ? (int)str_replace([',', ' '], '', $previousRow[5]) : 0,
                'm1'         => isset($previousRow[6]) ? (int)str_replace([',', ' '], '', $previousRow[6]) : 0,
                'm2'         => isset($previousRow[7]) ? (int)str_replace([',', ' '], '', $previousRow[7]) : 0,
                'm3'         => isset($previousRow[8]) ? (int)str_replace([',', ' '], '', $previousRow[8]) : 0,
                'm4'         => isset($previousRow[9]) ? (int)str_replace([',', ' '], '', $previousRow[9]) : 0,
                'total'      => isset($previousRow[10]) ? (int)str_replace([',', ' '], '', $previousRow[10]) : 0,
            ]);
        }

        // Si es la primera fila procesada, solo la guardamos como referencia
        self::$lastRowData = $row;

        return null; // No insertamos la primera vez
    }
}
