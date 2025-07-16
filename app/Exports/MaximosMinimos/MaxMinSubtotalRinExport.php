<?php

namespace App\Exports\MaximosMinimos;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Services\MaxMinService\CalculosMaxMinService;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class MaxMinSubtotalRinExport implements WithEvents, WithTitle, WithDrawings
{
    protected $service;

    public function __construct(CalculosMaxMinService $service)
    {
        $this->service = $service;
    }

    //Nombre de la hoja
    public function title(): string
    {
        return "TOTAL RIN";
    }

    //Agregar imagen
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('FUTURAMA TIRES LOGO');
        $drawing->setPath(public_path('img/futurama_logo2.png')); // Ruta de la imagen
        $drawing->setHeight(78);
        $drawing->setCoordinates('C3'); // Posición en el Excel
        return [$drawing];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                //Obtener factor
                $fecha = date("d-m-Y"); // Fecha actual
                $factor = $this->calcularFactor($fecha); // Calcular el factor
                $fecha_ayer = $this->obtenerFecha($fecha);
                $meses = $this->obtenerMeses($fecha_ayer);

                //INSERCCIÓN DE DATOS
                // Escribimos datos principales del archivo
                $sheet->setCellValue("A3", "FECHA");
                $sheet->setCellValue("A4", $fecha_ayer);
                $sheet->setCellValue('A5', "FACTOR");
                $sheet->setCellValue('A6', $factor);
                $sheet->setCellValue('A7', "TOTAL RIN " . $fecha_ayer);

                //Escribimos los encabezados
                $encabezado = ['RIN', $meses['m3'], $meses['m2'], $meses['m1'], $meses['mesActual'], 'TOTAL', 'STOCK', 'PORCENTAJE'];
                $sheet->fromArray($encabezado, null, 'A10');

                // **Obtener los datos de la consulta
                $datos = $this->service->obtenerDatos('rin'); // Obtiene el array con 'hoja_1' y 'hoja_2'
                $filas = $datos['hoja_4']; // Accedemos solo a los datos de la hoja 1
                $totales = end($filas);

                //Insertamos datos en el archivo
                $filaInicio = 11;
                $colorearFilaG = [];
                $ultimaFila = $filaInicio + count($filas) - 1;

                foreach ($filas as $fila) {
                    $data[] = [
                        $fila->rin ?? '', //A
                        $fila->m1 ?? 0, // B
                        $fila->m2 ?? 0, // C
                        $fila->m3 ?? 0, // D
                        $fila->m4 ?? 0, // E
                        $fila->total ?? 0, // F
                        $fila->stock ?? 0, // G
                        "=G$filaInicio/$totales->stock",
                    ];

                    $filaInicio++;
                }

                // Insertar datos en bloque
                $sheet->fromArray($data, null, 'A11');

                //Aplicar formato de porcentaje
                $sheet->getStyle('H11:H' . $ultimaFila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

                // Asegurar que las celdas vacías en D a I se muestren como 0
                $lastRow = $filaInicio - 1; // Última fila con datos
                foreach (range('B', 'G') as $col) { // Solo columnas D a I
                    for ($row = 11; $row <= $lastRow; $row++) {
                        $cell = $col . $row;
                        if (trim($sheet->getCell($cell)->getValue()) === '') { // Validar vacío
                            $sheet->setCellValueExplicit($cell, 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                        }
                    }
                }


                //ESTILOS
                // FORMATO: CENTRAR TODOS LOS TEXTOS
                $sheet->getStyle("A3:H$ultimaFila")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // FORMATO: NEGRITAS
                $filas = ["A11:A$ultimaFila", "G11:G$ultimaFila"];
                $negritas = ["A3:A7", "A10:H10"]; // Encabezados y títulos
                foreach ($filas as $rango) {
                    $negritas[] = $rango; // Agregar filas de artículos vacíos
                }
                foreach ($negritas as $rango) {
                    $sheet->getStyle($rango)->getFont()->setBold(true);
                }

                // FORMATO: COLORES
                $sheet->getStyle("A10:H10")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'a2a2a2'] // Gris claro para encabezados
                    ]
                ]);

                $colorearFilaG = ["G11:G$ultimaFila"];
                foreach ($colorearFilaG as $celda) {
                    $sheet->getStyle($celda)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '3fb745'] // Azul para stock
                        ]
                    ]);
                }

                // TOTAL GENERAL (Última fila)
                $totalGeneralFila = "A$ultimaFila:H$ultimaFila";
                $sheet->getStyle($totalGeneralFila)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFD700'] // Amarillo oro
                    ]
                ]);

                //Combinamos la celdas
                $sheet->mergeCells('A3:B3');
                $sheet->mergeCells('A4:B4');
                $sheet->mergeCells('A5:B5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('A7:B7');

                //Ancho de celdas
                foreach (range('A', 'H') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                //Agregamos el filtro en los encabezados
                $sheet->setAutoFilter('A10:H12');

                //Bloqueamos la celda
                $sheet->freezePane('B11');
            }
        ];
    }

    /**
     * Función para calcular el factor según la fecha
     * 
     * @param string $fecha Fecha de referencia
     * @return int El valor del factor calculado
     */
    private function calcularFactor($fecha)
    {
        // Obtener el día de la semana (0 para domingo, 1 para lunes, ..., 6 para sábado)
        $dia_semana = date('w', strtotime($fecha));

        // Verificar si es lunes
        if ($dia_semana == 1) {
            // Si es lunes, tomar el número del día del sábado (2 días atrás)
            $factorId = date('j', strtotime('-2 days', strtotime($fecha)));
        } else {
            // Si no es lunes, simplemente tomar el número del día anterior
            $factorId = date('j', strtotime('-1 day', strtotime($fecha)));
        }

        // Obtener el factor desde la base de datos
        $factor = DB::table('factor')->where('id', $factorId)->value('factor') ?? 1;

        return $factor;
    }

    //Función para obtener fechas
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

    //Función para obtener meses
    private function obtenerMeses($fecha)
    {

        $meses = array(
            "Jan" => "ENE",
            "Feb" => "FEB",
            "Mar" => "MAR",
            "Apr" => "ABR",
            "May" => "MAY",
            "Jun" => "JUN",
            "Jul" => "JUL",
            "Aug" => "AGO",
            "Sep" => "SEP",
            "Oct" => "OCT",
            "Nov" => "NOV",
            "Dec" => "DIC",
        );

        $fechaCarbon = Carbon::parse($fecha);

        // Mes actual (siempre el mes de la fecha proporcionada)
        $mes_actual = $meses[$fechaCarbon->format('M')];

        // Mes anterior (primer día del mes actual menos 1 día para evitar problemas con días que no existen)
        $m1 = $meses[$fechaCarbon->copy()->startOfMonth()->subDay()->format('M')];

        // 2 meses antes
        $m2 = $meses[$fechaCarbon->copy()->startOfMonth()->subMonths(1)->startOfMonth()->subDay()->format('M')];

        // 3 meses antes
        $m3 = $meses[$fechaCarbon->copy()->startOfMonth()->subMonths(2)->startOfMonth()->subDay()->format('M')];

        Log::info("Fecha calculo meses: {$fecha} Mes actual: {$mes_actual} Mes pasado: {$m1} Hace 2 meses: {$m2} Hace 3 meses: {$m3}");

        return [
            'mesActual' => $mes_actual,
            'm1' => $m1,
            'm2' => $m2,
            'm3' => $m3,
        ];
    }
}
