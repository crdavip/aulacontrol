<?php
require_once ('../controller/funciones.php');
require_once ('../model/db.php');

class Objetos extends ConnPDO
{

  private $functions;

  public function __construct()
  {
    parent::__construct();
    $this->functions = new Funciones();
  }

  function getObjects()
  {
    $sql = "SELECT * FROM objetos";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($objects);
  }

  function createObject($descripcion, $color)
  {

    $sql = "INSERT INTO objetos (descripcion, color) VALUES (?, ?)";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$descripcion, $color])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Objeto Creado Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al crear el objeto"]);
    }
  }

  function updateObject($descripcion, $color, $id)
  {
    $sql = "UPDATE objetos SET descripcion = ?, color = ? WHERE idObjeto = ?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$descripcion, $color, $id])) {
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