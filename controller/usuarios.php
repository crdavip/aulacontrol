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
        if (isset($_GET['docUserAssoc'])) {
            $docUserAssoc = $_GET['docUserAssoc'];
            getDocUserAssoc($pdo, $docUserAssoc);
            exit();
        }
        break;
    case 'POST':
        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
}

function getDocUserAssoc($pdo, $doc)
{
    $sqlDoc = 'SELECT * FROM usuario WHERE documento = ? AND idCargo = 2';
    $stmtDoc = $pdo->prepare($sqlDoc);
    $stmtDoc->execute([$doc]);
    $count = $stmtDoc->rowCount();
    $row = $stmtDoc->fetch(PDO::FETCH_ASSOC);
    if ($count > 0) {
        $sql = 'SELECT u.idUsuario, u.documento, ud.nombre, ud.imagen, c.detalle AS cargo
                    FROM usuario AS u
                    INNER JOIN usuario_detalle AS ud ON u.idUsuario = ud.idUsuario
                    INNER JOIN cargo AS c ON c.idCargo = u.idCargo
                    WHERE documento = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$doc]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'user' => $row]);
    } else {
        $icon = getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡El usuario no es valido!"]);
    }
}

$connPDO->closeConn();
