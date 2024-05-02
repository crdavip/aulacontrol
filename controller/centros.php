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
            echo json_encode(getColumns($pdo, 'centro', $columns), JSON_UNESCAPED_UNICODE);
        }
}
?>