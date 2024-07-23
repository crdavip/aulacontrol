<?php 
require_once('../model/usuarios.php');
require_once('../model/usuariosDetalles.php');
require_once('../controller/qrcode.php');

$users = new Usuarios();
$userDetails = new UsuariosDetalles();
$functions = new Funciones();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$type = $_FILES['dataUsers']['type'];

if ($type === 'text/csv') {
    $size = $_FILES['dataUsers']['size'];
    $fileTmp = $_FILES['dataUsers']['tmp_name'];
    $rows = file($fileTmp);

    $i = 0;

    foreach ($rows as $row) {
        $numRecords = count($rows);
        $numAddRecords =  ($numRecords - 1);

        if ($i != 0) {

            $data = explode(";", $row);
        
            $name = !empty($data[0])  ? ($data[0]) : '';
            $doc = !empty($data[1])  ? ($data[1]) : '';
            $pass = !empty($data[2])  ? ($data[2]) : '';
            $mail = !empty($data[3])  ? ($data[3]) : '';
            $role = !empty($data[4])  ? ($data[4]) : '';
            $center = !empty($data[5])  ? ($data[5]) : '';
        
            if( !empty($doc) ){
                $count = $users->getDocCount($doc);
            }   

            //No existe Registros Duplicados
            if ( $count == 0 ) { 
                $ultimaId = $users->createUsers($doc, sha1($pass), $role);
                $qr = new QRgenerator($doc, "user");
                $qr->createQR();
                $userQr = "./view/img/users/qr-$doc.png";
                $userDetails->createUsersDetails($name, $mail, $userQr, $center, intval($ultimaId)); 
            } else {
                $idUser = $users->editUsersImport($doc, $role);
                $userDetails->editUsersDetails($name, $mail, intval($center), intval($idUser));
            } 
    }

    $i++;
    }

    if ($_SESSION['success']) {
        $icon = $functions->getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡$numAddRecords Usuarios Creados Exitosamente!"]);
    } else {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon Error al importar los usuarios"]);
    }

} else {
    $icon = $functions->getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon Sólo se permite el formato CSV"]);
}

}
?>