<?php
session_start();
require_once('../model/db.php');
require_once('../model/imgKit.php');

$connPDO = new ConnPDO();
$pdo = $connPDO->getConn();

if (!isset($_SERVER['HTTP_REFERER'])) {
    header("Location: ../");
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'])) {
            $IDE = $data['id'];
            $stmt = $pdo->prepare('SELECT * FROM usuario WHERE USUARIO_ID = :ide');
            $stmt->execute(['ide' => $IDE]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuario) {
                echo json_encode(['success' => true, 'usuario' => $usuario]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else if (isset($_GET['filter'])) {
            $filter = $_GET['filter'] ?? 'all';
            $sql = "SELECT u.USUARIO_ID, u.DOCUMENTO, u.EMAIL, u.NOMBRE, u.CONTRASENA, u.ROL, u.IMAGEN, u.ESTADO, r.ROL_ID, r.DESCRIPCION AS ROL 
                FROM usuario u 
                INNER JOIN rol r ON u.ROL = r.ROL_ID";
            if ($filter !== 'all') {
                $sql .= " WHERE u.ROL = ?";
            }
            $sql .= " ORDER BY u.USUARIO_ID ASC";
            $stmt = $pdo->prepare($sql);
            if ($filter !== 'all') {
                $stmt->execute([$filter]);
            } else {
                $stmt->execute();
            }
            $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($usersData);
        } else {
            $sql = "SELECT u.USUARIO_ID, u.DOCUMENTO, u.EMAIL, u.NOMBRE, u.CONTRASENA, u.ROL, u.IMAGEN, u.ESTADO, r.ROL_ID, r.DESCRIPCION AS ROL 
            FROM usuario u 
            INNER JOIN rol r ON u.ROL = r.ROL_ID
            ORDER BY USUARIO_ID ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($usersData);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['names'])) {
            $NAMES = $data['names'];
            $DOC = $data['doc'];
            $MAIL = $data['mail'];
            $PASS = $data['pass'];
            $PASSTWO = $data['passTwo'];
            $ROLE = $data['role'];

            if (empty($NAMES) || empty($DOC) || empty($MAIL) || empty($PASS) || empty($PASSTWO) || empty($ROLE)) {
                echo json_encode(['userCreate' => false, 'message' => 'No se aceptan campos vacíos']);
            } else {
                if ($PASS === $PASSTWO) {
                    $PASS = sha1($PASS);
                    $sqlCheck = "SELECT COUNT(*) FROM usuario WHERE DOCUMENTO=?";
                    $stmtCheck = $pdo->prepare($sqlCheck);
                    $stmtCheck->execute([$DOC]);
                    $docCheck = $stmtCheck->fetchColumn();

                    if ($docCheck === 0) {
                        $sqlMailCheck = "SELECT COUNT(*) FROM usuario WHERE EMAIL=?";
                        $stmtMailCheck = $pdo->prepare($sqlMailCheck);
                        $stmtMailCheck->execute([$MAIL]);
                        $mailCheck = $stmtMailCheck->fetchColumn();

                        if ($mailCheck === 0) {
                            $IMG = "./view/img/users/default.jpg";
                            $sql = "INSERT INTO usuario (NOMBRE, DOCUMENTO, EMAIL, CONTRASENA, ROL, IMAGEN, ESTADO) VALUES (:nom, :docu, :mail, :pss, :roles, :img, 1)";
                            $stmt = $pdo->prepare($sql);
                            $response = $stmt->execute(['nom' => $NAMES, 'docu' => $DOC, 'mail' => $MAIL, 'pss' => $PASS, 'roles' => $ROLE, 'img' => $IMG]);

                            if ($response) {
                                echo json_encode(['userCreate' => true, 'message' => 'Usuario agregado correctamente']);
                            }
                        } else {
                            echo json_encode(['userCreate' => false, 'message' => 'El correo ya está en uso']);
                        }
                    } else {
                        echo json_encode(['userCreate' => false, 'message' => 'El documento ya está en uso']);
                    }
                } else {
                    echo json_encode(['userCreate' => false, 'message' => 'Las contraseñas no coinciden']);
                }
            }
        } else if (isset($_FILES['userImgProfile']) && $_FILES['userImgProfile']['name'] !== "") {
            $ID = $_POST['userIdProfile'];
            $NAMES = $_POST['userNameProfile'];
            $DOC = $_POST['userDocProfile'];
            $MAIL = $_POST['userMailProfile'];
            $IMG = $_FILES['userImgProfile']['tmp_name'];
            $IMGNAME = $_FILES['userImgProfile']['name'];
            $IMGTYPE = strtolower(pathinfo($IMGNAME, PATHINFO_EXTENSION));

            if (empty($NAMES) || empty($DOC) || empty($MAIL)) {
                echo json_encode(['userCreate' => false, 'message' => 'No se aceptan campos vacíos']);
            } else {
                $sqlGet = "SELECT DOCUMENTO, EMAIL FROM usuario WHERE USUARIO_ID=?";
                $stmtGet = $pdo->prepare($sqlGet);
                $stmtGet->execute([$ID]);
                $existing = $stmtGet->fetch(PDO::FETCH_ASSOC);

                $docCheck = checkExistence($pdo, 'usuario', 'DOCUMENTO', $DOC, $ID);
                $mailCheck = checkExistence($pdo, 'usuario', 'EMAIL', $MAIL, $ID);

                if ($docCheck === 0 || ($docCheck === 1 && $DOC === $existing['DOCUMENTO'])) {
                    if ($mailCheck === 0 || ($mailCheck === 1 && $MAIL === $existing['EMAIL'])) {

                        $sql = "SELECT IMAGEN FROM usuario WHERE USUARIO_ID=?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$ID]);
                        $oldSrcImg = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($oldSrcImg['IMAGEN'] === "./view/img/users/default.jpg") {
                            $newSrcImg = "./view/img/users/" . $ID . "-01." . $IMGTYPE;
                        } else {
                            $imgRen = new ImgRenamer($oldSrcImg['IMAGEN']);
                            $NEWNAME = $imgRen->processSrc($oldSrcImg['IMAGEN']);
                            $imgRenResp = $imgRen->getResponse();
                            $newSrcImg = "./view/img/users/" . $NEWNAME;
                            unlink("." . $oldSrcImg['IMAGEN']);
                        }

                        $imgProc = new ImgProcessor($IMG, $IMGNAME, $newSrcImg, "user");
                        $imgProc->processImage();
                        $imgProcResp = $imgProc->getResponse();

                        if ($imgProcResp['respKey']) {
                            $sql = "UPDATE usuario SET DOCUMENTO=?, EMAIL=?, NOMBRE=?, IMAGEN=? WHERE USUARIO_ID=?";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$DOC, $MAIL, $NAMES, $newSrcImg, $ID]);

                            $sqlSession = "SELECT DOCUMENTO, NOMBRE, ROL, IMAGEN, EMAIL, ESTADO FROM usuario WHERE USUARIO_ID=:userid";
                            $stmtSession = $pdo->prepare($sqlSession);
                            $stmtSession->execute(['userid' => $_SESSION['USUARIO_ID']]);
                            $newUserData = $stmtSession->fetch(PDO::FETCH_ASSOC);

                            $_SESSION['EMAIL'] = $newUserData['EMAIL'];
                            $_SESSION['NOMBRE'] = $newUserData['NOMBRE'];
                            $_SESSION['DOCUMENTO'] = $newUserData['DOCUMENTO'];
                            $_SESSION['ROL'] = $newUserData['ROL'];
                            $_SESSION['IMAGEN'] = $newUserData['IMAGEN'];
                            $_SESSION['ESTADO'] = $newUserData['ESTADO'];

                            echo json_encode(['userUpdate' => true, 'message' => "¡Perfil actualizado correctamente!"]);
                        } else {
                            echo json_encode(['userUpdate' => false, 'message' => $imgProcResp['resp']]);
                        }
                    } else {
                        echo json_encode(['userUpdate' => false, 'message' => 'El correo ya está en uso']);
                    }
                } else {
                    echo json_encode(['userUpdate' => false, 'message' => 'El documento ya está en uso']);
                }
            }
        } else if ($_POST['userNameProfile'] !== "") {
            $ID = $_POST['userIdProfile'];
            $NAMES = $_POST['userNameProfile'];
            $DOC = $_POST['userDocProfile'];
            $MAIL = $_POST['userMailProfile'];

            if (empty($NAMES) || empty($DOC) || empty($MAIL)) {
                echo json_encode(['userCreate' => false, 'message' => 'No se aceptan campos vacíos']);
            } else {
                $sqlGet = "SELECT DOCUMENTO, EMAIL FROM usuario WHERE USUARIO_ID=?";
                $stmtGet = $pdo->prepare($sqlGet);
                $stmtGet->execute([$ID]);
                $existing = $stmtGet->fetch(PDO::FETCH_ASSOC);

                $docCheck = checkExistence($pdo, 'usuario', 'DOCUMENTO', $DOC, $ID);
                $mailCheck = checkExistence($pdo, 'usuario', 'EMAIL', $MAIL, $ID);

                if ($docCheck === 0 || ($docCheck === 1 && $DOC === $existing['DOCUMENTO'])) {
                    if ($mailCheck === 0 || ($mailCheck === 1 && $MAIL === $existing['EMAIL'])) {
                        $sql = "UPDATE usuario SET DOCUMENTO=?, EMAIL=?, NOMBRE=? WHERE USUARIO_ID=?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$DOC, $MAIL, $NAMES, $ID]);

                        $sqlSession = "SELECT DOCUMENTO, NOMBRE, ROL, IMAGEN, EMAIL, ESTADO FROM usuario WHERE USUARIO_ID=:userid";
                        $stmtSession = $pdo->prepare($sqlSession);
                        $stmtSession->execute(['userid' => $_SESSION['USUARIO_ID']]);
                        $newUserData = $stmtSession->fetch(PDO::FETCH_ASSOC);

                        $_SESSION['EMAIL'] = $newUserData['EMAIL'];
                        $_SESSION['NOMBRE'] = $newUserData['NOMBRE'];
                        $_SESSION['DOCUMENTO'] = $newUserData['DOCUMENTO'];
                        $_SESSION['ROL'] = $newUserData['ROL'];
                        $_SESSION['IMAGEN'] = $newUserData['IMAGEN'];
                        $_SESSION['ESTADO'] = $newUserData['ESTADO'];

                        echo json_encode(['userUpdate' => true, 'message' => "¡Perfil actualizado correctamente!"]);
                    } else {
                        echo json_encode(['userUpdate' => false, 'message' => 'El correo ya está en uso']);
                    }
                } else {
                    echo json_encode(['userUpdate' => false, 'message' => 'El documento ya está en uso']);
                }
            }
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['userPassId'])) {
            $ID = $data['userPassId'];
            $PASS = $data['passEdit'];
            $PASSTWO = $data['passEditTwo'];

            if (empty($PASS) || empty($PASSTWO)) {
                echo json_encode(['passUpdate' => false, 'message' => 'No se aceptan campos vacíos']);
            } elseif ($PASS === $PASSTWO) {
                $PASS = sha1($PASS);
                $sql = "UPDATE usuario SET CONTRASENA=?, TOKEN=null, LOSTPASS=null WHERE USUARIO_ID=?";
                $stmt = $pdo->prepare($sql);
                $response = $stmt->execute([$PASS, $ID]);

                if ($response) {
                    echo json_encode(['passUpdate' => true, 'message' => 'Contraseña actualizada correctamente']);
                } else {
                    echo json_encode(['passUpdate' => false, 'message' => 'Error al actualizar la contraseña']);
                }
            } else {
                echo json_encode(['passUpdate' => false, 'message' => 'Las contraseñas no coinciden']);
            }
        } elseif (isset($data['userId'])) {
            $ID = $data['userId'];
            $NAMES = $data['namesEdit'];
            $MAIL = $data['mailEdit'];
            $DOC = $data['docEdit'];
            $ROLE = $data['roleEdit'];
            if (empty($NAMES) || empty($DOC) || empty($MAIL) || empty($ROLE)) {
                echo json_encode(['userUpdate' => false, 'message' => 'No se aceptan campos vacíos']);
            } else {
                $sqlGet = "SELECT DOCUMENTO, EMAIL FROM usuario WHERE USUARIO_ID=?";
                $stmtGet = $pdo->prepare($sqlGet);
                $stmtGet->execute([$ID]);
                $existing = $stmtGet->fetch(PDO::FETCH_ASSOC);

                $docCheck = checkExistence($pdo, 'usuario', 'DOCUMENTO', $DOC, $ID);
                $mailCheck = checkExistence($pdo, 'usuario', 'EMAIL', $MAIL, $ID);

                if ($docCheck === 0 || ($docCheck === 1 && $DOC == $existing['DOCUMENTO'])) {
                    if ($mailCheck === 0 || ($mailCheck === 1 && $MAIL == $existing['EMAIL'])) {
                        $sql = "UPDATE usuario SET NOMBRE=?, DOCUMENTO=?, EMAIL=?, ROL=? WHERE USUARIO_ID=?";
                        $stmt = $pdo->prepare($sql);
                        $response = $stmt->execute([$NAMES, $DOC, $MAIL, $ROLE, $ID]);

                        if ($response) {
                            echo json_encode(['userUpdate' => true, 'message' => 'Usuario actualizado correctamente']);
                        } else {
                            echo json_encode(['userUpdate' => false, 'message' => 'Error al actualizar el usuario']);
                        }
                    } else {
                        echo json_encode(['userUpdate' => false, 'message' => 'El correo ya está en uso']);
                    }
                } else {
                    echo json_encode(['userUpdate' => false, 'message' => 'El documento ya está en uso']);
                }
            }
        } else if (isset($data['userIdPassEdit'])) {
            $ID = $data['userIdPassEdit'];
            $OLDPASS = $data['userOldPass'];
            $PASS = $data['userPassProfile'];
            $PASSTWO = $data['userPassProfileTwo'];

            if (empty($OLDPASS) || (empty($PASS)) || (empty($PASSTWO))) {
                echo json_encode(['passUpdate' => false, 'message' => 'No se aceptan campos vacíos']);
            } elseif ($PASS === $PASSTWO) {
                $sqlCheckPass = "SELECT CONTRASENA FROM usuario WHERE USUARIO_ID=?";
                $stmtCheckPass = $pdo->prepare($sqlCheckPass);
                $stmtCheckPass->execute([$ID]);
                $checkPass = $stmtCheckPass->fetch(PDO::FETCH_ASSOC);

                $passBD = $checkPass['CONTRASENA'];
                $OLDPASS = sha1($OLDPASS);
                if ($passBD === $OLDPASS) {
                    $PASS = sha1($PASS);
                    $sql = "UPDATE usuario SET CONTRASENA=?, TOKEN=null, LOSTPASS=null WHERE USUARIO_ID=?";
                    $stmt = $pdo->prepare($sql);
                    $response = $stmt->execute([$PASS, $ID]);

                    if ($response) {
                        echo json_encode(['passUpdate' => true, 'message' => 'Contraseña actualizada correctamente']);
                    } else {
                        echo json_encode(['passUpdate' => false, 'message' => 'Error al actualizar la contraseña']);
                    }
                } else {
                    echo json_encode(['passUpdate' => false, 'message' => '¡Contraseña actual incorrecta!']);
                }
            } else {
                echo json_encode(['passUpdate' => false, 'message' => 'Las contraseñas no coinciden']);
            }
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $IDE = $data['userIdDelete'];

        $sql = "SELECT IMAGEN FROM usuario WHERE USUARIO_ID=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$IDE]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['IMAGEN'] !== "./view/img/users/default.jpg") {
            unlink('.' . $row['IMAGEN']);
        }

        $stmtDelete = $pdo->prepare('DELETE FROM usuario WHERE USUARIO_ID = :ide');
        if ($stmtDelete->execute(['ide' => $IDE])) {
            echo json_encode(['message' => 'Usuario eliminado correctamente']);
        }
        break;
}

function checkExistence($pdo, $table, $column, $value, $excludeId = null)
{
    $sql = "SELECT COUNT(*) FROM $table WHERE $column=?";
    $params = [$value];
    if ($excludeId !== null) {
        $sql .= " AND USUARIO_ID <> ?";
        $params[] = $excludeId;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}

$connPDO->closeConn();
?>