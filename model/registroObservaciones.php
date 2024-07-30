<?php
require_once ('../controller/funciones.php');

class RegistroObservaciones extends ConnPDO
{
  private $functions;

  public function __construct()
  {
    parent::__construct();
    $this->functions = new Funciones();
  }

  function getAllObservationsHistory()
  {
    $sql = "SELECT ro.*, o.*, ud.nombre AS nombreUsuario, ud.imagen AS imgUsuario, u.documento AS docuUsuario, rd.nombre AS nombreRevisor, r.documento AS docRevisor, o.descripcion
            FROM registro_observaciones AS ro
            INNER JOIN observaciones AS o ON o.idObservacion = ro.idObservacion
            INNER JOIN usuario_detalle AS ud ON ud.idUsuario = o.idUsuario
            INNER JOIN usuario AS u ON u.idUsuario = o.idUsuario
            LEFT JOIN usuario_detalle AS rd ON rd.idUsuario = ro.idRevisador
            LEFT JOIN usuario AS r ON r.idUsuario = ro.idRevisador
            INNER JOIN centro AS ce ON ce.idCentro = ud.idCentro
            WHERE ce.idCentro = ?
            ORDER BY ro.idRegistro DESC";

    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$_SESSION['idCenter']]);
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

  function getGroupOfHistory($idCenter, $startDateTime, $endDateTime)
  {
    $sql = "SELECT ro.*, c.siglas AS centro, ud.nombre AS usuario, u.documento AS documento, o.descripcion, o.color FROM registro_objeto AS ro INNER JOIN objetos AS o ON ro.idObjeto = o.idObjeto INNER JOIN usuario_detalle AS ud ON ud.idUsuario = o.idUsuario INNER JOIN centro AS c ON c.idCentro = c.idCentro INNER JOIN usuario AS u ON u.idUsuario = o.idUsuario WHERE c.idCentro = ? AND inicio >= ? AND fin <= ? ORDER BY ro.idRegistro DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$idCenter, $startDateTime, $endDateTime]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
  }

  function addObservationHistory($idObservation)
  {
    $sql = "INSERT INTO registro_observaciones (fechaPublicacion, fechaRevision, estado, idObservacion, idRevisador) VALUES (CURDATE(), NULL, 0, ?, NULL)";
    $stmtCheck = $this->getConn()->prepare($sql);
    if ($stmtCheck->execute([$idObservation])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Registro de Observación éxitoso!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon No se pudo guardar la observación."]);
    }
  }

  function updateObservationHistory($idUser, $idObs)
  {
    try {
      $sql = "UPDATE registro_observaciones SET fechaRevision = CURDATE(), estado = 1, idRevisador = ? WHERE idObservacion = ?";
      $stmt = $this->getConn()->prepare($sql);
      $stmt->execute([$idUser, $idObs]);

      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon Observacion marcada como revisada."]);
    } catch (PDOException $e) {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error de base de datos: " . $e->getMessage()]);
    }
  }
}