<?php
require_once ('../controller/funciones.php');
require_once ('../model/db.php');
require_once ('../model/registroObjetos.php');

class Objetos extends ConnPDO
{

  private $functions;
  private $regObjects;

  public function __construct()
  {
    parent::__construct();
    $this->functions = new Funciones();
    $this->regObjects = new RegistroObjetos();
  }

  function getObjects()
  {
    $sql = "SELECT o.*, u.documento FROM objetos AS o INNER JOIN usuario AS u on u.idUsuario = o.idUsuario";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($objects);
  }

  function createObject($descripcion, $color, $idUser, $idCenter)
  {

    $sql = "INSERT INTO objetos (descripcion, color, estado, idUsuario) VALUES (?, ?, 'Activo', ?)";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$descripcion, $color, $idUser])) {
      $idObjectCreated = $this->getConn()->lastInsertId();
      $this->regObjects->addObjectHistory($idObjectCreated, $idCenter);
      // $icon = $this->functions->getIcon('OK');
      // echo json_encode(['success' => true, 'message' => "$icon ¡Objeto Creado Exitosamente!"]);
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

  // function updateStateObject($state, $id) {
  //   $sql = "UPDATE objetos SET estado = ? WHERE idObjeto = ?";
  //   $stmt = $this->getConn()->prepare($sql);
  //   if ($stmt->execute([$state, $id])) {
  //     $icon = $this->functions->getIcon('OK');
  //     echo json_encode(['success' => true, 'message' => "$icon ¡Objeto Actualizado Exitosamente!"]);
  //   } else {
  //     $icon = $this->functions->getIcon('Err');
  //     echo json_encode(['success' => false, 'message' => "$icon Error al actualizar el objeto"]);
  //   }
  // }

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