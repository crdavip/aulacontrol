<?php
require_once('../model/db.php');
require_once('../model/imgKit.php');
require_once('../model/addAlert.php');
require_once("../model/sessions.php");

$connPDO = new ConnPDO();
$pdo = $connPDO->getConn();

if (!isset($_SERVER['HTTP_REFERER'])) {
    header("Location: ../");
    exit();
}

$alertIcon = 'bi bi-pencil-square';
$alertType = 'design';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'])) {
            $IDE = $data['id'];
            $stmt = $pdo->prepare('SELECT * FROM diseno WHERE DISENO_ID = :ide');
            $stmt->execute(['ide' => $IDE]);
            $design = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($design) {
                echo json_encode(['success' => true, 'design' => $design]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else if (isset($_GET['filter'])) {
            $filter = $_GET['filter'] ?? 'all';
            $sql = "SELECT DISENO_ID, NOMBRE, IMAGEN, ESTADO, FECHA_CREACION, USUARIO_ID 
                FROM diseno";
            if ($filter !== 'all') {
                if ($filter === 'null') {
                    $sql .= " WHERE ESTADO IS NULL";
                } else {
                    $sql .= " WHERE ESTADO = ?";
                }
            }
            $sql .= " ORDER BY DISENO_ID DESC";
            $stmt = $pdo->prepare($sql);
            if ($filter !== 'all' && $filter !== 'null') {
                $stmt->execute([$filter]);
            } else {
                $stmt->execute();
            }
            $designData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($designData);
        } else {
            $sql = "SELECT DISENO_ID, NOMBRE, IMAGEN, ESTADO, FECHA_CREACION, USUARIO_ID 
            FROM diseno ORDER BY DISENO_ID DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $designData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($designData);
        }
        break;
    case 'POST':
        //$data = json_decode(file_get_contents("php://input"), true);
        if (isset($_FILES['designImg']) && ($_FILES['designImg']['name'] !== "") && ($_POST['nameDesign'] !== "")) {
            $NAME = $_POST['nameDesign'];
            $IMG = $_FILES['designImg']['tmp_name'];
            $IMGNAME = $_FILES['designImg']['name'];
            $IMGTYPE = strtolower(pathinfo($IMGNAME, PATHINFO_EXTENSION));
            // Obtener Ultima Id
            $sql = "SELECT MAX(DISENO_ID) AS ultimaId FROM diseno";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $lastID = $stmt->fetch(PDO::FETCH_ASSOC);
            $idImg = ($lastID["ultimaId"] + 1) . "-01";
            $NAME = $NAME . " DS0" . ($lastID["ultimaId"] + 1);
            $srcImg = "./view/img/design/" . $idImg . "." . $IMGTYPE;

            $imgProc = new ImgProcessor($IMG, $IMGNAME, $srcImg, "design");
            $imgProc->processImage();
            $imgProcResp = $imgProc->getResponse();

            if ($imgProcResp['respKey']) {
                $sql = "INSERT INTO diseno (NOMBRE, IMAGEN, FECHA_CREACION, USUARIO_ID) VALUES (:nom, :imag, current_timestamp(), :useri)";
                $stmt = $pdo->prepare($sql);
                $response = $stmt->execute(['nom' => $NAME, 'imag' => $srcImg, 'useri' => $userId]);
                $lastDesignId = $pdo->lastInsertId();

                $alertLink = "./$alertType?cardId=$lastDesignId";

                $addAlert = new AddAlert($userId, '1', $alertIcon, "ha creado el diseño $NAME", $alertLink);
                $addAlert->insertAlert();

                echo json_encode(['designCreate' => true, 'message' => 'Diseño agregado correctamente']);
            } else {
                echo json_encode(['designCreate' => false, 'message' => $imgProcResp['resp']]);
            }
            unlink($IMG);
        } else if (isset($_FILES['designImgEdit']) && ($_FILES['designImgEdit']['name'] !== "") && ($_POST['nameDesignEdit'] !== "")) {
            $DESIGNID = $_POST['designIdEdit'];
            $NAME = $_POST['nameDesignEdit'];
            $IMG = $_FILES['designImgEdit']['tmp_name'];
            $IMGNAME = $_FILES['designImgEdit']['name'];
            // Obtener La Ruta Vieja
            $sqlGetSrc = "SELECT IMAGEN FROM diseno WHERE DISENO_ID=?";
            $stmtGetSrc = $pdo->prepare($sqlGetSrc);
            $stmtGetSrc->execute([$DESIGNID]);
            $oldImgSrc = $stmtGetSrc->fetch(PDO::FETCH_ASSOC);

            $imgRen = new ImgRenamer($oldImgSrc['IMAGEN']);
            $NEWNAME = $imgRen->processSrc();
            $imgRenResp = $imgRen->getResponse();

            if ($imgRenResp['respKey']) {
                $srcImg = "./view/img/design/" . $NEWNAME;
                $imgProc = new ImgProcessor($IMG, $IMGNAME, $srcImg, "design");
                $imgProc->processImage();
                $imgProcResp = $imgProc->getResponse();

                if ($imgProcResp['respKey']) {
                    unlink("." . $oldImgSrc['IMAGEN']);
                    $sql = "UPDATE diseno SET NOMBRE=:title, IMAGEN=:srcimg WHERE DISENO_ID=:idDe";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['title' => $NAME, 'srcimg' => $srcImg, 'idDe' => $DESIGNID]);

                    echo json_encode(['designUpdate' => true, 'message' => '¡Diseño actualizado correctamente!']);
                } else {
                    echo json_encode(['designUpdate' => false, 'message' => $imgProcResp['resp']]);
                }
            } else {
                echo json_encode(['designUpdate' => false, 'message' => 'Error al actualizar el diseño']);
            }
            unlink($IMG);
        } else if (isset($_FILES['designImgEdit']) && ($_FILES['designImgEdit']['name'] === "") && ($_POST['nameDesignEdit'] !== "")) {
            $DESIGNID = $_POST['designIdEdit'];
            $NAME = $_POST['nameDesignEdit'];

            $sql = "UPDATE diseno SET NOMBRE=? WHERE DISENO_ID=?";
            $stmt = $pdo->prepare($sql);
            $response = $stmt->execute([$NAME, $DESIGNID]);

            if ($response) {
                echo json_encode(['designUpdate' => true, 'message' => '¡Titulo actualizado!']);
            } else {
                echo json_encode(['designUpdate' => false, 'message' => 'Error al actualizar el titulo.']);
            }
        } else {
            echo json_encode(['designCreate' => false, 'message' => 'No se aceptan campos vacíos']);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['status'])) {
            $DESIGNID = $data['designId'];
            $STATUS = $data['status'];
            $designName = $data['designName'];

            $sql = "UPDATE diseno SET ESTADO=? WHERE DISENO_ID=?";
            $stmt = $pdo->prepare($sql);
            $response = $stmt->execute([$STATUS, $DESIGNID]);

            if ($STATUS) {
                $designStatus = "aprobado";
            } else {
                $designStatus = "rechazado";
            }

            $alertLink = "./$alertType?cardId=$DESIGNID";
            $addAlert = new AddAlert($userId, '2', $alertIcon, "ha $designStatus el diseño $designName", $alertLink);
            $addAlert->insertAlert();

            if ($response) {
                echo json_encode(['designUpdate' => true, 'message' => 'Diseño actualizado correctamente']);
            } else {
                echo json_encode(['designUpdate' => false, 'message' => 'Error al actualizar el diseño']);
            }
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $DESIGNID = $data['designIdDelete'];
        $IMG = $data['designImgDelete'];
        unlink("." . $IMG);
        $stmt = $pdo->prepare('DELETE FROM diseno WHERE DISENO_ID = :idde');
        if ($stmt->execute(['idde' => $DESIGNID])) {
            echo json_encode(['message' => 'Diseño eliminado correctamente']);
        }
        break;
}
$connPDO->closeConn();
