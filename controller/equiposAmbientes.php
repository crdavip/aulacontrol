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
    if (isset($data['numRoom']) && isset($data['centerRoom'])) {
      $numRoom = $data['numRoom'];
      $centerRoom = $data['centerRoom'];
      if (checkNotEmpty([$numRoom, $centerRoom])) {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
        exit();
      } elseif (checkExistence($pdo, 'ambiente', ['numero', 'idCentro'], [$numRoom, $centerRoom])) {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que este ambiente ya existe."]);
        exit();
      } else {
        createRoom($pdo, $numRoom, $centerRoom);
        exit();
      }
    }
    break;
  case 'PUT':
    if (isset($data['roomIdEdit'])) {
      $roomIdEdit = $data['roomIdEdit'];
      $numRoomEdit = $data['numRoomEdit'];
      $centerRoomEdit = $data['centerRoomEdit'];
      $numRoomBD = getValue($pdo, 'ambiente', 'numero', 'idAmbiente', $roomIdEdit);
      $centerRoomBD = getValue($pdo, 'ambiente', 'idCentro', 'idAmbiente', $roomIdEdit);
      $roomEdit = $numRoomEdit !== $numRoomBD || $centerRoomEdit !== $centerRoomBD;
      if (checkNotEmpty([$numRoomEdit, $centerRoomEdit])) {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
        exit();
      } elseif ((checkExistence($pdo, 'ambiente', ['numero', 'idCentro'], [$numRoomEdit, $centerRoomEdit])) && $roomEdit) {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que este ambiente ya existe."]);
        exit();
      } else {
        updateRoom($pdo, $numRoomEdit, $centerRoomEdit, $roomIdEdit);
        exit();
      }
    }
    break;
  case 'DELETE':
    if (isset($data['roomIdDelete'])) {
      $roomIdDelete = $data['roomIdDelete'];
      deleteRoom($pdo, $roomIdDelete);
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
