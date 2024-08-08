<?php 
require_once ('../model/sessions.php');
require_once('../model/equipos.php');
require_once('../controller/qrcode.php');
require_once ('./funciones.php');

$functions = new Funciones();
$devices = new Equipos();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$type = $_FILES['dataDevices']['type'];

if ($type === 'text/csv') {
    $size = $_FILES['dataDevices']['size'];
    $fileTmp = $_FILES['dataDevices']['tmp_name'];
    $rows = file($fileTmp);

    $i = 0;

    foreach ($rows as $row) {
        $numRecords = count($rows);
        $numAddRecords =  ($numRecords - 1);

        if ($i != 0) {

            $data = explode(";", $row);
        
            $ref = !empty($data[0])  ? ($data[0]) : '';
            $brandDevice = !empty($data[1])  ? ($data[1]) : '';
            $idRoom = !empty($data[2])  ? ($data[2]) : '';
        
            if( !empty($ref) ){
                $count = $devices->getRefCount($ref);
            }   

            //No existe Registros Duplicados
            if ( $count == 0 ) { 
                $qr = new QRgenerator($ref, "device");
                $qr->createQR();
                $deviceQr = "./view/img/devices/qr-$ref.png";
                $devices->createDevicesImport($ref, $brandDevice, "Disponible", $deviceQr, intval($idRoom));
            } else {
                $devices->editDevicesImport($ref, $brandDevice, intval($idRoom));
            } 
    }

    $i++;
    }

    if ($_SESSION['success']) {
        $icon = $functions->getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡$numAddRecords Equipos Creados Exitosamente!"]);
    } else {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon Error al importar los equipos"]);
    }

} else {
    $icon = $functions->getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon Sólo se permite el formato CSV"]);
}

}
?>