<?php

namespace App\Exports\Cotizador;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class HojaAgricolaIndustrialExport implements WithEvents, WithTitle, WithDrawings, WithColumnWidths
{
    protected $datos;

    public function __construct(array $datos)
    {
        $this->datos = collect($datos)->filter(function ($item) {
            $app = strtolower($item['aplicacion'] ?? '');
            return str_contains($app, 'agricol') ||
                str_contains($app, 'industri');
        });
    }

    //Nombre de la hoja
    public function title(): string
    {
        return "AGRICOLA-INDUSTRIAL";
    }

    //Agregar imagen
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('FUTURAMA TIRES LOGO');
        $drawing->setPath(public_path('img/futurama_logo2.png')); // Ruta de la imagen
        $drawing->setHeight(85);
        $drawing->setCoordinates('C3'); // Posición en el Excel
        return [$drawing];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Aplicar formato numérico a las columnas de precios
                /*$columns = ['H', 'I', 'J', 'K', 'L'];
                foreach ($columns as $column) {
                    $sheet->getStyle($column . '11:' . $column . $sheet->getHighestRow())
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }*/

                $sheet->freezePane('C11');  //Bloqueamos la celda

                $fecha = date("d-m-Y"); // Fecha actual

                //Combinamos la celdas
                $sheet->mergeCells('A2:B2');
                $sheet->mergeCells('A3:B3');
                $sheet->mergeCells('A4:B4');
                $sheet->mergeCells('A5:B5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('A7:B7');
                $sheet->mergeCells('A8:B8');

                // Escribimos el encabezado del archivo
                $sheet->setCellValue('A2', "::: NOTA IMPORTANTE :::");
                $sheet->setCellValue('A3', "UNICAMENTE SE MUESTRA LA DISPONIBILIDAD PARA LA VENTA");
                $sheet->setCellValue('A4', "LOS PRECIOS INCLUYEN I.V.A");
                $sheet->setCellValue('A5', "LOS PRECIOS SE ENCUENTRAN SUJETOS A CAMBIOS SIN PREVIO AVISO");
                $sheet->setCellValue('A6', "UNA VEZ SALIDA LA MERCANCIA NO SE ACEPTAN DEVOLUCIONES");
                $sheet->setCellValue('A7', "Fecha");
                $sheet->setCellValue('A8', $fecha);

                //Encabecado
                $encabezado = [
                    'ARTÍCULO',
                    'DESCRIPCIÓN',
                    'APLICACIÓN',
                    'OE',
                    'MEDIDA_EQUIVALENTE',
                    'MARCA',
                    'PROMOCIÓN',
                    'SEMI - MAYOREO',
                    'MAYOREO',
                    'PROM. DEL MES',
                    'NK',
                    'PROM. PRONTO PAGO',
                    'MAT',
                    'VGR',
                    'VAL',
                    'PON',
                    'IXT',
                    'QRO',
                    'GDL',
                    'OBR',
                    'MAY OBR',
                    'JOJ',
                    'PLA',
                    'JOR',
                    'MIS',
                    'CHA',
                    'AHU',
                    'VIN',
                    'BDC',
                ];

                $sheet->fromArray($encabezado, null, 'A10');

                //Agregamos el filtro automatico en los encabezados
                $sheet->setAutoFilter('A10:AC10');

                //Insertamos datos en el archivo
                $filaInicio = 11;

                foreach ($this->datos as $dato) {
                    $fila = $filaInicio++;
                    $sheet->setCellValue('A' . $fila, $dato['itemid'] ?? '');
                    $sheet->setCellValue('B' . $fila, $dato['descripcion'] ?? '');
                    $sheet->setCellValue('C' . $fila, $dato['aplicacion'] ?? '');
                    $sheet->setCellValue('D' . $fila, $dato['oe'] ?? '');
                    $sheet->setCellValue('E' . $fila, $dato['medida_equivalente'] ?? '');
                    $sheet->setCellValue('F' . $fila, $dato['marca'] ?? '');
                    $sheet->setCellValue('G' . $fila, $dato['promocion'] ?? '');
                    $sheet->setCellValue('H' . $fila, isset($dato['semi_mayoreo']) ? str_replace(",", "", $dato['semi_mayoreo']) : 'NO SE PUDO OBTENER');
                    $sheet->setCellValue('I' . $fila, isset($dato['mayoreo']) ? str_replace(",", "", $dato['mayoreo']) : 'NO SE PUDO OBTENER');
                    $sheet->setCellValue('J' . $fila, isset($dato['promocion_del_mes']) ? str_replace(",", "", $dato['promocion_del_mes']) : 'NO SE PUDO OBTENER');
                    $sheet->setCellValue('K' . $fila, isset($dato['nk']) ? str_replace(",", "", $dato['nk']) : 'NO SE PUDO OBTENER');
                    $sheet->setCellValue('L' . $fila, isset($dato['promocion_por_pronto_pago']) ? str_replace(",", "", $dato['promocion_por_pronto_pago']) : 'NO SE PUDO OBTENER');
                    $sheet->setCellValue('M' . $fila, isset($dato['matriz']) ? $dato['matriz'] : 0);
                    $sheet->setCellValue('N' . $fila, isset($dato['vicente_guerrero']) ? $dato['vicente_guerrero'] : 0);
                    $sheet->setCellValue('O' . $fila, isset($dato['vallejo']) ? $dato['vallejo'] : 0);
                    $sheet->setCellValue('P' . $fila, isset($dato['poniente']) ? $dato['poniente'] : 0);
                    $sheet->setCellValue('Q' . $fila, isset($dato['ixtapaluca']) ? $dato['ixtapaluca'] : 0);
                    $sheet->setCellValue('R' . $fila, isset($dato['queretaro']) ? $dato['queretaro'] : 0);
                    $sheet->setCellValue('S' . $fila, isset($dato['guadalajara']) ? $dato['guadalajara'] : 0);
                    $sheet->setCellValue('T' . $fila, isset($dato['obregon']) ? $dato['obregon'] : 0);
                    $sheet->setCellValue('U' . $fila, isset($dato['obregon_mayoreo']) ? $dato['obregon_mayoreo'] : 0);
                    $sheet->setCellValue('V' . $fila, isset($dato['jojutla']) ? $dato['jojutla'] : 0);
                    $sheet->setCellValue('W' . $fila, isset($dato['plasticos']) ? $dato['plasticos'] : 0);
                    $sheet->setCellValue('X' . $fila, isset($dato['jorges']) ? $dato['jorges'] : 0);
                    $sheet->setCellValue('Y' . $fila, isset($dato['misael']) ? $dato['misael'] : 0);
                    $sheet->setCellValue('Z' . $fila, isset($dato['chamilpa']) ? $dato['chamilpa'] : 0);
                    $sheet->setCellValue('AA' . $fila, isset($dato['ahuatepec']) ? $dato['ahuatepec'] : 0);
                    $sheet->setCellValue('AB' . $fila, isset($dato['vinilos']) ? $dato['vinilos'] : 0);
                    $sheet->setCellValue('AC' . $fila, isset($dato['bodega_de_camion']) ? $dato['bodega_de_camion'] : 0);


                    //$sheet->getStyle("H$fila:L$fila")->getNumberFormat()->setFormatCode('$ 0.00');


                    if ($fila % 2 === 0) {
                        $sheet
                            ->getStyle("A$fila:AC$fila")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('ABB2B9');
                    }
                }



                //Estilos
                //Centrar texto en las celdas A1:B8
                $sheet->getStyle("A1:B8")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                //Centrar texto de las celdas F11:AG ultima fila
                $ultimaFila = $sheet->getHighestRow();
                $sheet->getStyle("F11:AC$ultimaFila")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                //Formato de moneda
                $sheet->getStyle("H11:L$ultimaFila")->getNumberFormat()
                    ->setFormatCode('$#,##0.00');

                $sheet->getStyle("A3:B3")->getFont()->setBold(true);
                $sheet->getStyle("A10:AC10")->getFont()->setBold(true);

                //Texto blanco en el encabezado
                $sheet->getStyle("A10:AC10")->getFont()->getColor()->setRGB('FFFFFF');

                // FORMATO: COLORES
                $sheet->getStyle("A10:AC10")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '000000'] // Gris claro para encabezados
                    ]
                ]);

                //Borde de las celdas
                $bordes = [
                    'borders' => [
                        'horizontal' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ],
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ]
                    ]
                ];

                $sheet->getStyle("A11:AC$ultimaFila")->applyFromArray($bordes);

                //Asignamos el color Rojo
                $sheet->getstyle('A6')
                    ->getFont()
                    ->setBold(true)
                    ->setName('Arial')
                    ->setSize(10)
                    ->getColor()->setRGB('FA0C01');
            }
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 23,
            'B' => 85,
            'C' => 15,
            'D' => 18,
            'E' => 23,
            'F' => 17,
            'G' => 48,
            'H' => 15,
            'I' => 13,
            'J' => 16,
            'K' => 10,
            'L' => 17,
            'M' => 7,
            'N' => 7,
            'O' => 7,
            'P' => 7,
            'Q' => 7,
            'R' => 7,
            'S' => 7,
            'T' => 7,
            'U' => 12,
            'V' => 7,
            'W' => 7,
            'X' => 7,
            'Y' => 7,
            'Z' => 7,
            'AA' => 7,
            'AB' => 7,
            'AC' => 7,
        ];
    }
}
