<?php
require_once ('../controller/funciones.php');

class Usuarios extends ConnPDO
{

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

  function getDocUserAssoc2($doc)
  {
    $sqlDoc = 'SELECT * FROM usuario WHERE documento = ? AND (idCargo = 2 OR idCargo = 3)';
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

  function getDocCount($doc)
  {
    $sqlCheck = ("SELECT documento FROM usuario WHERE documento=? ");
    $stmtCheck = $this->getConn()->prepare($sqlCheck);
    $stmtCheck->execute([$doc]);
    return $stmtCheck->rowCount();
  }

  function getUsers()
  {
    $sql = "SELECT u.idUsuario, u.documento, u.estado, ud.nombre, ud.imagen, ud.imagenQr, ud.idCentro, ud.correo, c.siglas, rol.idCargo, rol.detalle AS cargo
          FROM usuario AS u
          INNER JOIN usuario_detalle AS ud ON u.idUsuario = ud.idUsuario
          INNER JOIN centro AS c ON ud.idCentro = c.idCentro
          INNER JOIN cargo AS rol ON u.idCargo = rol.idCargo
          WHERE c.idCentro = ?
          ORDER BY u.idUsuario DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$_SESSION['idCenter']]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
  }

  function getByRole($role, $center)
  {
    $sql = "SELECT COUNT(*) AS cantidad_usuarios
      FROM usuario AS u
      INNER JOIN usuario_detalle AS ud ON u.idUsuario = ud.idUsuario
      INNER JOIN centro AS c ON ud.idCentro = c.idCentro
      INNER JOIN cargo AS rol ON u.idCargo = rol.idCargo
      WHERE c.idCentro = ? 
      AND rol.detalle = ?
      AND u.estado = 'Activo'
    ";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$center, $role]);
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($count);
  }

  function getUsersForSearching($doc)
  {
    $sql = "SELECT u.documento, u.idUsuario, u.estado, ud.imagen, ud.nombre FROM usuario AS u INNER JOIN usuario_detalle AS ud ON ud.idUsuario = u.idUsuario WHERE documento LIKE ?";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute(["$doc%"]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
  }

  // Al momento de utilizar el sistema para todo el complejo se requiere mostrar solo los usuarios que pertenezcan al mismo centro del instructor.
  function getUsersForSearchingAdd($doc)
  {
    $sql = "SELECT u.documento, u.idUsuario, u.estado, ud.imagen, ud.nombre
            FROM usuario AS u
            INNER JOIN usuario_detalle AS ud ON ud.idUsuario = u.idUsuario
            LEFT JOIN centro AS c ON c.idCentro = ud.idCentro
            LEFT JOIN aprendices AS a ON u.idUsuario = a.idUsuario
            LEFT JOIN ficha AS f ON a.idFicha = f.idFicha
            WHERE u.idCargo = 3 AND u.estado = 'Activo' AND u.documento LIKE ?
            AND c.idCentro = ?
            ";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute(["$doc%"]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
  }

  function getTraineesAvailables($center)
  {
    $sql = "SELECT u.documento, u.idUsuario, u.estado, ud.imagen, ud.nombre
            FROM usuario AS u
            INNER JOIN usuario_detalle AS ud ON ud.idUsuario = u.idUsuario
            LEFT JOIN centro AS c ON c.idCentro = ud.idCentro
            LEFT JOIN aprendices AS a ON u.idUsuario = a.idUsuario
            LEFT JOIN ficha AS f ON a.idFicha = f.idFicha
            WHERE u.idCargo = 3
            AND u.estado = 'Activo'
            AND (a.idUsuario IS NULL OR f.estado != 'Activa')
            AND c.idCentro = ?";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$center]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
  }

  function getUsersExport()
  {
    $sql = "SELECT u.idUsuario, u.documento, u.estado, ud.nombre, ud.imagen, ud.idCentro, ud.correo, c.siglas, rol.idCargo, rol.detalle AS cargo
          FROM usuario AS u
          INNER JOIN usuario_detalle AS ud ON u.idUsuario = ud.idUsuario
          INNER JOIN centro AS c ON ud.idCentro = c.idCentro
          INNER JOIN cargo AS rol ON u.idCargo = rol.idCargo
          WHERE c.idCentro = ?
          ORDER BY u.idUsuario DESC";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$_SESSION['idCenter']]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users;
  }

  function createUsers($doc, $pass, $idRole)
  {
    $sql = "INSERT INTO usuario (documento, contrasena, idCargo) VALUES (?, ?, ?)";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$doc, $pass, $idRole])) {
      $ultimaId = $this->getConn()->lastInsertId();
      return $ultimaId;
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al crear el usuario"]);
    }
  }

  function editUsers($idUser, $doc, $idRole)
  {
    $sql = "UPDATE usuario SET documento=?, idCargo=? WHERE idUsuario=?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$doc, $idRole, $idUser])) {
      $ultimaId = $this->getConn()->lastInsertId();
      return $ultimaId;
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al editar el usuario"]);
    }
  }

  function editUsersImport($doc, $idRole)
  {
    $sql = "UPDATE usuario SET documento=?, idCargo=? WHERE documento=?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$doc, $idRole, $doc])) {
      $sqlIdUser = "SELECT idUsuario FROM usuario WHERE documento=?";
      $stmtIdUser = $this->getConn()->prepare($sqlIdUser);
      $stmtIdUser->execute([$doc]);
      $row = $stmtIdUser->fetch(PDO::FETCH_ASSOC);
      return $idUser = $row['idUsuario'];
    } else {
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al editar el usuario"]);
    }
  }

  function updatePass($idUser, $pass)
  {
    $sql = "UPDATE usuario SET contrasena=?, token=NULL, olvideContra='No', nuevo='No' WHERE idUsuario=?";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute([$pass, $idUser])) {
      $_SESSION['success'] = true;
    } else {
      $_SESSION['success'] = false;
    }
  }

  function deleteUsers($idUser)
  {
    $this->getConn()->beginTransaction();

    try {
      $checkSql = "SELECT idFicha FROM aprendices WHERE idUsuario = ?";
      $checkStmt = $this->getConn()->prepare($checkSql);
      $checkStmt->execute([$idUser]);
      $ficha = $checkStmt->fetch(PDO::FETCH_ASSOC);

      if ($ficha) {
        $idFicha = $ficha['idFicha'];
        $updateFichaSql = "UPDATE ficha SET aprendices = aprendices - 1 WHERE idFicha = ?";
        $updateFichaStmt = $this->getConn()->prepare($updateFichaSql);
        $updateFichaStmt->execute([$idFicha]);
        $deleteAprendicesSql = "DELETE FROM aprendices WHERE idUsuario = ?";
        $deleteAprendicesStmt = $this->getConn()->prepare($deleteAprendicesSql);
        $deleteAprendicesStmt->execute([$idUser]);
      }
      $deleteUserSql = "DELETE FROM usuario WHERE idUsuario = ?";
      $deleteUserStmt = $this->getConn()->prepare($deleteUserSql);
      $deleteUserStmt->execute([$idUser]);
      $this->getConn()->commit();
    } catch (Exception $e) {
      $this->getConn()->rollBack();
      $icon = $this->functions->getIcon('Err');
      echo json_encode(['success' => false, 'message' => "$icon Error al eliminar el usuario"]);
    }
  }
}
