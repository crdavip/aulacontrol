<?php
require_once ('../controller/funciones.php');

class RegistroObjetos extends ConnPDO
{

  private $functions;

  public function __construct()
  {
    parent::__construct();
    $this->functions = new Funciones();
  }

  function getAllObjectsHistory()
  {
    $sql = "SELECT ro.idRegistro, ud.nombre AS usuario, ud.imagen, u.documento, o.idObjeto, o.descripcion, o.color, cent.siglas AS centro, ro.inicio, ro.fin, ro.idCentro FROM registro_objeto AS ro INNER JOIN objetos AS o ON o.idObjeto = ro.idObjeto INNER JOIN usuario_detalle AS ud ON ud.idUsuario = o.idUsuario INNER JOIN usuario AS u ON u.idUsuario = o.idUsuario INNER JOIN centro AS cent ON cent.idCentro = ro.idCentro ORDER BY idRegistro DESC";

    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
  }

  function getObjectHistory($idObject)
  {
    $sql = "SELECT ro.idRegistro, ud.nombre AS usuario, ud.imagen, u.documento, o.idObjeto, o.descripcion, o.color, cent.siglas AS centro, ro.inicio, ro.fin FROM registro_objeto AS ro INNER JOIN objetos AS o ON o.idObjeto = ro.idObjeto INNER JOIN usuario_detalle AS ud ON ud.idUsuario = o.idUsuario INNER JOIN usuario AS u ON u.idUsuario = o.idUsuario INNER JOIN centro AS cent ON cent.idCentro = ro.idCentro WHERE ro.idObjeto = ? ORDER BY idRegistro AND fin IS NULL";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$idObject]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);
  }

  function addObjectHistory($idObject, $idCenter)
  {
    $sqlCheck = "SELECT * FROM registro_objeto
                WHERE fin IS NULL AND idObjeto = ?
                ORDER BY fin DESC LIMIT 1";
    $stmtCheck = $this->getConn()->prepare($sqlCheck);
    $stmtCheck->execute([$idObject]);
    $count = $stmtCheck->rowCount();
    if ($count == 0) {
      $sql = "INSERT INTO registro_objeto (inicio, idObjeto, idCentro) VALUES (current_timestamp(), ?, ?)";
      $stmt = $this->getConn()->prepare($sql);
      if ($stmt->execute([$idObject, $idCenter])) {
        $sqlUpdate = "UPDATE objeto SET estado = 'Activo' WHERE id = ?";
        $stmtUpdate = $this->getConn()->prepare($sqlUpdate);
        $stmtUpdate->execute([$idObject]);

        $icon = $this->functions->getIcon('OK');
        echo json_encode(['success' => true, 'message' => "$icon ¡Registro exitoso!"]);
      } else {
        $icon = $this->functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡Oops! Parece que algo salió mal."]);
      }
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Parece que hay un registro activo."]);
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