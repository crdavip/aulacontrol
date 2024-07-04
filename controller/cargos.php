<?php
require_once ('../model/sessions.php');
require_once ('../model/cargos.php');
require_once ('./funciones.php');

$functions = new Funciones();
$role = new Cargos();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['columns'])) {
            $columns = json_decode($_GET['columns']);
            echo json_encode($functions->getColumns('cargo', $columns), JSON_UNESCAPED_UNICODE);
        } else {
            $center->getCenters();
        }
        break;
}

?>