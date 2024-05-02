<?php
require_once('../model/db.php');
require_once('../model/sessions.php');
require_once('./funciones.php');

$connPDO = new ConnPDO;
$pdo = $connPDO->getConn();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['columns'])) {
            $columns = json_decode($_GET['columns']);
            echo json_encode(getColumns($pdo, 'ambiente', $columns), JSON_UNESCAPED_UNICODE);
        } else {
            getRooms($pdo);
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

function getRooms($pdo)
{
    $sql = "SELECT a.idAmbiente, a.numero, a.estado, a.afluencia, c.idCentro, c.siglas AS centro
            FROM ambiente AS a
            INNER JOIN centro as c ON c.idCentro = a.idCentro
            ORDER BY a.numero ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $classRoom = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($classRoom);
}

function createRoom($pdo, $num, $center)
{
    $sql = "INSERT INTO ambiente (numero, idCentro) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$num, $center])) {
        $icon = getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡Ambiente Creado Exitosamente!"]);
    } else {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon Error al crear el ambiente"]);
    }
}

function updateRoom($pdo, $num, $center, $id)
{
    $sqlCheck = "SELECT * FROM registro_ambiente WHERE idAmbiente = ? AND fin IS NULL";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$id]);
    $count = $stmtCheck->rowCount();
    if ($count === 0) {
        $sql = "UPDATE ambiente SET numero = ?, centro = ? WHERE idAmbiente = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$num, $center, $id])) {
            $icon = getIcon('OK');
            echo json_encode(['success' => true, 'message' => "$icon ¡Ambiente Actualizado Exitosamente!"]);
        } else {
            $icon = getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon Error al actualizar el ambiente"]);
        }
    } else {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon El ambiente tiene una vinculación activa"]);
    }
}

function deleteRoom($pdo, $id)
{
    $sqlCheck = "SELECT * FROM registro_ambiente WHERE idAmbiente = ? AND fin IS NULL";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$id]);
    $count = $stmtCheck->rowCount();
    if ($count === 0) {
        $sql = "DELETE FROM ambiente WHERE idAmbiente = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id])) {
            $icon = getIcon('OK');
            echo json_encode(['success' => true, 'message' => "$icon ¡Ambiente Eliminado Exitosamente!"]);
        } else {
            $icon = getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon Error al eliminar el ambiente"]);
        }
    } else {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon El ambiente tiene una vinculación activa"]);
    }
}

$connPDO->closeConn();
