<?php
require_once('../model/db.php');
require_once('../model/sessions.php');

if (!isset($_SERVER['HTTP_REFERER'])) {
    header("Location: ../");
    exit();
}

$connPDO = new ConnPDO();
$pdo = $connPDO->getConn();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($_GET['all'])) {
            $sql = "SELECT a.*, u.NOMBRE
                    FROM alertas AS a
                    INNER JOIN usuario AS u ON a.EMISOR = u.USUARIO_ID
                    WHERE a.RECEPTOR = ? AND a.ESTADO = 1
                    ORDER BY a.ALERTA_ID DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);
            $alertRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sqlCount = "SELECT * FROM alertas WHERE GRUPO=?";
            $stmtCount = $pdo->prepare($sqlCount);
            $stmtCount->execute([$userRole]);
            $alertCount = $stmtCount->rowCount();

            echo json_encode(['alertCount' => $alertCount, 'alertRow' => $alertRow]);
        } elseif (isset($_GET['tly'])) {
            $sql = "SELECT * FROM alertas WHERE RECEPTOR=? AND ESTADO=0";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);
            $alertCount = $stmt->rowCount();

            echo json_encode(['alertCount' => $alertCount]);
        } else {
            $sql = "SELECT a.*, u.NOMBRE
                    FROM alertas AS a
                    INNER JOIN usuario AS u ON a.EMISOR = u.USUARIO_ID
                    WHERE a.RECEPTOR = ? AND a.ESTADO = 0
                    ORDER BY a.ALERTA_ID DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);
            $alertCount = $stmt->rowCount();
            $alertRow = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['alertCount' => $alertCount, 'alertRow' => $alertRow]);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['alertId'])) {
            $IDALERT = $data['alertId'];
            $sql = "UPDATE alertas SET ESTADO=1 WHERE RECEPTOR=? AND ALERTA_ID=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $IDALERT]);
    
            echo json_encode(['alertKey' => true]);
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $sql = "DELETE FROM alertas WHERE RECEPTOR=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);

        echo json_encode(['alertKey' => true]);
        break;
}
$connPDO->closeConn();
?>