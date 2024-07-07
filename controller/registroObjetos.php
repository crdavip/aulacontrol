<?php
require_once ('../model/sessions.php');
require_once ('./funciones.php');
require_once ('../model/registroObjetos.php');

$functions = new Funciones();
$registerObjects = new RegistroObjetos();


$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    if (isset($_GET['idObjeto'])) {
      $idObject = $_GET['idObjeto'];
      $registerObjects->getObjectHistory($idObject);
    } else {
      $registerObjects->getAllObjectsHistory();
    }
    break;
  case 'POST':
    if (isset($data['idObject']) && isset($data["idCenter"])) {
      $idObject = $data['idObject'];
      $idCenter = $data['idCenter'];

      if ($functions->checkNotEmpty([$idObject, $idCenter])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
      } else {
        $registerObjects->addObjectHistory($idObject, $idCenter);
      }
    } else {
      $icon = $functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon No es posible crear el registro."]);
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
        $registerObjects->updateDeviceHistory($idUserAssoc, $idDevice);
      }
    } else {
      $icon = $functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon No es posible vincular."]);
    }
    break;
  case 'DELETE':
    break;
}

