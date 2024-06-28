<?php
require_once('./db.php');
require_once('../controller/funciones.php');

class Ambientes extends ConnPDO
{

  private $functions = new Funciones();

  function getRooms()
  {
    $sql = "SELECT a.idAmbiente, a.numero, a.estado, a.afluencia, c.idCentro, c.siglas AS centro
            FROM ambiente AS a
            INNER JOIN centro as c ON c.idCentro = a.idCentro
            ORDER BY a.numero ASC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $classRoom = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($classRoom);
  }

  function createRoom($num, $center)
  {
    $sql = "INSERT INTO ambiente (numero, idCentro) VALUES (?, ?)";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$num, $center])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Ambiente Creado Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al crear el ambiente"]);
    }
  }

  function updateRoom($num, $center, $id)
  {
    $sqlCheck = "SELECT * FROM registro_ambiente WHERE idAmbiente = ? AND fin IS NULL";
    $stmtCheck = $this->getConn()->prepare($sqlCheck);
    $stmtCheck->execute([$id]);
    $count = $stmtCheck->rowCount();
    if ($count === 0) {
      $sql = "UPDATE ambiente SET numero = ?, centro = ? WHERE idAmbiente = ?";
      $stmt = $this->getConn()->prepare($sql);
      if ($stmt->execute([$num, $center, $id])) {
        $icon = $this->functions->getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡Ambiente Actualizado Exitosamente!"]);
      } else {
        $icon = $this->functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon Error al actualizar el ambiente"]);
      }
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon El ambiente tiene una vinculación activa"]);
    }
  }

  function deleteRoom($id)
  {
    $sqlCheck = "SELECT * FROM registro_ambiente WHERE idAmbiente = ? AND fin IS NULL";
    $stmtCheck = $this->getConn()->prepare($sqlCheck);
    $stmtCheck->execute([$id]);
    $count = $stmtCheck->rowCount();
    if ($count === 0) {
      $sql = "DELETE FROM ambiente WHERE idAmbiente = ?";
      $stmt = $this->getConn()->prepare($sql);
      if ($stmt->execute([$id])) {
        $icon = $this->functions->getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡Ambiente Eliminado Exitosamente!"]);
      } else {
        $icon = $this->functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon Error al eliminar el ambiente"]);
      }
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon El ambiente tiene una vinculación activa"]);
    }
  }
}