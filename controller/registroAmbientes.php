<?php
require_once ('../model/sessions.php');
require_once ('./funciones.php');
require_once ('../model/registroAmbientes.php');

$functions = new Funciones();
$registerRooms = new RegistroAmbientes();


$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['idAmbiente'])) {
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

