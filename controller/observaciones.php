<?php
require_once ('../model/sessions.php');
require_once ('./funciones.php');
require_once ('../model/observaciones.php');
require_once ('../model/registroObservaciones.php');
// require_once ('./ExportController.php');

$functions = new Funciones();
$registerObservations = new RegistroObservaciones();
$observations = new Observaciones();
// $exportController = new ExportController();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    if (isset($_GET['id'])) {
      $idObservation = $_GET['id'];
      $observations->getObservationsUser($idUser);
    } elseif (isset($_GET['getCount'])) {
      $getCount = $_GET['getCount'];

      if ($getCount == "allObs") {
        $observations->getCountObservations();
        exit;
      } else {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon Parece que ocurrió un error."]);
        exit;
      }

    } else {
      $user = $_SESSION['userId'];
      if ($user == 1 || $user == 2) {
        $observations->getObservations();
      } else {
        $observations->getObservationsUser($user);
      }
    }
    break;
  case 'POST':
    if (isset($data['typeSubject']) && isset($data['descriptionObservation'])) {
      $type = $data['typeSubject'];
      $desc = $data['descriptionObservation'];
      $user = $_SESSION['userId'];

      if ($functions->checkNotEmpty([$type, $desc, $user])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
      } else {
        $observationInserted = $observations->createObservation($desc, $user, $type);
        $registerObservations->addObservationHistory($observationInserted);
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