<?php
require_once ('../model/sessions.php');
require_once ('../model/asistencia.php');
require_once ('../model/registroAsistencia.php');
require_once ('./funciones.php');
require_once ('./ExportController.php');

$functions = new Funciones();
$assistance = new Asistencia();
$exportController = new ExportController();
$regAssistance = new RegistroAsistencia();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
    if (isset($_GET['idSheetExport'])) {
      $idInstructor = $_SESSION['userId'];
      $idSheet = $_GET['idSheetExport'];
      $format = "excel";

      if ($functions->checkNotEmpty([$idSheet, $idInstructor, $format])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
      } else {
        $exportController->exportRegAsist($idInstructor, $idSheet, "registroAsistencia", $format);
      }
    } elseif (isset($_GET['startDatePdf']) && isset($_GET['endDatePdf']) && isset($_GET['selectedRoomPdf'])) {
      $idRoom = $_GET['selectedRoomPdf'];
      $format = "pdf";
      $startDate = $_GET['startDatePdf'];
      $endDate = $_GET['endDatePdf'];

      if ($functions->checkNotEmpty([$idRoom, $startDate, $endDate])) {
        $icon = $functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon No se permiten campos vacíos."]);
      } else {
        $exportController->export($startDate, $endDate, $idRoom, $format, "registroAmbientes");
      }
    } elseif (isset($_GET['sheet'])) {
      $sheet = $_GET['sheet'];
      $centerDetail = $_SESSION['center'];
      $center;
      if ($centerDetail === "Centro del Diseño y Manufactura del Cuero") {
        $center = 1;
      } elseif ($centerDetail === "Centro Tecnológico del Mobiliario") {
        $center = 2;
      } elseif ($centerDetail === "Centro de Formación en Diseño Confección y Moda") {
        $center = 3;
      }
      $regAssistance->getAllRegsByCenterAndSheet($center, $sheet);
    } else {
      // ! No se usa -------------------------------->
      $centerDetail = $_SESSION['center'];
      $center;
      if ($centerDetail === "Centro del Diseño y Manufactura del Cuero") {
        $center = 1;
      } elseif ($centerDetail === "Centro Tecnológico del Mobiliario") {
        $center = 2;
      } elseif ($centerDetail === "Centro de Formación en Diseño Confección y Moda") {
        $center = 3;
      }
      $regAssistance->getAllRegsByCenter($center);
    }
    break;
  case 'POST':

    break;
  case 'PUT':
    if(isset($data['regAssistIdEdit']) && isset($data['date']) && isset($data['selectedRoom'])){
      $idEdit = $data['regAssistIdEdit'];
      $date = $data['date'];
      $room = $data['selectedRoom'];
      
    }
    break;
  default:
    $icon = $functions->getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon Acción Denegada."]);
    break;
}