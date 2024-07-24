<?php
require_once '../model/ExportExcel.php';
require_once '../model/ExportPDF.php';
require_once '../model/registroAmbientes.php';

class ExportController
{
    private $headers = [];
    private $data = [];
    private $results;
    private $title;
    private $subtitle;

    // Función que recibe los parámetros necesarios para imprimir Excel o PDF de cualquier tipo de registro
    public function export($startDate, $endDate, $selectedItem, $format, $report)
    {
        $startDatetime = "$startDate 00:00:00";
        $endDatetime = "$endDate 23:59:59";
        $classInstance = $this->classSelector($report);

        if (!$classInstance) {
            header('Location: ../index.php');
            exit;
        }

        $this->results = $classInstance->getGroupOfHistory($selectedItem, $startDatetime, $endDatetime);

        if ($this->results == null) {
            header('Location: ../not-found.php');
            exit;
        } else {
            $this->prepareReportData($report);
            $this->validateFormat($format);
        }
    }

    private function classSelector($reportType)
    {
        switch ($reportType) {
            case 'registroAmbientes':
                return new RegistroAmbientes();
            case 'registroEquipos':
                // return new RegistroEquipos();
                break;
            default:
                return null;
        }
    }

    private function prepareReportData($report)
    {
        switch ($report) {
            case "registroAmbientes":
                $roomNumber = $this->results[0]['numero'];
                $this->title = "Reporte Registros de Ambiente";
                $this->subtitle = "Reporte de Registros del Ambiente $roomNumber";

                $this->headers = ['Registro', 'Entrada', 'Salida', 'Llaves', 'Televisor', 'Aire', 'Instructor'];
                foreach ($this->results as $row) {
                    $this->data[] = [
                        $row['idRegistro'],
                        $row['inicio'],
                        $row['fin'],
                        $row['llaves'],
                        $row['controlTv'],
                        $row['controlAire'],
                        $row['instructor']
                    ];
                }
                break;

            default:
                header('Location: ../index.php');
                exit;
        }
    }

    private function validateFormat($format)
    {
        if ($format === "pdf") {
            $pdfInstance = new ExportPDF($this->title, "Desconocido", '../view/img/logoSena.png', 26);
            $pdfInstance->addPage();
            $pdfInstance->logo();
            $pdfInstance->setFont('helvetica', 'B', 12);
            $pdfInstance->writeText($this->subtitle);
            $printDate = 'Fecha de impresión: ' . date('Y-m-d H:i:s');
            $pdfInstance->writeText($printDate);
            $pdfInstance->addTable($this->headers, $this->data);
            $pdfInstance->output('reporte.pdf', 'I');

        } elseif ($format === "excel") {
            $exporter = new ExcelExporter();
            $exporter->setHeaders($this->headers);
            $exporter->addData($this->data);
            $exporter->output('reporte.xlsx');
        }
    }
}