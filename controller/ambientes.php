<?php
require_once ('../model/sessions.php');
require_once ('./funciones.php');
require_once ('../model/ambientes.php');

$rooms = new Ambientes();
$functions = new Funciones();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['columns'])) {
            $columns = json_decode($_GET['columns']);
            echo json_encode($functions->getColumns('ambiente', $columns), JSON_UNESCAPED_UNICODE);
        } elseif (isset($_GET['getCount'])) {
            $getCount = $_GET['getCount'];
      
            if ($getCount == "allRooms") {
              $rooms->getCountRooms();
              exit;
            } else {
              $icon = $functions->getIcon('Err');
              echo json_encode(['success' => false, 'message' => "$icon Parece que ocurrió un error."]);
              exit;
            }
    
          } else {
            $rooms->getRooms();
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

