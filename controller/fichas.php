<?php
require_once('../model/sessions.php');
require_once('../model/fichas.php');

$fichas = new Fichas();
$functions = new Funciones();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        $fichas->getDataSheets();
        break;
    case 'POST':
        if (isset($data['dataSheetNum']) && isset($data['dataSheetCourse']) && isset($data['dataSheetCenter'])) {
            $num = $data['dataSheetNum'];
            $course = $data['dataSheetCourse'];
            $center = $data['dataSheetCenter'];
            if ($functions->checkNotEmpty([$num, $course, $center])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit();
            } elseif ($functions->checkExistence('ficha', ['ficha', 'idCentro'], [$num, $center])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que esta ficha ya existe."]);
                exit();
            } else {
                $fichas->createDataSheets($num, $course, $center);
                exit();
            }
        }
        break;
    case 'PUT':
        if (isset($data['dataSheetIdEdit'])) {
            $idDataSheet = $data['dataSheetIdEdit'];
            $num = $data['dataSheetNumEdit'];
            $course = $data['dataSheetCourseEdit'];
            $center = $data['dataSheetCenterEdit'];
            $numBD = $functions->getValue('ficha', 'ficha', 'idFicha', $idDataSheet);
            $courseBD = $functions->getValue('ficha', 'detalle', 'idFicha', $idDataSheet);
            $centerBD = $functions->getValue('ficha', 'idCentro', 'idFicha', $idDataSheet);
            $dsEdit = $num !== $numBD || $course !== $courseBD || $center !== $centerBD;
            if ($functions->checkNotEmpty([$num, $course, $center])) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
                exit();
            } elseif (($functions->checkExistence('ficha', ['ficha', 'detalle', 'idCentro'], [$num, $course, $center])) && $dsEdit) {
                $icon = $functions->getIcon('Err');
                echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que esta ficha ya existe."]);
                exit();
            } else {
                $fichas->updateDataSheets($num, $course, $center, $idDataSheet);
                exit();
            }
        }
        break;
    case 'DELETE':
        if (isset($data['dataSheetIdDelete'])) {
            $idDataSheet = $data['dataSheetIdDelete'];
            $fichas->deleteDataSheets($idDataSheet);
            exit();
        }
        break;
}