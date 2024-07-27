<?php
require_once('../controller/funciones.php');

class UsuariosDetalles extends ConnPDO
{

  private $functions;
  public function __construct()
  {
    parent::__construct(); // Llamar al constructor de la clase padre
    $this->functions = new Funciones();
  }

  function createUsersDetails($name, $email, $imgQr, $idCenter, $idUser)
  {
    $sql = "INSERT INTO usuario_detalle (nombre, correo, imagen, imagenQr, idCentro, idUsuario) VALUES (?, ?, './view/img/users/default.jpg', ?, ?, ?)";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$name, $email, $imgQr, $idCenter, $idUser])) {
      $_SESSION['success'] = true;
    } else {
      $_SESSION['success'] = false;
    }
  }

  function editUsersDetails($name, $mail, $idCenter, $idUser)
  {
    $sql = "UPDATE usuario_detalle SET nombre=?, correo=?, idCentro=? WHERE idUsuario=?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$name, $mail, $idCenter, $idUser])) {
      $_SESSION['success'] = true;
    } else {
      $_SESSION['success'] = false;
    }
  }

  function updateUserDetails($img, $birth, $genre, $idUser)
  {
    $sql = "UPDATE usuario_detalle SET imagen=?, nacimiento=?, genero=? WHERE idUsuario=?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$img, $birth, $genre, $idUser])) {
      $_SESSION['success'] = true;
    } else {
      $_SESSION['success'] = false;
    }
  }

  function deleteUsersDetails($idUser)
  {
    $sql = "DELETE FROM usuario_detalle WHERE idUsuario=?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$idUser])) {
      $icon = $this->functions->getIcon('OK');
      echo json_encode(['success' => true, 'message' => "$icon ¡Usuario Eliminado Exitosamente!"]);
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al eliminar el usuario"]);
    }
  }
}
