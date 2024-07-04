<?php
require_once('../model/sessions.php');
require_once('../model/usuarios.php');
require_once('../model/usuariosDetalles.php');
require_once('../controller/qrcode.php');

$users = new Usuarios();
$userDetails = new UsuariosDetalles();
$functions = new Funciones();

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
        if (isset($data['nameUser']) && isset($data['docUser']) && isset($data['passUser']) && isset($data['rolUser']) && isset($data['centerUser'])) {
            $nameUser = $data['nameUser'];
            $docUser = $data['docUser'];
            $passUser = $data['passUser'];
            $pass2User = $data['pass2User'];
            $rolUser = $data['rolUser'];
            $centerUser = $data['centerUser'];
            if ($functions->checkNotEmpty([$nameUser, $docUser, $passUser, $pass2User, $rolUser, $centerUser])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit();
            } elseif ($functions->checkExistence('usuario', ['documento'], [$docUser])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que este usuario ya existe."]);
                exit();
            } elseif (!($functions->checkPassword($passUser, $pass2User))) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon ¡Oh no! Las contraseñas no coinciden."]);
            } else {
                $ultimaId = $users->createUsers($docUser, sha1($passUser), $rolUser);
                $qr = new QRgenerator($docUser, "user");
                $qr->createQR();
                $userQr = "./view/img/users/qr-$docUser.png";
                $userDetails->createUsersDetails($nameUser, $userQr, $centerUser, intval($ultimaId));
                exit();
            }
        }
        break;
    case 'PUT':
        break;
    case 'DELETE':
        break;
}

