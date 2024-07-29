<?php
require_once ('../model/sessions.php');
require_once ('./funciones.php');
require_once ('../model/registroObservaciones.php');
// require_once ('./ExportController.php');

$functions = new Funciones();
$registerObservations = new RegistroObservaciones();
// $exportController = new ExportController();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    if (isset($_GET['id'])) {
      $idObservation = $_GET['id'];
      $observations->getObservationsUser($idUser);
    } else {
      $registerObservations->getAllObservationsHistory();
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
    if (isset($data['observationId'])) {
      $observationId = $data['observationId'];
      $user = $_SESSION['userId'];
      if ($functions->checkNotEmpty([$observationId, $user])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
      } else {
        $registerObservations->updateObservationHistory($user, $observationId);
      }
    } else {
      $icon = $functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon No es posible vincular."]);
    }
    break;
  case 'DELETE':
    break;
}