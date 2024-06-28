<?php
require_once ('../model/sessions.php');
require_once ('../model/equipos.php');
require_once ('./funciones.php');


$functions = new Funciones();
$devices = new Equipos();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    if (isset($_GET['columns'])) {
      $columns = json_decode($_GET['columns']);
      echo json_encode($functions->getColumns('computador', $columns), JSON_UNESCAPED_UNICODE);
    } else {
      $devices->getDevices();
    }
    break;
  case 'POST':
    if (isset($data['refDevice']) && isset($data['brandDevice']) && isset($data['idRoom'])) {
      $refDevice = $data['refDevice'];
      $brandDevice = $data['brandDevice'];
      $brandDevice = strtoupper($brandDevice);
      $stateDevice = "Disponible";
      $idRoom = $data['idRoom'];

      if ($functions->checkNotEmpty([$refDevice, $brandDevice, $stateDevice, $idRoom])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
        exit();
      } elseif ($functions->checkExistence('computador', ['ref', 'marca', 'estado', 'idAmbiente'], [$refDevice, $brandDevice, $stateDevice, $idRoom])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que este ambiente ya existe."]);
        exit();
      } else {
        $devices->createDevice($refDevice, $brandDevice, $stateDevice, $idRoom);
        exit();
      }
    }
    break;
  case 'PUT':
    if (isset($data['deviceIdEdit'])) {
      $deviceIdEdit = $data['deviceIdEdit'];
      $deviceRefEdit = $data['deviceRefEdit'];
      $deviceBrandEdit = $data['deviceBranchEdit'];
      $deviceBrandEdit = strtoupper($deviceBrandEdit);
      $deviceStateEdit = $data['deviceStateEdit'];
      $deviceAmbEdit = $data['deviceAmbEdit'];
      $refDeviceBD = $functions->getValue('computador', 'ref', 'idComputador', $deviceIdEdit);
      $brandDeviceBD = $functions->getValue('computador', 'marca', 'idComputador', $deviceIdEdit);
      $stateDeviceBD = $functions->getValue('computador', 'estado', 'idComputador', $deviceIdEdit);
      $roomDeviceBD = $functions->getValue('computador', 'idAmbiente', 'idComputador', $deviceIdEdit);
      $deviceEdit = $deviceRefEdit !== $refDeviceBD || $deviceBrandEdit !== $brandDeviceBD || $deviceStateEdit !== $stateDeviceBD || $deviceAmbEdit !== $roomDeviceBD;
      if ($functions->checkNotEmpty([$deviceRefEdit, $deviceBrandEdit, $deviceStateEdit, $deviceAmbEdit])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
        exit();
      } elseif (($functions->checkExistence('computador', ['ref', 'marca', 'idAmbiente'], [$deviceRefEdit, $deviceBrandEdit, $deviceAmbEdit])) && $deviceEdit) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que este equipo ya existe."]);
        exit();
      } else {
        $devices->updateDevice($deviceRefEdit, $deviceBrandEdit, $deviceStateEdit, $deviceAmbEdit, $deviceIdEdit);
        exit();
      }
    }
    break;
  case 'DELETE':
    if (isset($data['deviceIdDelete'])) {
      $deviceIdDelete = $data['deviceIdDelete'];
      $devices->deleteDevice($deviceIdDelete);
      exit();
    }
    break;
}
