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
    if (isset($data['descriptionObject']) && isset($data['colorObject'])) {
      $descriptionObject = $data['descriptionObject'];
      $idUser = $data['idUser'];
      $colorObject = $data['colorObject'];
      $colorObject = strtolower($colorObject);

      if ($functions->checkNotEmpty([$descriptionObject, $colorObject, $idUser])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
        exit();
      } elseif ($functions->getValue('usuario', 'documento', 'idUsuario', $idUser)) {

      } else {
        $objects->createObject($descriptionObject, $colorObject, $idUser);
        exit();
      }
    }
    break;
  case 'PUT':
    if (isset($data['objectIdEdit'])) {
      $objectIdEdit = $data['objectIdEdit'];
      $objectStateEdit = $data['objectStateEdit'];
      $objectIdUserEdit = $data['objectIdUser'];
      $objectDescriptionEdit = $data['objectDescriptionEdit'];
      $objectDescriptionEdit = strtolower($objectDescriptionEdit);
      $objectColorEdit = $data['objectColorEdit'];
      $objectDescriptionBD = $functions->getValue('objetos', 'descripcion', 'idObjeto', $objectIdEdit);
      $objectColorBD = $functions->getValue('objetos', 'color', 'idObjeto', $objectIdEdit);
      $objectEdit = $objectDescriptionEdit !== $objectDescriptionBD || $objectColorEdit !== $objectColorEdit;
      if ($functions->checkNotEmpty([$objectDescriptionEdit, $objectColorEdit])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
        exit();
      } else {
        $objects->updateObject($objectDescriptionEdit, $objectColorEdit, $objectStateEdit, $objectIdUserEdit, $objectIdEdit);
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
