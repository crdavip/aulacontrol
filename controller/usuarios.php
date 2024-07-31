<?php

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$refererFilename = basename(parse_url($referer, PHP_URL_PATH));

if ($refererFilename !== 'resetpass') {
    require_once('../model/sessions.php');
}
require_once('../model/usuarios.php');
require_once('../model/usuariosDetalles.php');
require_once('../controller/qrcode.php');
require_once('./ExportController.php');

$users = new Usuarios();
$userDetails = new UsuariosDetalles();
$functions = new Funciones();
$exportController = new ExportController();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['query'])) {
            $query = $_GET['query'];

            if ($functions->checkNotEmpty([$query])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit;
            } else {
                $users->getUsersForSearching($query);
                exit;
            }

            // Busqueda .-----------
        } elseif (isset($_GET['getByRole'])) {

            try {
                $center = $_SESSION['idCenter'];
                $type = $_GET['getByRole'];
                if ($functions->checkNotEmpty([$center])) {
                    $icon = $functions->getIcon('Err');
                    echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                    exit;
                }
                if ($type == "students") {
                    // print_r($type);
                    $users->getByRole("Aprendiz", $center);
                    exit;
                } elseif ($type == "instructors") {
                    $users->getByRole("Instructor", $center);
                    exit;
                } else {
                    $icon = $functions->getIcon('Err');
                    echo json_encode(['success' => false, 'message' => "$icon Parece que ocurrió un error."]);
                    exit;
                }
            } catch (Exception $e) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon Parece que ocurrio un error." . $e]);
                exit;
            }

        } elseif (isset($_GET['queryAdd'])) {
            $query = $_GET['queryAdd'];
            // $idCenter = session
            if ($functions->checkNotEmpty([$query])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit;
            } else {
                $users->getUsersForSearchingAdd($query);
                exit;
            }
        } elseif (isset($_GET['queryAll'])) {
            $query = $_GET['queryAll'];
            $centerDetail = $_SESSION['center'];
            $center;
            if ($centerDetail === "Centro del Diseño y Manufactura del Cuero") {
                $center = 1;
            } elseif ($centerDetail === "Centro Tecnológico del Mobiliario") {
                $center = 2;
            } elseif ($centerDetail === "Centro de Formación en Diseño Confección y Moda") {
                $center = 3;
            }
            // $idCenter = session
            if (!$query) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon Navegación erronea."]);
                exit;
            } else {
                $users->getTraineesAvailables($center);
                exit;
            }
        } elseif (isset($_GET['format'])) {
            $format = $_GET['format'];

            if ($format === "excel") {
                $exportController->simpleExport($format, "usuarios");
            } elseif ($format === "pdf") {
                $exportController->simpleExport($format, "usuarios");
            } else {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No es un formato válido."]);
            }
        } elseif (isset($_GET['docUserAssoc'])) {
            $docUserAssoc = $_GET['docUserAssoc'];
            $users->getDocUserAssoc($docUserAssoc);
            exit();
        } elseif (isset($_GET['docUserAssoc2'])) {
            $docUserAssoc = $_GET['docUserAssoc2'];
            $users->getDocUserAssoc2($docUserAssoc);
            exit();
        } else {
            $users->getUsers();
            exit;
        }
    case 'POST':
        if (isset($data['nameUser']) && isset($data['docUser']) && isset($data['passUser']) && isset($data['rolUser']) && isset($data['centerUser'])) {
            $nameUser = $data['nameUser'];
            $docUser = $data['docUser'];
            $mailUser = $data['mailUser'];
            $passUser = $data['passUser'];
            $pass2User = $data['pass2User'];
            $rolUser = $data['rolUser'];
            $centerUser = $data['centerUser'];
            if ($functions->checkNotEmpty([$nameUser, $docUser, $mailUser, $passUser, $pass2User, $rolUser, $centerUser])) {
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
                $userDetails->createUsersDetails($nameUser, $mailUser, $userQr, $centerUser, intval($ultimaId));
                if ($_SESSION['success']) {
                    $icon = $functions->getIcon('OK');
                    echo json_encode(['success' => true, 'message' => "$icon ¡Usuario Creado Exitosamente!"]);
                } else {
                    $icon = $functions->getIcon('Err');
                    echo json_encode(['success' => false, 'message' => "$icon Error al crear el usuario"]);
                }
                exit();
            }
        }
        break;
    case 'PUT':
        if (isset($data['userIdEdit']) && isset($data['nameUserEdit']) && isset($data['docUserEdit'])) {
            $idUser = $data['userIdEdit'];
            $nameUser = $data['nameUserEdit'];
            $mailUser = $data['mailUserEdit'];
            $docUser = $data['docUserEdit'];
            $rolUser = $data['rolUserEdit'];
            $centerUser = $data['centerUserEdit'];
            if ($functions->checkNotEmpty([$nameUser, $$mailUser, $docUser, $rolUser, $centerUser])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit();
            } else {
                $users->editUsers($idUser, $docUser, $rolUser);
                $userDetails->editUsersDetails($nameUser, $mailUser, $centerUser, $idUser);
                if ($_SESSION['success']) {
                    $icon = $functions->getIcon('OK');
                    echo json_encode(['success' => true, 'message' => "$icon ¡Usuario Editado Exitosamente!"]);
                } else {
                    $icon = $functions->getIcon('Err');
                    echo json_encode(['success' => false, 'message' => "$icon Error al editar el usuario"]);
                }
                exit();
            }
        } elseif (isset($data['userIdPassEdit'])) {
            $idUSer = $data['userIdPassEdit'];
            $userOldPass = $data['userOldPass'];
            $userPassProfile = $data['userPassProfile'];
            $userPassProfileTwo = $data['userPassProfileTwo'];
            if ($functions->checkNotEmpty([$idUSer, $userOldPass, $userPassProfile, $userPassProfileTwo])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit();
            } elseif (!($functions->checkPassword($userPassProfile, $userPassProfileTwo))) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon ¡Oh no! Las contraseñas no coinciden."]);
            } else {
                $passBD = $functions->getValue("usuario", "contrasena", "idUsuario", $idUSer);
                $userOldPass = sha1($userOldPass);
                if (!($functions->checkPassword($passBD, $userOldPass))) {
                    $icon = $functions->getIcon('Err');
                    echo json_encode(['success' => false, 'message' => "$icon ¡Contraseña actual incorrecta!."]);
                } else {
                    $users->updatePass($idUSer, sha1($userPassProfile));
                    if ($_SESSION['success']) {
                        $icon = $functions->getIcon('OK');
                        echo json_encode(['success' => true, 'message' => "$icon ¡Usuario Editado Exitosamente!"]);
                    } else {
                        $icon = $functions->getIcon('Err');
                        echo json_encode(['success' => false, 'message' => "$icon Error al editar el usuario"]);
                    }
                    exit();
                }
            }
        } elseif (isset($data['userPassId'])) {
            $idUSer = $data['userPassId'];
            $passEdit = $data['passEdit'];
            $passEditTwo = $data['passEditTwo'];
            if ($functions->checkNotEmpty([$idUSer, $passEdit, $passEditTwo])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit();
            } elseif (!($functions->checkPassword($passEdit, $passEditTwo))) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon ¡Oh no! Las contraseñas no coinciden."]);
            } else {
                $users->updatePass($idUSer, sha1($passEdit));
                if ($_SESSION['success']) {
                    $icon = $functions->getIcon('OK');
                    echo json_encode(['success' => true, 'message' => "$icon ¡Usuario Editado Exitosamente!"]);
                } else {
                    $icon = $functions->getIcon('Err');
                    echo json_encode(['success' => false, 'message' => "$icon Error al editar el usuario"]);
                }
                exit();
            }
        }
        break;
    case 'DELETE':
        if (isset($data['userIdDelete'])) {
            $idUser = $data['userIdDelete'];
            if ($functions->checkNotEmpty([$idUser])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit();
            } else {
                $userImg = $functions->getValue("usuario_detalle", "imagen", "idUsuario", $idUser);
                $userImgQr = $functions->getValue("usuario_detalle", "imagenQr", "idUsuario", $idUser);
                $users->deleteUsers($idUser);
                $userDetails->deleteUsersDetails($idUser);
                if ($userImg !== "./view/img/users/default.jpg") {
                    unlink("." . $userImg);
                }
                unlink("." . $userImgQr);
                exit();
            }
        }
        break;
}
