<?php
require_once ('../controller/funciones.php');

class RegistroEquipos extends ConnPDO
{

  private $functions;

  public function __construct()
  {
    parent::__construct(); // Llamar al constructor de la clase padre
    $this->functions = new Funciones();
  }

  function getAllDevicesHistory()
  {
    $sql = "SELECT rc.idRegistro, ud.nombre AS usuario, ud.imagen, u.documento, c.ref AS referencia, c.marca, cent.siglas AS centro, a.numero as ambiente, rc.inicio, rc.fin
            FROM registro_computador AS rc
            INNER JOIN usuario_detalle AS ud ON ud.idUsuario = rc.idUsuario
            INNER JOIN usuario AS u ON u.idUsuario = rc.idUsuario
            INNER JOIN computador AS c ON c.idComputador = rc.idComputador
            INNER JOIN ambiente AS a ON a.idAmbiente = c.idAmbiente
            INNER JOIN centro AS cent ON cent.idCentro = a.idCentro
            WHERE a.numero != 'Mesa Ayuda' AND cent.idCentro = ?
            ORDER BY idRegistro DESC";

    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$_SESSION['idCenter']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
  }

  function getAllHelpDevicesHistory()
  {
    $sql = "SELECT rc.idRegistro, ud.nombre AS usuario, ud.imagen, u.documento, c.ref AS referencia, c.marca, cent.siglas AS centro, a.numero as ambiente, rc.inicio, rc.fin
            FROM registro_computador AS rc
            INNER JOIN usuario_detalle AS ud ON ud.idUsuario = rc.idUsuario
            INNER JOIN usuario AS u ON u.idUsuario = rc.idUsuario
            INNER JOIN computador AS c ON c.idComputador = rc.idComputador
            INNER JOIN ambiente AS a ON a.idAmbiente = c.idAmbiente
            INNER JOIN centro AS cent ON cent.idCentro = a.idCentro
            WHERE a.numero = 'Mesa Ayuda' AND cent.idCentro = ?
            ORDER BY idRegistro DESC";

    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$_SESSION['idCenter']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
  }

  function getDeviceHistory($idComputador)
  {
    $sql = "SELECT rc.*, ud.nombre AS usuario
            FROM registro_computador AS rc
            INNER JOIN usuario_detalle AS ud ON ud.idUsuario = rc.idUsuario
            WHERE idComputador = ? AND fin IS NULL";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$idComputador]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);
  }

  function getGroupOfHistory($idAmbiente, $startDateTime, $endDateTime)
  {
    $sql = "SELECT re.*, a.numero, ud.nombre AS usuario, u.documento AS documento, c.ref, c.marca FROM registro_computador AS re INNER JOIN usuario_detalle AS ud ON ud.idUsuario = re.idUsuario INNER JOIN computador AS c ON c.idComputador = re.idComputador INNER JOIN ambiente AS a ON a.idAmbiente = c.idAmbiente INNER JOIN usuario AS u ON u.idUsuario = re.idUsuario WHERE c.idAmbiente = ? AND inicio >= ? AND fin <= ? ORDER BY re.idRegistro DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$idAmbiente, $startDateTime, $endDateTime]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
  }

  function addDeviceHistory($idUser, $idDevice)
  {
    $sqlCheck = "SELECT * FROM registro_computador
                WHERE fin IS NULL AND idUsuario = ?
                ORDER BY fin DESC LIMIT 1";
    $stmtCheck = $this->getConn()->prepare($sqlCheck);
    $stmtCheck->execute([$idUser]);
    $count = $stmtCheck->rowCount();
    if ($count == 0) {
      $sql = "INSERT INTO registro_computador (inicio, idUsuario, idComputador) VALUES (current_timestamp(), ?, ?)";
      $stmt = $this->getConn()->prepare($sql);
      if ($stmt->execute([$idUser, $idDevice])) {
        $sqlUpdate = "UPDATE computador SET estado = 'Ocupado' WHERE idComputador = ?";
        $stmtUpdate = $this->getConn()->prepare($sqlUpdate);
        $stmtUpdate->execute([$idDevice]);

        $icon = $this->functions->getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡Vinculación exitosa!"]);
      } else {
        $icon = $this->functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que algo salió mal."]);
      }
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon El usuario ya tiene una vinculación activa."]);
    }
  }

  function addDeviceHistoryAlt($idDevice, $idUser)
  {
    $sqlCheck = "SELECT * FROM registro_computador
                WHERE fin IS NULL AND idUsuario = ?
                ORDER BY fin DESC LIMIT 1";
    $stmtCheck = $this->getConn()->prepare($sqlCheck);
    $stmtCheck->execute([$idUser]);
    $count = $stmtCheck->rowCount();
    if ($count == 0) {
      $sql = "INSERT INTO registro_computador (inicio, fin, idUsuario, idComputador) VALUES (current_timestamp(), current_timestamp(), ?, ?)";
      $stmt = $this->getConn()->prepare($sql);
      if ($stmt->execute([$idUser, $idDevice])) {
        $icon = $this->functions->getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡Vinculación exitosa!"]);
      } else {
        $icon = $this->functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que algo salió mal."]);
      }
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon El usuario ya tiene una vinculación activa."]);
    }
  }

  function updateDeviceHistory($idUser, $idDevice)
  {
    $sqlGet = "SELECT * FROM registro_computador
                WHERE fin IS NULL AND idUsuario = ? AND idComputador = ?
                ORDER BY fin DESC LIMIT 1";
    $stmtGet = $this->getConn()->prepare($sqlGet);
    $stmtGet->execute([$idUser, $idDevice]);
    $row = $stmtGet->fetch(PDO::FETCH_ASSOC);
    $count = $stmtGet->rowCount();
    if ($count > 0) {
      $idHistory = $row['idRegistro'];
      $sql = "UPDATE registro_computador SET fin = current_timestamp() WHERE idRegistro = ?";
      $stmt = $this->getConn()->prepare($sql);
      if ($stmt->execute([$idHistory])) {
        $sqlUpdate = "UPDATE computador SET estado = 'Disponible' WHERE idComputador = ?";
        $stmtUpdate = $this->getConn()->prepare($sqlUpdate);
        $stmtUpdate->execute([$idDevice]);

        $icon = $this->functions->getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡Desvinculación exitosa!"]);
      } else {
        $icon = $this->functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que algo salía mal."]);
      }
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon El usuario no esta vinculado a este equipo."]);
    }
  }
}