<?php
require_once ('../model/sessions.php');
require_once ('../model/asistencia.php');
require_once ('./funciones.php');

$functions = new Funciones();
$assistance = new Asistencia();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':

    break;
  case 'POST':

    break;
  default:
    $icon = $functions->getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon Acción Denegada."]);
    break;
}