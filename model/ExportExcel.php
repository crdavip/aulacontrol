<?php
require '../autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;

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
        $col = 'A';
        foreach ($headers as $header) {
            $this->sheet->setCellValue($col . '1', $header);
            $col++;
        }
        $this->setHeaderStyles();
    }

    public function addData($data)
    {
      $rowNum = 2;
      foreach ($data as $row) {
          $col = 'A';
          foreach ($row as $cell) {
              $cellCoordinate = $col . $rowNum;
              $this->sheet->setCellValue($cellCoordinate, $cell);
              $this->sheet->getStyle($cellCoordinate)->applyFromArray($this->getDataCellStyle());
              $col++;
          }
          $rowNum++;
      }
      $this->setAutoColumnWidths();
      $this->setTableBorders('A1', $this->sheet->getHighestColumn(), $rowNum - 1);
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
                'color' => ['argb' => 'FF4F81BD'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];

        $highestColumn = $this->sheet->getHighestColumn();
        $this->sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray($headerStyle);
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
        $writer = new Xlsx($this->spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}
