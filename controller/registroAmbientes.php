<?php
require_once ('../model/sessions.php');
require_once ('./funciones.php');
require_once ('../model/registroAmbientes.php');
require_once ('./ExportController.php');

$functions = new Funciones();
$registerRooms = new RegistroAmbientes();
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
                $exportController->export($startDate, $endDate, $idRoom, $format, "registroAmbientes");
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
                $exportController->export($startDate, $endDate, $idRoom, $format, "registroAmbientes");
            }
        } elseif (isset($_GET['idAmbiente'])) {
            $idRoom = $_GET['idAmbiente'];
            $registerRooms->getRoomHistory($idRoom);
        } else {
            $registerRooms->getAllRoomHistory();
        }
        break;
    case 'POST':
        if (isset($data['idUserAssoc'])) {
            $idUserAssoc = $data['idUserAssoc'];
            $idRoom = $data['idRoom'];
            $keys = $data['keys'];
            $controlTv = $data['controlTv'];
            $controlAir = $data['controlAir'];
            if ($functions->checkNotEmpty([$idUserAssoc, $idRoom])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
            } else {
                $registerRooms->addRoomHistory($keys, $controlTv, $controlAir, $idUserAssoc, $idRoom);
            }
        } else {
            $icon = $functions->getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon No es posible vincular."]);
        }
        break;
    case 'PUT':
        if (isset($data['idUserAssoc'])) {
            $idUserAssoc = $data['idUserAssoc'];
            $idRoom = $data['idRoom'];
            if ($functions->checkNotEmpty([$idUserAssoc, $idRoom])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
            } else {
                $registerRooms->updateRoomHistory($idUserAssoc, $idRoom);
            }
        } else {
            $icon = $functions->getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon No es posible vincular."]);
        }
        break;
    case 'DELETE':
        break;
}

