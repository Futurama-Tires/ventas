<?php

namespace App\Exports\Cotizador;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CotizadorExport implements WithMultipleSheets
{
    protected $datos;

    public function __construct(array $datos)
    {
        $this->datos = $datos;
    }

    public function sheets(): array
    {
        return [
            'MAYOREO' => new HojaGeneralExport($this->datos),
            'MOTOCICLETAS' => new HojaMotosExport($this->datos),
            'AGRICOLA-INDUSTRIAL' => new HojaAgricolaIndustrialExport($this->datos),
        ];
    }
}
