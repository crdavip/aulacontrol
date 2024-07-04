<?php
require_once('../model/sessions.php');
require_once('../model/usuarios.php');

$users = new Usuarios();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['docUserAssoc'])) {
            $docUserAssoc = $_GET['docUserAssoc'];
            $users->getDocUserAssoc($docUserAssoc);
            exit();
        } else {
            $users->getUsers();
            exit;
        }
        break;
    case 'POST':
        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
}

