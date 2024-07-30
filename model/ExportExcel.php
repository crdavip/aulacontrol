<?php
require '../autoload.php';
require '../plugins/PhpSpreadsheet/src/PhpSpreadsheet/Spreadsheet.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExcelExporter
{
    private $spreadsheet;
    private $sheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function setHeaders($headers)
    {
        $col = 'B';
        foreach ($headers as $header) {
            $this->sheet->setCellValue($col . '3', $header);
            $col++;
        }
        $this->setHeaderStyles();
    }

    public function addData($data)
    {
        if (empty($data)) {
            return;
        }

        $rowNum = 4;
        foreach ($data as $row) {
            $col = 'B';
            foreach ($row as $cell) {
                $cellCoordinate = $col . $rowNum;
                $this->sheet->setCellValue($cellCoordinate, $cell);
                $this->sheet->getStyle($cellCoordinate)->applyFromArray($this->getDataCellStyle());
                $col++;
            }
            $rowNum++;
        }
        $this->setAutoColumnWidths();
        // Ajusta el rango para los bordes a partir de B3 hasta el final de los datos
        $this->setTableBorders('B3', $this->sheet->getHighestColumn(), $rowNum - 1);
    }

    private function setHeaderStyles()
    {
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => '0FC500'], // Verde
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];

        $highestColumn = $this->sheet->getHighestColumn();
        $this->sheet->getStyle('B3:' . $highestColumn . '3')->applyFromArray($headerStyle);
    }

    private function getDataCellStyle()
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
    }

    private function setAutoColumnWidths()
    {
        foreach ($this->sheet->getColumnIterator() as $column) {
            $this->sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    private function setTableBorders($startCell, $endColumn, $endRow)
    {
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->sheet->getStyle($startCell . ':' . $endColumn . $endRow)->applyFromArray($borderStyle);
    }

    public function save($filename)
    {
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filename);
    }

    public function output($filename)
    {
        // Verifica si la hoja contiene datos válidos
        $dataValid = $this->sheet->getCell('B4')->getValue() !== 'No hay datos disponibles';

        if ($dataValid) {
            $writer = new Xlsx($this->spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        } else {
            // Manejo de errores si los datos no son válidos
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            echo 'No hay datos disponibles';
        }
        exit;
    }
}