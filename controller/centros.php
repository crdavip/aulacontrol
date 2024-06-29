<?php
require_once ('../model/sessions.php');
require_once ('../model/centros.php');
require_once ('./funciones.php');

$functions = new Funciones();
$center = new Centro();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['columns'])) {
            $columns = json_decode($_GET['columns']);
            echo json_encode($functions->getColumns('centro', $columns), JSON_UNESCAPED_UNICODE);
        } else {
            $center->getCenters();
        }
        break;
}

?>