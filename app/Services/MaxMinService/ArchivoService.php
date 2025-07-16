<?php

namespace App\Services\MaxMinService;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MaxMin\MaxMinMarcaImport;
use App\Imports\MaxMin\MaxMinRinImport;
use App\Imports\MaxMin\MaxMinExistenciasImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArchivoService
{
    public function procesarArchivo($archivo)
    {
        $nombreArchivo = $archivo->getClientOriginalName();
        $path = $archivo->storeAs('uploads', $nombreArchivo);
        $fullPath = storage_path("app/$path");

        try {
            if (str_contains($nombreArchivo, 'MaximosyMinimosMarca')) {
                DB::table('maxminmarca')->truncate();
                Excel::import(new MaxMinMarcaImport, $fullPath);
            } elseif (str_contains($nombreArchivo, 'MaximosyMinimosRin')) {
                DB::table('maxminrin')->truncate();
                Excel::import(new MaxMinRinImport, $fullPath);
            } elseif (str_contains($nombreArchivo, 'ResultadosFUTExistenciasAux')) {
                DB::table('maxminexistencias')->truncate();
                Excel::import(new MaxMinExistenciasImport, $fullPath);
            }
        } finally {
            // Elimina el archivo después de procesarlo
            Storage::delete($path);
        }
    }
}
