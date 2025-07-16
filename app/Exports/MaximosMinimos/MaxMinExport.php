<?php

namespace App\Exports\MaximosMinimos;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Services\MaxMinService\CalculosMaxMinService;

class MaxMinExport implements WithMultipleSheets
{
    protected $service;

    public function __construct(CalculosMaxMinService $service)
    {
        $this->service = $service;
    }

    public function sheets(): array
    {
        return [
            'MAX MIN MARCA' => new MaxMinGeneralMarcaExport($this->service),
            'TOTAL MARCA' => new MaxMinSubtotalMarcaExport($this->service),
            'MAX MIN RIN' => new MaxMinGeneralRinExport($this->service),
            'TOTAL RIN' => new MaxMinSubtotalRinExport($this->service),
        ];
    }
}
