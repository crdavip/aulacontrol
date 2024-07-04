<?php
require_once('../controller/funciones.php');

class UsuariosDetalles extends ConnPDO {
   
    private $functions;
    public function __construct()
  {
    parent::__construct(); // Llamar al constructor de la clase padre
    $this->functions = new Funciones();
  }

function createUsersDetails($name, $imgQr, $idCenter, $idUser)
{
  $sql = "INSERT INTO usuario_detalle (nombre, imagen, imagenQr, idCentro, idUsuario) VALUES (?, './view/img/users/default.jpg', ?, ?, ?)";
  $stmt = $this->getConn()->prepare($sql);
  if ($stmt->execute([$name, $imgQr, $idCenter, $idUser])) {
    $icon = $this->functions->getIcon('OK');
    echo json_encode(['success' => true, 'message' => "$icon ¡Usuario Creado Exitosamente!"]);
  } else {
    $icon = $this->functions->getIcon('Err');
    echo json_encode(['success' => false, 'message' => "$icon Error al crear el usuario"]);
  }
}

}