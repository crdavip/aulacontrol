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
            ORDER BY ro.estado, ro.idRegistro DESC";

    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$_SESSION['idCenter']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
  }

  function getGroupOfHistoryOnlyByDate($startDate, $endDate)
  {
    $sql = "SELECT ro.*, ud.nombre AS usuario, u.documento AS documento, rd.nombre AS nombreRevisor, r.documento AS docRevisor, o.*, c.detalle AS centro FROM registro_observaciones AS ro INNER JOIN observaciones AS o ON ro.idObservacion = o.idObservacion INNER JOIN usuario_detalle AS ud ON ud.idUsuario = o.idUsuario INNER JOIN usuario AS u ON u.idUsuario = o.idUsuario INNER JOIN centro AS c ON c.idCentro = ud.idCentro LEFT JOIN usuario_detalle AS rd ON rd.idUsuario = ro.idRevisador LEFT JOIN usuario AS r ON r.idUsuario = ro.idRevisador WHERE ro.fechaPublicacion >= ? AND fechaPublicacion <= ? ORDER BY ro.estado, ro.idRegistro DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$startDate, $endDate]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
  }

  function getGroupOfHistoryByState($startDate, $endDate, $state)
  {
    $sql = "SELECT ro.*, ud.nombre AS usuario, u.documento AS documento, rd.nombre AS nombreRevisor, r.documento AS docRevisor, o.*, c.detalle AS centro FROM registro_observaciones AS ro INNER JOIN observaciones AS o ON ro.idObservacion = o.idObservacion INNER JOIN usuario_detalle AS ud ON ud.idUsuario = o.idUsuario INNER JOIN usuario AS u ON u.idUsuario = o.idUsuario INNER JOIN centro AS c ON c.idCentro = ud.idCentro LEFT JOIN usuario_detalle AS rd ON rd.idUsuario = ro.idRevisador LEFT JOIN usuario AS r ON r.idUsuario = ro.idRevisador WHERE ro.fechaPublicacion >= ? AND fechaPublicacion <= ? AND ro.estado = ? ORDER BY ro.idRegistro DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$startDate, $endDate, $state]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
  }

  function getGroupOfHistoryByType($startDate, $endDate, $typeObs)
  {
    $sql = "SELECT ro.*, ud.nombre AS usuario, u.documento AS documento, rd.nombre AS nombreRevisor, r.documento AS docRevisor, o.*, c.detalle AS centro FROM registro_observaciones AS ro INNER JOIN observaciones AS o ON ro.idObservacion = o.idObservacion INNER JOIN usuario_detalle AS ud ON ud.idUsuario = o.idUsuario INNER JOIN usuario AS u ON u.idUsuario = o.idUsuario INNER JOIN centro AS c ON c.idCentro = ud.idCentro LEFT JOIN usuario_detalle AS rd ON rd.idUsuario = ro.idRevisador LEFT JOIN usuario AS r ON r.idUsuario = ro.idRevisador WHERE ro.fechaPublicacion >= ? AND fechaPublicacion <= ? AND o.tipoAsunto = ? ORDER BY ro.estado, ro.idRegistro DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$startDate, $endDate, $typeObs]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
  }

  function getGroupOfHistoryByDateTypeState($startDate, $endDate, $state, $typeObs)
  {
    $sql = "SELECT ro.*, ud.nombre AS usuario, u.documento AS documento, rd.nombre AS nombreRevisor, r.documento AS docRevisor, o.*, c.detalle AS centro FROM registro_observaciones AS ro INNER JOIN observaciones AS o ON ro.idObservacion = o.idObservacion INNER JOIN usuario_detalle AS ud ON ud.idUsuario = o.idUsuario INNER JOIN usuario AS u ON u.idUsuario = o.idUsuario INNER JOIN centro AS c ON c.idCentro = ud.idCentro LEFT JOIN usuario_detalle AS rd ON rd.idUsuario = ro.idRevisador LEFT JOIN usuario AS r ON r.idUsuario = ro.idRevisador WHERE ro.fechaPublicacion >= ? AND fechaPublicacion <= ? AND o.tipoAsunto = ? AND ro.estado = ? ORDER BY ro.idRegistro DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$startDate, $endDate, $typeObs, $state]);
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
      $date = date('Y-m-d');
      $sql = "UPDATE registro_observaciones SET fechaRevision = ?, estado = 1, idRevisador = ? WHERE idObservacion = ?";
      $stmt = $this->getConn()->prepare($sql);
      $stmt->execute([$date, $idUser, $idObs]);

      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon Observacion marcada como revisada."]);
    } catch (PDOException $e) {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error de base de datos: " . $e->getMessage()]);
    }
  }
}