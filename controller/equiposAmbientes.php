<?php
require_once ('../model/db.php');
require_once ('../model/sessions.php');
require_once ('./funciones.php');

$connPDO = new ConnPDO;
$pdo = $connPDO->getConn();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    if (isset($_GET['columns'])) {
      $columns = json_decode($_GET['columns']);
      echo json_encode(getColumns($pdo, 'computador', $columns), JSON_UNESCAPED_UNICODE);
    } else {
      // $id = json_decode($_GET['numeroAmbiente']);
      getDevices($pdo);
    }
    break;
  case 'POST':
    if (isset($data['refDevice']) && isset($data['brandDevice']) && isset($data['idRoom'])) {
      $refDevice = $data['refDevice'];
      $brandDevice = $data['brandDevice'];
      $brandDevice = strtoupper($brandDevice);
      $stateDevice = "Disponible";
      $idRoom = $data['idRoom'];

      if (checkNotEmpty([$refDevice, $brandDevice, $stateDevice, $idRoom])) {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
        exit();
      } elseif (checkExistence($pdo, 'computador', ['ref', 'marca', 'estado', 'idAmbiente'], [$refDevice, $brandDevice, $stateDevice, $idRoom])) {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que este ambiente ya existe."]);
        exit();
      } else {
        createDevice($pdo, $refDevice, $brandDevice, $stateDevice, $idRoom);
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
      $refDeviceBD = getValue($pdo, 'computador', 'ref', 'idComputador', $deviceIdEdit);
      $brandDeviceBD = getValue($pdo, 'computador', 'marca', 'idComputador', $deviceIdEdit);
      $stateDeviceBD = getValue($pdo, 'computador', 'estado', 'idComputador', $deviceIdEdit);
      $roomDeviceBD = getValue($pdo, 'computador', 'idAmbiente', 'idComputador', $deviceIdEdit);
      $deviceEdit = $deviceRefEdit !== $refDeviceBD || $deviceBrandEdit !== $brandDeviceBD || $deviceStateEdit !== $stateDeviceBD || $deviceAmbEdit !== $roomDeviceBD;
      if (checkNotEmpty([$deviceRefEdit, $deviceBrandEdit, $deviceStateEdit, $deviceAmbEdit])) {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
        exit();
      } elseif ((checkExistence($pdo, 'computador', ['ref', 'marca'], [$deviceRefEdit, $deviceBrandEdit])) && $deviceEdit) {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que este equipo ya existe."]);
        exit();
      } else {
        updateDevice($pdo, $deviceRefEdit, $deviceBrandEdit, $deviceStateEdit, $deviceAmbEdit, $deviceIdEdit);
        exit();
      }
    }
    break;
  case 'DELETE':
    if (isset($data['deviceIdDelete'])) {
      $deviceIdDelete = $data['deviceIdDelete'];
      deleteDevice($pdo, $deviceIdDelete);
      exit();
    }
    break;
}

function getDevices($pdo)
{
  $sql = "SELECT c.idComputador, c.ref, c.marca, c.estado, a.numero AS ambiente FROM computador AS c INNER JOIN ambiente AS a ON a.idAmbiente = c.idAmbiente ORDER BY c.idAmbiente";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($devices);
}

function createDevice($pdo, $reference, $brand, $stateDevice, $room)
{
  $sql = "INSERT INTO computador (ref, marca, estado, idAmbiente) VALUES (?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  if ($stmt->execute([$reference, $brand, $stateDevice, $room])) {
    $icon = getIcon('OK');
    echo json_encode(['success' => true, 'message' => "$icon ¡Equipo Creado Exitosamente!"]);
  } else {
    $icon = getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon Error al crear el equipo"]);
  }
}

function updateDevice($pdo, $reference, $brand, $stateDevice, $room, $id)
{
  $sql = "UPDATE computador SET ref = ?, marca = ?, estado = ?, idAmbiente = ? WHERE idComputador = ?";
  $stmt = $pdo->prepare($sql);
  if ($stmt->execute([$reference, $brand, $stateDevice, $room, $id])) {
    $icon = getIcon('OK');
    echo json_encode(['success' => true, 'message' => "$icon ¡Equipo Actualizado Exitosamente!"]);
  } else {
    $icon = getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon Error al actualizar el equipo"]);
  }
}

function deleteDevice($pdo, $id)
{
  $sql = "DELETE FROM computador WHERE idComputador = ?";
  $stmt = $pdo->prepare($sql);
  if ($stmt->execute([$id])) {
    $icon = getIcon('OK');
    echo json_encode(['success' => true, 'message' => "$icon ¡Equipo Eliminado Exitosamente!"]);
  } else {
    $icon = getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon Error al eliminar el equipo"]);
  }
}

$connPDO->closeConn();