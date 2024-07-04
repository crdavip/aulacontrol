<?php
require_once('../model/sessions.php');
require_once('../model/usuariosDetalles.php');

$usersDetails = new UsuariosDetalles();
$functions = new Funciones();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        break;
    case 'POST':
        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
}

