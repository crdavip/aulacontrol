<?php
require_once ('../controller/funciones.php');

class RegistroAmbientes extends ConnPDO
{

  private $functions;

  public function __construct()
  {
    parent::__construct(); // Llamar al constructor de la clase padre
    $this->functions = new Funciones();
  }

  function getAllRoomHistory()
  {
    $sql = "SELECT ra.idRegistro, ud.nombre, ud.imagen, u.documento, a.numero, c.detalle AS centro, ra.inicio, ra.fin, ra.llaves, ra.controlTv, ra.controlAire
            FROM registro_ambiente AS ra
            INNER JOIN usuario_detalle AS ud ON ud.idUsuario = ra.idInstructor
            INNER JOIN usuario AS u ON u.idUsuario = ra.idInstructor
            INNER JOIN ambiente AS a ON a.idAmbiente = ra.idAmbiente
            INNER JOIN centro AS c ON c.idCentro = a.idCentro
            ORDER BY idRegistro DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
  }

  function getGroupOfHistory($idAmbiente, $startDateTime, $endDateTime){
    $sql = "SELECT ra.*, a.numero, ud.nombre AS instructor FROM registro_ambiente AS ra INNER JOIN usuario_detalle AS ud ON ud.idUsuario = ra.idInstructor INNER JOIN ambiente AS a ON a.idAmbiente = ra.idAmbiente WHERE ra.idAmbiente = ? AND inicio >= ? AND fin <= ?";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$idAmbiente, $startDateTime, $endDateTime]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

  function getRoomHistory($idAmbiente)
  {
    $sql = "SELECT ra.*, ud.nombre AS instructor
            FROM registro_ambiente AS ra
            INNER JOIN usuario_detalle AS ud ON ud.idUsuario = ra.idInstructor
            WHERE idAmbiente = ? AND fin IS NULL";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$idAmbiente]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);
  }

  function addRoomHistory($llaves, $controlTv, $controlAir, $idInstructor, $idAmbiente)
  {
    $sqlCheck = "SELECT * FROM registro_ambiente
                WHERE fin IS NULL AND idInstructor = ?
                ORDER BY fin DESC LIMIT 1";
    $stmtCheck = $this->getConn()->prepare($sqlCheck);
    $stmtCheck->execute([$idInstructor]);
    $count = $stmtCheck->rowCount();
    if ($count == 0) {
      $sql = "INSERT INTO registro_ambiente (inicio, llaves, controlTv, controlAire, idInstructor, idAmbiente) VALUES (current_timestamp(), ?, ?, ?, ?, ?)";
      $stmt = $this->getConn()->prepare($sql);
      if ($stmt->execute([$llaves, $controlTv, $controlAir, $idInstructor, $idAmbiente])) {
        $sqlUpdate = "UPDATE ambiente SET estado = 'Ocupada', afluencia = 1 WHERE idAmbiente = ?";
        $stmtUpdate = $this->getConn()->prepare($sqlUpdate);
        $stmtUpdate->execute([$idAmbiente]);

        $icon = $this->functions->getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡Vinculación exitosa!"]);
      } else {
        $icon = $this->functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que algo salió mal."]);
      }
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon El instructor ya tiene una vinculación activa."]);
    }
  }

  function updateRoomHistory($idInstructor, $idAmbiente)
  {
    $sqlGet = "SELECT * FROM registro_ambiente
                WHERE fin IS NULL AND idInstructor = ? AND idAmbiente = ?
                ORDER BY fin DESC LIMIT 1";
    $stmtGet = $this->getConn()->prepare($sqlGet);
    $stmtGet->execute([$idInstructor, $idAmbiente]);
    $row = $stmtGet->fetch(PDO::FETCH_ASSOC);
    $count = $stmtGet->rowCount();
    if ($count > 0) {
      $idHistory = $row['idRegistro'];
      $sql = "UPDATE registro_ambiente SET fin = current_timestamp() WHERE idRegistro = ?";
      $stmt = $this->getConn()->prepare($sql);
      if ($stmt->execute([$idHistory])) {
        $sqlUpdate = "UPDATE ambiente SET estado = 'Disponible', afluencia = 0 WHERE idAmbiente = ?";
        $stmtUpdate = $this->getConn()->prepare($sqlUpdate);
        $stmtUpdate->execute([$idAmbiente]);

        $icon = $this->functions->getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡Desvinculación exitosa!"]);
      } else {
        $icon = $this->functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que algo salía mal."]);
      }
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon El instructor no esta vinculado a este ambiente."]);
    }
  }
}