<?php
require_once ('../controller/funciones.php');
require_once ('../model/db.php');

class Observaciones extends ConnPDO
{

  private $functions;

  public function __construct()
  {
    parent::__construct();
    $this->functions = new Funciones();
  }

  function getObservations()
  {
    $sql = "SELECT o.*, u.documento, c.detalle, ud.imagen, ud.idCentro, ud.nombre, ro.fechaPublicacion, ro.estado, c.detalle AS detalleCargo FROM observaciones AS o INNER JOIN usuario AS u ON u.idUsuario = o.idUsuario INNER JOIN usuario_detalle AS ud ON ud.idUsuario = o.idUsuario INNER JOIN cargo AS c ON u.idCargo = c.idCargo INNER JOIN registro_observaciones AS ro ON ro.idObservacion = o.idObservacion";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
  }

  public function getObservationsUser($idUser)
  {
    $sql = "SELECT o.*, u.documento, c.detalle, ud.imagen, ud.idCentro, ud.nombre, ro.fechaPublicacion, ro.estado, c.detalle AS detalleCargo FROM observaciones AS o INNER JOIN usuario AS u ON u.idUsuario = o.idUsuario INNER JOIN usuario_detalle AS ud ON ud.idUsuario = o.idUsuario INNER JOIN cargo AS c ON u.idCargo = c.idCargo INNER JOIN registro_observaciones AS ro ON ro.idObservacion = o.idObservacion WHERE o.idUsuario = ?";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$idUser]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
  }

  public function getCountObservations(){
    $sql = "SELECT COUNT(*) AS observations FROM observaciones";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $obs = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($obs);
  }

  function createObservation($descripcion, $idUser, $type)
  {
    $sqlObs = "INSERT INTO observaciones (descripcion, idUsuario, tipoAsunto) VALUES (?, ?, ?)";
    $stmtObs = $this->getConn()->prepare($sqlObs);
    $stmtObs->execute([$descripcion, $idUser, $type]);
    $ultimaId = $this->getConn()->lastInsertId();
    if ($ultimaId) {
      return $ultimaId;
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al crear el objeto"]);
    }
  }

  function updateObject($descripcion, $color, $idUser, $id)
  {
    $sql = "UPDATE objetos SET descripcion = ?, color = ?, idUsuario = ? WHERE idObjeto = ?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$descripcion, $color, $idUser, $id])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Objeto Actualizado Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al actualizar el objeto"]);
    }
  }

  function deleteObject($id)
  {
    $sql = "DELETE FROM objetos WHERE idObjeto = ?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$id])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Objeto Eliminado Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al eliminar el objeto"]);
    }
  }
}