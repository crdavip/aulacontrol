<?php
require_once ('../model/sessions.php');
require_once ('../model/asistencia.php');
require_once ('../model/registroAsistencia.php');
require_once ('./funciones.php');

$functions = new Funciones();
$assistance = new Asistencia();
$regAssistance = new RegistroAsistencia();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
  case 'GET':
      if(isset($_GET['formta'])){

      } else {
        $centerDetail = $_SESSION['center'];
      $center;
      if($centerDetail === "Centro del Diseño y Manufactura del Cuero"){
        $center = 1;
      } elseif($centerDetail === "Centro Tecnológico del Mobiliario"){
        $center = 2;
      } elseif($centerDetail === "Centro de Formación en Diseño Confección y Moda") {
        $center = 3;
      }
      $regAssistance->getAllRegsByCenter($center);
      }
    break;
  case 'POST':

    break;
  default:
    $icon = $functions->getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon Acción Denegada."]);
    break;
}