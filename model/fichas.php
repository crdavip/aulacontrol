<?php

require_once ('../model/db.php');
require_once ('../controller/funciones.php');

class Fichas extends ConnPDO
{

  private $functions;

  public function __construct()
  {
    parent::__construct();
    $this->functions = new Funciones();
  }

  function getDataSheets()
  {
    $sql = "SELECT f.idFicha, f.ficha, f.detalle AS curso, f.aprendices, f.idCentro, c.siglas AS centro
            FROM ficha AS f
            INNER JOIN centro AS c ON c.idCentro = f.idCentro
            WHERE c.idCentro = ?
            ORDER BY f.idFicha DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$_SESSION['idCenter']]);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($row, JSON_UNESCAPED_UNICODE);
  }

  function createDataSheets($num, $detail, $idCenter)
  {
    $sql = "INSERT INTO ficha (ficha, detalle, aprendices, idCentro) VALUES (?, ?, 0, ?)";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$num, $detail, $idCenter])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Ficha Creada Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al crear la ficha"]);
    }
  }

  function updateDataSheets($num, $detail, $idCenter, $idDataSheet)
  {
    $sql = "UPDATE ficha SET ficha=?, detalle=?, idCentro=? WHERE idFicha=?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$num, $detail, $idCenter, $idDataSheet])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Ficha Actualizada Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al editar la ficha"]);
    }
  }

  function deleteDataSheets($idDataSheet)
  {
    $sql = "DELETE FROM ficha WHERE idFicha=?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$idDataSheet])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Ficha Eliminada Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al eliminar la ficha"]);
    }
  }

}