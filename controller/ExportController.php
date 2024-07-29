<?php
require_once '../model/ExportExcel.php';
require_once '../model/ExportPDF.php';
require_once '../model/registroAmbientes.php';
require_once '../model/usuarios.php';
require_once '../model/registroEquipos.php';
require_once '../model/equipos.php';
require_once '../model/objetos.php';
require_once '../model/registroObjetos.php';
require_once '../model/registroAsistencia.php';

class ExportController
{
    private $headers = [];
    private $data = [];
    private $results;
    private $title;
    private $subtitle;

    public function export($startDate, $endDate, $selectedItem, $format, $report)
    {

        $startDatetime = $startDate;
        $endDatetime = $endDate;

        if ($report !== "registroAsistencia") {
            $startDatetime = "$startDate 00:00:00";
            $endDatetime = "$endDate 23:59:59";
        }

        if (!$report) {
            header('Location: ../index.php');
            exit;
        } else {
            $classInstance = $this->classSelector($report);
            $this->results = $classInstance->getGroupOfHistory($selectedItem, $startDatetime, $endDatetime);
        }

        if ($this->results == null) {
            header('Location: ../not-found.php');
            exit;
        } else {
            $this->prepareReportData($report);
            $this->validateFormat($format);
        }
    }

    public function exportRegAsist($idInstructor, $idSheet, $report, $format) {
        $classInstance = new RegistroAsistencia();
        $this->results = $classInstance->getGroupOfHistoryAssist($idSheet, $idInstructor);
        if ($this->results == null) {
            header('Location: ../not-found.php');
            exit;
        } else {
            $this->prepareReportData($report);
            $this->validateFormat($format);
        }
    }

    public function simpleExport($reportType, $report, $idItem = null)
    {
        switch ($report) {
            case 'usuarios':
                $classInstance = new Usuarios();
                $this->results = $classInstance->getUsersExport();
                break;
            case 'objetos':
                $classInstance = new Objetos();
                $this->results = $classInstance->getObjectsExport();
                break;
            case 'equipos':
                if ($idItem == null) {
                    exit;
                } else {
                    $classInstance = new Equipos();
                    $this->results = $classInstance->getDevicesExport($idItem);
                    break;
                }
            default:
                $classInstance = null;
                break;
        }

        if ($this->results == null) {
            header('Location: ../not-found.php');
            exit;
        } else {
            $this->prepareReportData($report);
            $this->validateFormat($reportType);
        }
    }

    private function classSelector($reportType)
    {
        switch ($reportType) {
            case 'registroAmbientes':
                return new RegistroAmbientes();
            case 'registroEquipos':
                return new RegistroEquipos();
            case 'registroObjetos':
                return new RegistroObjetos();
            case 'registroAsistencia':
                return new RegistroAsistencia();
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
            case "registroAsistencia":
                $roomNumber = $this->results[0]['numeroFicha'];
                $this->title = "Reporte Registros de Asistencias";
                $this->subtitle = "Reporte de Asistencias de la Ficha $roomNumber";

                $this->headers = ['Instructor', 'Fecha', 'Total Aprendices', 'Asistieron', 'Aprendices', 'Ambiente'];
                foreach ($this->results as $row) {
                    $this->data[] = [
                        $row['instructorNombre'],
                        $row['fecha'],
                        $row['totalAprendices'],
                        $row['presentes'],
                        $row['aprendices'],
                        $row['ambiente']
                    ];
                }
                break;
            case "registroEquipos":
                $roomNumber = $this->results[0]['numero'];
                $this->title = "Reporte Registros de Equipos";
                $this->subtitle = "Reporte Equipos del Ambiente $roomNumber";

                $this->headers = ['Registro', 'Inicio', 'Fin', 'Ambiente', 'Referencia', 'Marca', 'Usuario', 'Documento'];
                foreach ($this->results as $row) {
                    $this->data[] = [
                        $row['idRegistro'],
                        $row['inicio'],
                        $row['fin'],
                        $row['numero'],
                        $row['ref'],
                        $row['marca'],
                        $row['usuario'],
                        $row['documento']
                    ];
                }
                break;
            case "registroObjetos":
                $centerNumber = $this->results[0]['centro'];
                $this->title = "Reporte Registros de Objetos";
                $this->subtitle = "Reporte Objetos con destino $centerNumber";

                $this->headers = ['R. Nro', 'Entrada', 'Salida', 'Objeto', 'Detalle', 'Destino', 'Usuario', 'Documento'];
                foreach ($this->results as $row) {
                    $this->data[] = [
                        $row['idRegistro'],
                        $row['inicio'],
                        $row['fin'],
                        $row['idObjeto'],
                        $row['descripcion'],
                        $row['centro'],
                        $row['usuario'],
                        $row['documento']
                    ];
                }
                break;
            case "usuarios":
                $this->title = "Reporte Usuarios";
                $this->subtitle = "Registro de Usuarios";

                $this->headers = ['Documento', 'Nombre', 'Estado', 'Correo', 'Cargo'];
                foreach ($this->results as $row) {
                    $this->data[] = [
                        $row['documento'],
                        $row['nombre'],
                        $row['estado'],
                        $row['correo'],
                        $row['cargo']
                    ];
                }
                break;
            case "objetos":
                $this->title = "Reporte Objetos";
                $this->subtitle = "Registro de Objetos";

                $this->headers = ['Numero', 'Descripcion', 'Color', 'Estado', 'Propietario', 'Documento'];
                foreach ($this->results as $row) {
                    $this->data[] = [
                        $row['idObjeto'],
                        $row['descripcion'],
                        $row['color'],
                        $row['estado'],
                        $row['usuario'],
                        $row['documento']
                    ];
                }
                break;
            case "equipos":
                $roomNumber = $this->results[0]['ambiente'];
                $this->title = "Reporte Equipos";
                $this->subtitle = "Reporte Equipos del Ambiente $roomNumber";

                $this->headers = ['Computador', 'Referencia', 'Marca', 'Estado', 'Ambiente'];
                foreach ($this->results as $row) {
                    $this->data[] = [
                        $row['idComputador'],
                        $row['ref'],
                        $row['marca'],
                        $row['estado'],
                        $row['ambiente']
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