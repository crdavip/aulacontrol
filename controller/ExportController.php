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
require_once '../model/registroObservaciones.php';

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

        if ($report !== "registroAsistencia" || $report !== "registroObservaciones") {
            $startDatetime = "$startDate 00:00:00";
            $endDatetime = "$endDate 23:59:59";
        }

        if (!$report) {
            header('Location: ../index.php');
            exit;
        } else {
            $classInstance = $this->classSelector($report);
            print ($this->results);
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

    public function exportRegAsist($idInstructor, $idSheet, $report, $format)
    {
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

    public function exportRegObservations($startDate, $endDate, $stateObs, $typeObs, $exportBy, $format, $report)
    {
        $classInstance = new RegistroObservaciones();
        switch ($exportBy) {
            case 'dateStateType':
                $this->results = $classInstance->getGroupOfHistoryByDateTypeState($startDate, $endDate, $stateObs, $typeObs);
                break;
            case 'type':
                $this->results = $classInstance->getGroupOfHistoryByType($startDate, $endDate, $typeObs);
                break;
            case 'state':
                $this->results = $classInstance->getGroupOfHistoryByState($startDate, $endDate, $stateObs);
                break;
            default:
                $this->results = $classInstance->getGroupOfHistoryOnlyByDate($startDate, $endDate);
                break;
        }
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
                    $keys = $row['llaves'] ? 'SI' : 'NO';
                    $tv = $row['controlTv'] ? 'SI' : 'NO';
                    $air = $row['controlAire'] ? 'SI' : 'NO';
                    $this->data[] = [
                        $row['idRegistro'],
                        $row['inicio'],
                        $row['fin'],
                        $keys,
                        $tv,
                        $air,
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
            case "registroObservaciones":
                $centerNumber = $this->results[0]['centro'];
                $this->title = "Reporte de Observaciones";
                $this->subtitle = "Reporte Observaciones del $centerNumber";

                $this->headers = ['Registro', 'Observacion', 'Descripcion', 'Publicado', 'Estado', 'Tipo de Asunto', 'Usuario', 'Documento', 'Fecha Revision', 'Revisado Por', 'Documento quien Reviso'];
                foreach ($this->results as $row) {
                    $state = "";
                    $checker = "";
                    $docChecker = "";
                    $dateChecking = "";
                    if ($row['estado'] == 0) {
                        $state = "PENDIENTE";
                        $checker = "PENDIENTE";
                        $docChecker = "PENDIENTE";
                        $dateChecking = "PENDIENTE";
                    } else {
                        $state = "REVISADO";
                        $checker = $row['nombreRevisor'];
                        $docChecker = $row['docRevisor'];
                        $dateChecking = $row['fechaRevision'];
                    }
                    $this->data[] = [
                        $row['idRegistro'],
                        $row['idObservacion'],
                        $row['descripcion'],
                        $row['fechaPublicacion'],
                        $state,
                        $row['tipoAsunto'],
                        $row['usuario'],
                        $row['documento'],
                        $dateChecking,
                        $checker,
                        $docChecker,
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

            if (empty($this->data)) {
                header('Location: ../not-found.php');
                exit;
            }

            $exporter = new ExcelExporter();
            $exporter->setHeaders($this->headers);
            $exporter->addData($this->data);
            $exporter->output('reporte.xlsx');
        }
    }
}