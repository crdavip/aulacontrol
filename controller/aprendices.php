<?php
require_once ('../model/sessions.php');
require_once ('../model/aprendices.php');
require_once ('./funciones.php');
require_once ('./ExportController.php');

$functions = new Funciones();
$trainees = new Aprendices();
$exportController = new ExportController();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    if (isset($_GET['paramSheet'])) {
      $idSheet = $_GET['paramSheet'];
      $trainees->getTrainees($idSheet);
    } elseif (isset($_GET['queryList']) && isset($_GET['searchDoc'])) {
      $sheet = $_GET['queryList'];
      $doc = $_GET['searchDoc'];
      $trainees->getTraineesForSearching($doc, $sheet);
    } else {
      $icon = $functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Acción no permitida."]);
    }
    break;
  case 'POST':
    if ((isset($data['ids']) && is_array($data['ids'])) && isset($data['idSheet'])) {
      $ids = $data['ids'];
      $idSheet = $data['idSheet'];
      $result = $trainees->saveTrainee($ids, $idSheet);

      if ($result) {
        echo json_encode($result);

      } else {
        echo json_encode(['insertion' => 'incompleta', 'details' => ['error' => 'No se recibieron IDs válidos.']]);

      }
    } else {
      echo json_encode(['insertion' => 'incompleta', 'details' => ['error' => 'No se recibieron IDs válidos.']]);
    }
    break;
  case 'DELETE':
    if (isset($data['dataSheetIdRemoveSheet']) && isset($data['dataSheetIdRemoveTrainee'])) {
      $idTrainee = $data['dataSheetIdRemoveTrainee'];
      $idSheet = $data['dataSheetIdRemoveSheet'];
      $trainees->removeTrainee($idTrainee, $idSheet);
      $icon = $functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon Usuario removido exitosamente."]);
    } else {
      $icon = $functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Parametros incorrectos."]);
    }
    break;
  default:
    $icon = $functions->getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
    break;
}