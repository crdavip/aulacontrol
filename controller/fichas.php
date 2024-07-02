<?php
require_once('../model/sessions.php');
require_once('../model/fichas.php');

$fichas = new Fichas();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        $fichas->getDataSheets();
        break;
    case 'POST':
        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
}


