<?php
require_once ('../controller/funciones.php');
require_once ('../model/db.php');
require_once ('../model/ambientes.php');

class Equipos extends ConnPDO
{

  private $functions;

  public function __construct()
  {
    parent::__construct(); // Llamar al constructor de la clase padre
    $this->functions = new Funciones();
  }

  function getDevices()
  {
    $sql = "SELECT c.idComputador, c.ref, c.marca, c.estado, a.numero AS ambiente, ce.siglas AS centro
            FROM computador AS c
            INNER JOIN ambiente AS a ON a.idAmbiente = c.idAmbiente
            INNER JOIN centro AS ce ON ce.idCentro = a.idCentro
            WHERE a.numero != 'Mesa Ayuda' AND ce.detalle = ?
            ORDER BY c.idComputador DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$_SESSION['center']]);
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($devices);
  }

  function getHelpDevices()
  {
    $sql = "SELECT c.idComputador, c.ref, c.marca, c.estado, a.numero AS ambiente, ce.siglas AS centro
            FROM computador AS c
            INNER JOIN ambiente AS a ON a.idAmbiente = c.idAmbiente
            INNER JOIN centro AS ce ON ce.idCentro = a.idCentro
            WHERE a.numero = 'Mesa Ayuda' AND ce.detalle = ?
            ORDER BY c.idComputador DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$_SESSION['center']]);
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($devices);
  }

  function getDevicesExport($idAmbiente) {
    $sql = "SELECT c.idComputador, c.ref, c.marca, c.estado, a.numero AS ambiente FROM computador AS c INNER JOIN ambiente AS a ON a.idAmbiente = c.idAmbiente WHERE c.idAmbiente = ? ORDER BY c.ref DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$idAmbiente]);
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $devices;
  }

  function createDevice($reference, $brand, $stateDevice, $imageQr, $room)
  {
    $sql = "INSERT INTO computador (ref, marca, estado, imagenQr, idAmbiente) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$reference, $brand, $stateDevice, $imageQr, $room])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Equipo Creado Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al crear el equipo"]);
    }
  }

  function updateDevice($reference, $brand, $room, $id)
  {
    $sql = "UPDATE computador SET ref = ?, marca = ?, idAmbiente = ? WHERE idComputador = ?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$reference, $brand, $room, $id])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Equipo Actualizado Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al actualizar el equipo"]);
    }
  }

  function deleteDevice($id)
  {
    $sql = "DELETE FROM computador WHERE idComputador = ?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$id])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Equipo Eliminado Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al eliminar el equipo"]);
    }
  }
}