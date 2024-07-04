<?php
require_once('../controller/funciones.php');

class Usuarios extends ConnPDO {
   
    private $functions;
    public function __construct()
  {
    parent::__construct(); // Llamar al constructor de la clase padre
    $this->functions = new Funciones();
  }

  function getDocUserAssoc($doc)
{
    $sqlDoc = 'SELECT * FROM usuario WHERE documento = ? AND idCargo = 2';
    $stmtDoc = $this->getConn()->prepare($sqlDoc);
    $stmtDoc->execute([$doc]);
    $count = $stmtDoc->rowCount();
    $row = $stmtDoc->fetch(PDO::FETCH_ASSOC);
    if ($count > 0) {
        $sql = 'SELECT u.idUsuario, u.documento, ud.nombre, ud.imagen, c.detalle AS cargo
                    FROM usuario AS u
                    INNER JOIN usuario_detalle AS ud ON u.idUsuario = ud.idUsuario
                    INNER JOIN cargo AS c ON c.idCargo = u.idCargo
                    WHERE documento = ?';
        $stmt = $this->getConn()->prepare($sql);
        $stmt->execute([$doc]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'user' => $row]);
    } else {
        $icon = $this->functions->getIcon('Err');
        echo json_encode(['success' => false, 'message' => "$icon ¡El usuario no es valido!"]);
    }
}

function getUsers(){
  $sql = "SELECT u.idUsuario, u.documento, u.estado, ud.nombre, ud.imagen, c.siglas, rol.detalle AS cargo
          FROM usuario AS u
          INNER JOIN usuario_detalle AS ud ON u.idUsuario = ud.idUsuario
          INNER JOIN centro AS c ON ud.idCentro = c.idCentro
          INNER JOIN cargo AS rol ON u.idCargo = rol.idCargo";
  $stmt = $this->getConn()->prepare($sql);
  $stmt->execute([]);
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($users);
}
}