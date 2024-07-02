<?php
require_once ('../controller/funciones.php');
require_once ('../model/db.php');

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
    $sql = "SELECT c.idComputador, c.ref, c.marca, c.estado, a.numero AS ambiente FROM computador AS c INNER JOIN ambiente AS a ON a.idAmbiente = c.idAmbiente ORDER BY c.idAmbiente";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($devices);
  }

  function createDevice($reference, $brand, $stateDevice, $room)
  {
    $sql = "INSERT INTO computador (ref, marca, estado, idAmbiente) VALUES (?, ?, ?, ?)";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$reference, $brand, $stateDevice, $room])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Equipo Creado Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al crear el equipo"]);
    }
  }

  function updateDevice($reference, $brand, $stateDevice, $room, $id)
  {
    $sql = "UPDATE computador SET ref = ?, marca = ?, estado = ?, idAmbiente = ? WHERE idComputador = ?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$reference, $brand, $stateDevice, $room, $id])) {
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