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
        if (isset($_GET['idAmbiente'])) {
            $idRoom = $_GET['idAmbiente'];
            getRoomHistory($pdo, $idRoom);
        } else {
            getAllRoomHistory($pdo);
        }
        break;
    case 'POST':
        if (isset($data['idUserAssoc'])) {
            $idUserAssoc = $data['idUserAssoc'];
            $idRoom = $data['idRoom'];
            $keys = $data['keys'];
            $controlTv = $data['controlTv'];
            $controlAir = $data['controlAir'];
            if (checkNotEmpty([$idUserAssoc, $idRoom])) {
                $icon = getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
            } else {
                addRoomHistory($pdo, $keys, $controlTv, $controlAir, $idUserAssoc, $idRoom);
            }
        } else {
            $icon = getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon No es posible vincular."]);
        }
        break;
    case 'PUT':
        if (isset($data['idUserAssoc'])) {
            $idUserAssoc = $data['idUserAssoc'];
            $idRoom = $data['idRoom'];
            if (checkNotEmpty([$idUserAssoc, $idRoom])) {
                $icon = getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
            } else {
                updateRoomHistory($pdo, $idUserAssoc, $idRoom);
            }
        } else {
            $icon = getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon No es posible vincular."]);
        }
        break;
    case 'DELETE':
        break;
}

function getAllRoomHistory($pdo)
{
    $sql = "SELECT ra.idRegistro, ud.nombre, ud.imagen, u.documento, a.numero, c.detalle AS centro, ra.inicio, ra.fin, ra.llaves, ra.controlTv, ra.controlAire
            FROM registro_ambiente AS ra
            INNER JOIN usuario_detalle AS ud ON ud.idUsuario = ra.idInstructor
            INNER JOIN usuario AS u ON u.idUsuario = ra.idInstructor
            INNER JOIN ambiente AS a ON a.idAmbiente = ra.idAmbiente
            INNER JOIN centro AS c ON c.idCentro = a.idCentro
            ORDER BY idRegistro DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
}

function getRoomHistory($pdo, $idAmbiente)
{
    $sql = "SELECT ra.*, ud.nombre AS instructor
            FROM registro_ambiente AS ra
            INNER JOIN usuario_detalle AS ud ON ud.idUsuario = ra.idInstructor
            WHERE idAmbiente = ? AND fin IS NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idAmbiente]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);
}

function addRoomHistory($pdo, $llaves, $controlTv, $controlAir, $idInstructor, $idAmbiente)
{
    $sqlCheck = "SELECT * FROM registro_ambiente
                WHERE fin IS NULL AND idInstructor = ?
                ORDER BY fin DESC LIMIT 1";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$idInstructor]);
    $count = $stmtCheck->rowCount();
    if ($count == 0) {
        $sql = "INSERT INTO registro_ambiente (inicio, llaves, controlTv, controlAire, idInstructor, idAmbiente) VALUES (current_timestamp(), ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$llaves, $controlTv, $controlAir, $idInstructor, $idAmbiente])) {
            $sqlUpdate = "UPDATE ambiente SET estado = 'Ocupada', afluencia = 1 WHERE idAmbiente = ?";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([$idAmbiente]);

            $icon = getIcon('OK');
            echo json_encode(['success' => true, 'message' => "$icon ¡Vinculación exitosa!"]);
        } else {
            $icon = getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que algo salió mal."]);
        }
    } else {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon El instructor ya tiene una vinculación activa."]);
    }
}

function updateRoomHistory($pdo, $idInstructor, $idAmbiente)
{
    $sqlGet = "SELECT * FROM registro_ambiente
                WHERE fin IS NULL AND idInstructor = ? AND idAmbiente = ?
                ORDER BY fin DESC LIMIT 1";
    $stmtGet = $pdo->prepare($sqlGet);
    $stmtGet->execute([$idInstructor, $idAmbiente]);
    $row = $stmtGet->fetch(PDO::FETCH_ASSOC);
    $count = $stmtGet->rowCount();
    if ($count > 0) {
        $idHistory = $row['idRegistro'];
        $sql = "UPDATE registro_ambiente SET fin = current_timestamp() WHERE idRegistro = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$idHistory])) {
            $sqlUpdate = "UPDATE ambiente SET estado = 'Disponible', afluencia = 0 WHERE idAmbiente = ?";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([$idAmbiente]);

            $icon = getIcon('OK');
            echo json_encode(['success' => true, 'message' => "$icon ¡Desvinculación exitosa!"]);
        } else {
            $icon = getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que algo salía mal."]);
        }
    } else {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon El instructor no esta vinculado a este ambiente."]);
    }
}

$connPDO->closeConn();
