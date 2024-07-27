<?php
require_once ('../model/sessions.php');
require_once ('./funciones.php');
require_once ('../model/registroObjetos.php');
require_once ('./ExportController.php');

$functions = new Funciones();
$registerObjects = new RegistroObjetos();
$exportController = new ExportController();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    if (isset($_GET['startDateExcel']) && isset($_GET['endDateExcel']) && isset($_GET['selectedCenterExcel'])) {
      $idCenter = $_GET['selectedCenterExcel'];
      $format = "excel";
      $startDate = $_GET['startDateExcel'];
      $endDate = $_GET['endDateExcel'];

      if ($functions->checkNotEmpty([$idCenter, $startDate, $endDate])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
      } else {
        $exportController->export($startDate, $endDate, $idCenter, $format, "registroObjetos");
      }

    } elseif (isset($_GET['startDatePdf']) && isset($_GET['endDatePdf']) && isset($_GET['selectedCenterPdf'])) {
      $idCenter = $_GET['selectedCenterPdf'];
      $format = "pdf";
      $startDate = $_GET['startDatePdf'];
      $endDate = $_GET['endDatePdf'];

      if ($functions->checkNotEmpty([$idCenter, $startDate, $endDate])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
      } else {
        $exportController->export($startDate, $endDate, $idCenter, $format, "registroObjetos");
      }
    } elseif (isset($_GET['idObjeto'])) {
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
    } elseif (isset($data['objectIdEntranceMark']) && isset($data['objectIdEntranceCenter'])) {
      $idObject = $data['objectIdEntranceMark'];
      $idCenter = $data['objectIdEntranceCenter'];
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

