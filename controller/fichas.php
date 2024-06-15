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
        getDataSheets($pdo);
        break;
    case 'POST':
        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
}

function getDataSheets($pdo)
{
    $sql = "SELECT f.idFicha, f.ficha, f.detalle AS curso, f.aprendices, c.siglas AS centro
            FROM ficha AS f
            INNER JOIN centro AS c ON c.idCentro = f.idCentro ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($row, JSON_UNESCAPED_UNICODE);
}

$connPDO->closeConn();
