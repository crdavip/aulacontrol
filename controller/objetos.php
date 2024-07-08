<?php
require_once ('../model/sessions.php');
require_once ('../model/objetos.php');
require_once ('./funciones.php');

$functions = new Funciones();
$objects = new Objetos();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    if (isset($_GET['columns'])) {
      $columns = json_decode($_GET['columns']);
      echo json_encode($functions->getColumns('objetos', $columns), JSON_UNESCAPED_UNICODE);
    } else {
      $objects->getObjects();
    }
    break;
  case 'POST':
    if (isset($data['descriptionObject']) && isset($data['colorObject']) && isset($data['userObject'])) {
      $userObject = $data['userObject'];
      $descriptionObject = $data['descriptionObject'];
      $colorObject = $data['colorObject'];
      $idCenterObject = $data['idObjectCenter'];
      $colorObject = strtolower($colorObject);

      if ($functions->checkNotEmpty([$descriptionObject, $colorObject, $userObject, $idCenterObject])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
        exit();
      } else {

        $userExistence = $functions->getValueValidation('usuario', 'documento', $userObject);
        if ($userExistence) {
          $idUserFounded = $userExistence["idUsuario"];
          $objects->createObject($descriptionObject, $colorObject, $idUserFounded, $idCenterObject);
          exit();
        } else {
          $icon = $functions->getIcon('Err');
          echo json_encode(['success' => false, 'message' => "$icon El usuario no existe."]);
          exit();
        }
      }
    }
    break;
  case 'PUT':
    if (isset($data['objectIdEdit'])) {
      $objectIdEdit = $data['objectIdEdit'];
      $userObjectEdit = $data['userObjectEdit'];
      $objectDescriptionEdit = $data['objectDescriptionEdit'];
      $objectDescriptionEdit = strtolower($objectDescriptionEdit);
      $objectColorEdit = $data['objectColorEdit'];
      $objectDescriptionBD = $functions->getValue('objetos', 'descripcion', 'idObjeto', $objectIdEdit);
      $userObjectBD = $functions->getValueValidation('usuario', 'documento', $userObjectEdit);
      if ($userObjectBD) {
        $userIdValidated = $userObjectBD["idUsuario"];
        $userObjectBD = $userObjectBD["documento"];
      } else {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon El usuario no existe, se debe registrar previamente."]);
        exit();
      }
      $objectColorBD = $functions->getValue('objetos', 'color', 'idObjeto', $objectIdEdit);
      $objectEdit = $objectDescriptionEdit !== $objectDescriptionBD || $objectColorEdit !== $objectColorEdit || $userObjectEdit !== $userObjectBD;
      if ($functions->checkNotEmpty([$objectDescriptionEdit, $objectColorEdit, $userObjectEdit])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
        exit();
      } else {
        $objects->updateObject($objectDescriptionEdit, $objectColorEdit, $userIdValidated, $objectIdEdit);
        exit();
      }
    }
    break;
  case 'DELETE':
    if (isset($data['objectIdDelete'])) {
      $objectIdDelete = $data['objectIdDelete'];
      $objects->deleteObject($objectIdDelete);
      exit();
    }
    break;
}
