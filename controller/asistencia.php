<?php
require_once ('../model/sessions.php');
require_once ('../model/asistencia.php');
require_once ('../model/registroAsistencia.php');
require_once ('./funciones.php');

$functions = new Funciones();
$assistance = new Asistencia();
$regAssistance = new RegistroAsistencia();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':

    break;
  case 'POST':
    if (isset($data['items']) && isset($data['dateAssistance']) && isset($data['sheet']) && isset($data['envrmnt'])) {
      $items = $data['items'];
      $date = $data['dateAssistance'];
      $sheet = $data['sheet'];
      $user = $_SESSION['userId'];
      $envrmnt = $data['envrmnt'];
      $idAssistance = $assistance->saveAssistance($date, $sheet, $user, $envrmnt);
      if ($idAssistance) {
        $result = $regAssistance->saveRegAssistance($items, $idAssistance);
        if ($result['insertion'] === 'completa') {
          echo json_encode([
            'success' => true,
            'insertion' => $result['insertion']
          ]);
        } else {
          echo json_encode([
            'success' => false,
            'insertion' => $result['insertion'],
            'details' => $result['details']
          ]);
        }
      } else {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se pudo ingresar la asistencia."]);
        break;
      }
    }
    break;
  default:
    $icon = $functions->getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon Acción Denegada."]);
    break;
}