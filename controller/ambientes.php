<?php
require_once('../model/sessions.php');
require_once('./funciones.php');
require_once('../model/ambientes.php');

$rooms = new Ambientes();
$functions = new Funciones();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['startDateExcel']) && isset($_GET['endDateExcel']) && isset($_GET['selectedRoom'])) {
            $idRoom = $_GET['selectedRoom'];
            $format = "excel";
            $startDate = $_GET['startDateExcel'];
            $endDate = $_GET['endDateExcel'];

            if ($functions->checkNotEmpty([$idRoom, $startDate, $endDate])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
            } else {
                $exportController->export($startDate, $endDate, $idRoom, $format, "registroAmbientes");
            }

        } elseif (isset($_GET['startDatePdf']) && isset($_GET['endDatePdf']) && isset($_GET['selectedRoom'])) {
            $idRoom = $_GET['selectedRoom'];
            $format = "pdf";
            $startDate = $_GET['startDatePdf'];
            $endDate = $_GET['endDatePdf'];

            if ($functions->checkNotEmpty([$idRoom, $startDate, $endDate])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
            } else {
                $exportController->export($startDate, $endDate, $idRoom, $format, "registroAmbientes");
            }
        }
        break;
    case 'POST':
        if (isset($data['numRoom']) && isset($data['centerRoom'])) {
            $numRoom = $data['numRoom'];
            $centerRoom = $data['centerRoom'];
            if ($functions->checkNotEmpty([$numRoom, $centerRoom])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit();
            } elseif ($functions->checkExistence('ambiente', ['numero', 'idCentro'], [$numRoom, $centerRoom])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que este ambiente ya existe."]);
                exit();
            } else {
                $rooms->createRoom($numRoom, $centerRoom);
                exit();
            }
        }
        break;
    case 'PUT':
        if (isset($data['roomIdEdit'])) {
            $roomIdEdit = $data['roomIdEdit'];
            $numRoomEdit = $data['numRoomEdit'];
            $centerRoomEdit = $data['centerRoomEdit'];
            $numRoomBD = $functions->getValue('ambiente', 'numero', 'idAmbiente', $roomIdEdit);
            $centerRoomBD = $functions->getValue('ambiente', 'idCentro', 'idAmbiente', $roomIdEdit);
            $roomEdit = $numRoomEdit !== $numRoomBD || $centerRoomEdit !== $centerRoomBD;
            if ($functions->checkNotEmpty([$numRoomEdit, $centerRoomEdit])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit();
            } elseif (($functions->checkExistence('ambiente', ['numero', 'idCentro'], [$numRoomEdit, $centerRoomEdit])) && $roomEdit) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que este ambiente ya existe."]);
                exit();
            } else {
                $rooms->updateRoom($numRoomEdit, $centerRoomEdit, $roomIdEdit);
                exit();
            }
        }
        break;
    case 'DELETE':
        if (isset($data['roomIdDelete'])) {
            $roomIdDelete = $data['roomIdDelete'];
            $rooms->deleteRoom($roomIdDelete);
            exit();
        }
        break;
}

