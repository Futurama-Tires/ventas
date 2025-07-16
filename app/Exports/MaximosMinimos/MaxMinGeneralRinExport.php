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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class MaxMinGeneralRinExport implements WithEvents, WithTitle, WithDrawings
{
    protected $service;

    public function __construct(CalculosMaxMinService $service)
    {
        $this->service = $service;
    }

    //Nombre de la hoja
    public function title(): string
    {
        return "MAX MIN RIN";
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
                $sheet->setCellValue('A7', "MÁXIMOS Y MÍNIMOS RIN " . $fecha_ayer);

                //Escribimos los encabezados
                $encabezado = ['RIN', 'ARTÍCULO', 'DESCRIPCIÓN', $meses['m3'], $meses['m2'], $meses['m1'], $meses['mesActual'], 'TOTAL', 'STOCK', 'MÍNIMO', 'MÁXIMO', 'PEDMIN', 'PED', 'PROM', 'VENTA MES'];
                $sheet->fromArray($encabezado, null, 'A10');

                // **Obtener los datos de la consulta
                $datos = $this->service->obtenerDatos('rin'); // Obtiene el array con 'hoja_3' y 'hoja_4'
                $filas = $datos['hoja_3']; // Accedemos solo a los datos de la hoja 1

                //Insertamos datos en el archivo
                $filaInicio = 11;
                $data = [];
                $formulas = [];
                $resaltarFilas = [];
                $colorearFilaI = [];
                $filaSinVentas = [];
                $ultimaFila = $filaInicio + count($filas) - 1;

                // Preparar datos y fórmulas en arrays
                foreach ($filas as $fila) {
                    $data[] = [
                        $fila->rin ?? '', // A
                        $fila->articulo ?? '', // B
                        $fila->descripcion ?? '', // C
                        $fila->m1 ?? 0, // D
                        $fila->m2 ?? 0, // E
                        $fila->m3 ?? 0, // F
                        $fila->m4 ?? 0, // G
                        $fila->total ?? 0, // H
                        $fila->stock ?? 0, // I
                        '',
                        '',
                        '',
                        '',
                        '',
                        '' // J a O en blanco por defecto
                    ];

                    if (!empty($fila->articulo)) {
                        $formulas[] = [
                            "=ROUND(H$filaInicio/A\$6,0)",  // J - MÍNIMO
                            "=ROUND((H$filaInicio/A\$6)*2,0)", // K - MÁXIMO
                            "=ROUND(J$filaInicio-I$filaInicio,0)", // L - PEDMIN
                            "=ROUND(K$filaInicio-I$filaInicio,0)", // M - PED
                            "=ROUND(H$filaInicio/A\$6,0)", // N - PROM
                            "=IF(I$filaInicio>0,IF(N$filaInicio>0,ROUND(I$filaInicio/N$filaInicio,0),0),0)", // O - VENTA MES
                        ];
                    } else {
                        $formulas[] = array_fill(0, 6, ""); // Si no hay artículo, dejar celdas vacías
                        $resaltarFilas[] = "A$filaInicio:I$filaInicio"; // Guardamos para resaltar después
                    }

                    if ($fila->total == 0) { //Si hay articulos sin venta
                        $filaSinVentas[] = "A$filaInicio:H$filaInicio"; //Guardamos para poner texto en rojo
                    }

                    $colorearFilaI[] = "I$filaInicio"; // Colorear toda la columna I
                    $filaInicio++;
                }

                // Insertar datos en bloque
                $sheet->fromArray($data, null, 'A11');

                // Insertar fórmulas en bloque
                $sheet->fromArray($formulas, null, 'J11');

                // Asegurar que las celdas vacías en D a I se muestren como 0
                $lastRow = $filaInicio - 1; // Última fila con datos
                foreach (range('D', 'I') as $col) { // Solo columnas D a I
                    for ($row = 11; $row <= $lastRow; $row++) {
                        $cell = $col . $row;
                        if (trim($sheet->getCell($cell)->getValue()) === '') { // Validar vacío
                            $sheet->setCellValueExplicit($cell, 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                        }
                    }
                }

                //ESTILOS
                // FORMATO: CENTRAR TODOS LOS TEXTOS
                $sheet->getStyle("A3:O$ultimaFila")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // FORMATO: NEGRITAS
                $negritas = ["A3:A7", "A10:O10"]; // Encabezados y títulos
                foreach ($resaltarFilas as $rango) {
                    $negritas[] = $rango; // Agregar filas de artículos vacíos
                }
                foreach ($negritas as $rango) {
                    $sheet->getStyle($rango)->getFont()->setBold(true);
                }

                //FORMATO: TEXTO ROJO SIN VENTAS
                foreach ($filaSinVentas as $rango) {
                    $sheet->getStyle($rango)->getFont()->getColor()->setRGB('ff1a1a');
                }

                // FORMATO: COLORES
                $sheet->getStyle("A10:O10")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'a2a2a2'] // Gris claro para encabezados
                    ]
                ]);

                foreach ($resaltarFilas as $rango) {
                    $sheet->getStyle($rango)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '3fb745'] // Rosa para subtotales
                        ]
                    ]);
                }

                foreach ($colorearFilaI as $celda) {
                    $sheet->getStyle($celda)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '3fb745'] // Azul para stock
                        ]
                    ]);
                }

                // TOTAL GENERAL (Última fila)
                $totalGeneralFila = "A$ultimaFila:I$ultimaFila";
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
                foreach (range('A', 'O') as $column) {
                    if ($column == 'C') {
                        $sheet->getColumnDimension($column)->setWidth(80);
                    } else {
                        $sheet->getColumnDimension($column)->setAutoSize(true);
                    }
                }

                //Agregamos el filtro en los encabezados
                $sheet->setAutoFilter('A10:O12');

                //Bloqueamos la celda
                $sheet->freezePane('C11');
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
