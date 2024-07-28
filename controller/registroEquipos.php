<?php
require_once ('../model/sessions.php');
require_once ('./funciones.php');
require_once ('../model/registroEquipos.php');
require_once ('./ExportController.php');

$functions = new Funciones();
$registerDevices = new RegistroEquipos();
$exportController = new ExportController();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['startDateExcel']) && isset($_GET['endDateExcel']) && isset($_GET['selectedRoomExcel'])) {
            $idRoom = $_GET['selectedRoomExcel'];
            $format = "excel";
            $startDate = $_GET['startDateExcel'];
            $endDate = $_GET['endDateExcel'];

            if ($functions->checkNotEmpty([$idRoom, $startDate, $endDate])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
            } else {
                $exportController->export($startDate, $endDate, $idRoom, $format, "registroEquipos");
            }

        } elseif (isset($_GET['startDatePdf']) && isset($_GET['endDatePdf']) && isset($_GET['selectedRoomPdf'])) {
            $idRoom = $_GET['selectedRoomPdf'];
            $format = "pdf";
            $startDate = $_GET['startDatePdf'];
            $endDate = $_GET['endDatePdf'];

            if ($functions->checkNotEmpty([$idRoom, $startDate, $endDate])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
            } else {
                $exportController->export($startDate, $endDate, $idRoom, $format, "registroEquipos");
            }
        } elseif (isset($_GET['idComputador'])) {
            $idDevice = $_GET['idComputador'];
            $registerDevices->getDeviceHistory($idDevice);
        } elseif (isset($_GET['helpDevice'])) {
            $registerDevices->getAllHelpDevicesHistory();
        } else {
            $registerDevices->getAllDevicesHistory();
        }
        break;
    case 'POST':
        if (isset($data['idUserAssoc'])) {
            $idUserAssoc = $data['idUserAssoc'];
            $idDevice = $data['idDevice'];
            // ? MAS CAMPOS ? ##########################

            if ($functions->checkNotEmpty([$idUserAssoc, $idDevice])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
            } else {
                $registerDevices->addDeviceHistory($idUserAssoc, $idDevice);
            }
        } else {
            $icon = $functions->getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon No es posible vincular."]);
        }
        break;
    case 'PUT':
        if (isset($data['idUserAssoc'])) {
            $idUserAssoc = $data['idUserAssoc'];
            $idDevice = $data['idDevice'];
            if ($functions->checkNotEmpty([$idUserAssoc, $idDevice])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
            } else {
                $registerDevices->updateDeviceHistory($idUserAssoc, $idDevice);
            }
        } else {
            $icon = $functions->getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon No es posible vincular."]);
        }
        break;
    case 'DELETE':
        break;
}

