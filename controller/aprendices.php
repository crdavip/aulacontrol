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
  case 'POST':
    if ((isset($data['ids']) && is_array($data['ids'])) && isset($data['idSheet'])) {
      $ids = $data['ids'];
      $idSheet = $data['idSheet'];
      $result = $trainees->saveTrainee($ids, $idSheet);

      if($result) {
        echo json_encode(['success' => true, 'message' => 'Aprendices guardados correctamente.']);

      } else {
        echo json_encode(['success' => false, 'message' => 'No se recibieron IDs válidos.']);

      }
    } else {
      echo json_encode(['success' => false, 'message' => 'No se recibieron IDs válidos.']);
    }
    break;
}