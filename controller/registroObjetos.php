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
        $registerObjects->updateObjectHistory($idUserAssoc, $idDevice);
      }
    } elseif (isset($data['objectIdExitMark']) && isset($data['objectIdUser'])) {
      $objectIdExitMark = $data['objectIdExitMark'];
      $objectIdUser = $data['objectIdUser'];
      if ($functions->checkNotEmpty([$objectIdExitMark, $objectIdUser])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
      } else {
        // Existe?
        if (($functions->getValue('objetos', 'idObjeto', 'idObjeto', $objectIdExitMark) == $objectIdExitMark) && ($functions->getValue('usuario', 'idUsuario', 'idUsuario', $objectIdUser) == $objectIdUser)) {
          $registerObjects->updateObjectHistory($objectIdUser, $objectIdExitMark);
        }
        // Tiene el campo inicio lleno?
        // Tiene el campo fin = null?
      }

    } else {
      $icon = $functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon No se pudo actualizar la información."]);
    }
    break;
  case 'DELETE':
    break;
}

