<?php
require_once('../model/db.php');
require_once('../model/sessions.php');

$connPDO = new ConnPDO;
$pdo = $connPDO->getConn();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        getDesigns($pdo);
        break;
    case 'POST':
        // createDesign();
        break;
    case 'PUT':
        // updateDesign();
        break;
    case 'DELETE':
        // deleteDesign();
        break;
}

function getDesigns($pdo)
{
    $sql = "SELECT cr.classNum, cr.status, ud.name 
            FROM classroom AS cr
            INNER JOIN user_detail AS ud ON cr.instructorId = ud.userId
            ORDER BY cr.classroomId DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($row);
    $idRol = $_SESSION['idRol'];
    $idUsuario = $_SESSION['idUsuario'];
    $filter = $_GET['filter'];
}
