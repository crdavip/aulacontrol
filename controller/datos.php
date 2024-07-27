<?php
require_once('../model/sessions.php');
require_once('../model/usuarios.php');
require_once('../model/usuariosDetalles.php');
require_once('../model/imgKit.php');

$users = new Usuarios();
$userDetails = new UsuariosDetalles();
$functions = new Funciones();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        if (isset($_FILES['userImgProfile']) && ($_FILES['userImgProfile']['name'] === "")) {
            $icon = $functions->getIcon('Err');
            echo json_encode(['success' => false, 'message' => "$icon ¡Recuerda cargar la foto del carnet!"]);
        } else {
            $imgUser = $_FILES['userImgProfile']['tmp_name'];
            $imgUserName = $_FILES['userImgProfile']['name'];
            $idUser = $_POST['idUser'];
            $pass = $_POST['pass'];
            $passTwo = $_POST['passTwo'];
            $birthDate = $_POST['birthDate'];
            $genre = $_POST['genre'];
            $docUser = $functions->getValue("usuario", "documento", "idUsuario", $idUser);
            $imgUserOld = $functions->getValue("usuario_detalle", "imagen", "idUsuario", $idUser);
            if ($functions->checkNotEmpty([$pass, $passTwo, $birthDate, $genre])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit();
            } elseif (!($functions->checkPassword($pass, $passTwo))) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon ¡Oh no! Las contraseñas no coinciden."]);
            } elseif ($pass == $docUser) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon La contraseña no puede ser igual al documento."]);
            } else {
                if ($imgUserOld === "./view/img/users/default.jpg") {
                    $newImgUser = "./view/img/users/$idUser-01.jpg";
                } else {
                    $imgRen = new ImgRenamer($imgUserOld);
                    $newName = $imgRen->processSrc($imgUserOld);
                    $imgRenResp = $imgRen->getResponse();
                    $newImgUser = "./view/img/users/" . $newName;
                    unlink("." . $imgUserOld);
                }

                $imgProc = new ImgProcessor($imgUser, $imgUserName, $newImgUser, "user");
                $imgProc->processImage();
                $imgProcResp = $imgProc->getResponse();

                if ($imgProcResp['respKey']) {
                    $users->updatePass($idUser, sha1($pass));
                    $userDetails->updateUserDetails($newImgUser, $birthDate, $genre, $idUser);
                    if ($_SESSION['success']) {
                        $_SESSION['img'] = $newImgUser;
                        $_SESSION['birth'] = $birthDate;
                        $_SESSION['firstTime'] = 'No';
                        $icon = $functions->getIcon('OK');
                        echo json_encode(['success' => true, 'message' => "$icon ¡Usuario Actualizado Exitosamente!"]);
                    } else {
                        $icon = $functions->getIcon('Err');
                        echo json_encode(['success' => false, 'message' => "$icon Error al actualizar el usuario"]);
                    }
                } else {
                    echo json_encode(['userUpdate' => false, 'message' => $imgProcResp['resp']]);
                }
                exit();
            }
        }
        break;
}
