<?php
require_once ('../controller/funciones.php');

class Asistencia extends ConnPDO
{
  private $functions;
  public function __construct()
  {
    parent::__construct();
    $this->functions = new Funciones();
  }

  public function saveAssistance($date, $idSheet, $idInstructor,  $idAmbiente){
    $sql = "INSERT INTO asistencia (fecha, idFicha, idInstructor, idAmbiente) VALUES (?, ?, ?, ?)";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$date, $idSheet, $idInstructor, $idAmbiente])) {
      return $idAssistanceInserted = $this->getConn()->lastInsertId();
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al registrar la asistencia"]);
    }
  }
}
