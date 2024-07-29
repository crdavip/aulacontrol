<?php
require_once ('../controller/funciones.php');

class RegistroAsistencia extends ConnPDO
{
  private $functions;
  public function __construct()
  {
    parent::__construct();
    $this->functions = new Funciones();
  }

  public function getAllRegsByCenter($center)
  {
    $sql = "SELECT 
      a.*,
      ra.idRegistro, 
      f.ficha AS numeroFicha, 
      f.aprendices AS totalAprendices, 
      COUNT(ra.idUsuario) AS presentes, 
      GROUP_CONCAT(ra.idUsuario SEPARATOR ',') AS usuarios, 
      GROUP_CONCAT(ud.nombre SEPARATOR ', ') AS nombresUsuarios, 
      GROUP_CONCAT(u.documento SEPARATOR ', ') AS docUsuarios, 
      am.numero AS ambiente,
      i.nombre AS instructorNombre,
      iu.documento AS instructorDocumento
      FROM registro_asistencia ra 
      JOIN asistencia a ON ra.idAsistencia = a.idAsistencia 
      JOIN ficha f ON a.idFicha = f.idFicha 
      JOIN usuario_detalle ud ON ra.idUsuario = ud.idUsuario 
      JOIN usuario u ON ud.idUsuario = u.idUsuario 
      JOIN ambiente am ON a.idAmbiente = am.idAmbiente 
      LEFT JOIN usuario_detalle i ON a.idInstructor = i.idUsuario
      LEFT JOIN usuario iu ON i.idUsuario = iu.idUsuario
      WHERE am.idCentro = ?
      GROUP BY a.idAsistencia, f.ficha, f.aprendices, i.nombre, iu.documento
    ";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$center]);
    $regsAssist = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($regsAssist, JSON_UNESCAPED_UNICODE);
  }

  public function getAllRegsByCenterAndSheet($center, $sheet)
  {
    $sql = "SELECT 
      a.*,
      ra.idRegistro, 
      f.ficha AS numeroFicha, 
      f.aprendices AS totalAprendices, 
      COUNT(ra.idUsuario) AS presentes, 
      GROUP_CONCAT(ra.idUsuario SEPARATOR ',') AS usuarios, 
      GROUP_CONCAT(ud.nombre SEPARATOR ', ') AS nombresUsuarios, 
      GROUP_CONCAT(u.documento SEPARATOR ', ') AS docUsuarios, 
      am.numero AS ambiente,
      i.nombre AS instructorNombre,
      i.imagen,
      iu.documento AS instructorDocumento
      FROM registro_asistencia ra 
      JOIN asistencia a ON ra.idAsistencia = a.idAsistencia 
      JOIN ficha f ON a.idFicha = f.idFicha 
      JOIN usuario_detalle ud ON ra.idUsuario = ud.idUsuario 
      JOIN usuario u ON ud.idUsuario = u.idUsuario 
      JOIN ambiente am ON a.idAmbiente = am.idAmbiente 
      LEFT JOIN usuario_detalle i ON a.idInstructor = i.idUsuario
      LEFT JOIN usuario iu ON i.idUsuario = iu.idUsuario
      WHERE am.idCentro = ? AND a.idFicha = ?
      GROUP BY a.idAsistencia, f.ficha, f.aprendices, i.nombre, iu.documento
    ";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$center, $sheet]);
    $regsAssist = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($regsAssist, JSON_UNESCAPED_UNICODE);
  }

  public function getRegAssistById ($idAsistencia) {
    $sql = "SELECT 
      a.*,
      ra.idRegistro, 
      f.ficha AS numeroFicha, 
      f.aprendices AS totalAprendices, 
      COUNT(ra.idUsuario) AS presentes, 
      GROUP_CONCAT(ra.idUsuario SEPARATOR ',') AS usuarios, 
      GROUP_CONCAT(ud.nombre SEPARATOR ', ') AS nombresUsuarios, 
      GROUP_CONCAT(u.documento SEPARATOR ', ') AS docUsuarios, 
      am.numero AS ambiente,
      i.nombre AS instructorNombre,
      i.imagen,
      iu.documento AS instructorDocumento
      FROM registro_asistencia ra 
      JOIN asistencia a ON ra.idAsistencia = a.idAsistencia 
      JOIN ficha f ON a.idFicha = f.idFicha 
      JOIN usuario_detalle ud ON ra.idUsuario = ud.idUsuario 
      JOIN usuario u ON ud.idUsuario = u.idUsuario 
      JOIN ambiente am ON a.idAmbiente = am.idAmbiente 
      LEFT JOIN usuario_detalle i ON a.idInstructor = i.idUsuario
      LEFT JOIN usuario iu ON i.idUsuario = iu.idUsuario
      WHERE ra.idAsistencia = ?
      GROUP BY a.idAsistencia, f.ficha, f.aprendices, i.nombre, iu.documento
    ";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$idAsistencia]);
    $regsAssist = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($regsAssist, JSON_UNESCAPED_UNICODE);
  }

  public function getGroupOfHistoryAssist($idSheet, $idInstructor) {
    $sql = "SELECT 
      a.*,
      ra.idRegistro, 
      f.ficha AS numeroFicha, 
      f.aprendices AS totalAprendices, 
      COUNT(ra.idUsuario) AS presentes, 
      GROUP_CONCAT(
          CONCAT(
              ud.nombre, ' (', u.documento, ')'
          ) SEPARATOR ', '
      ) AS aprendices, 
      am.numero AS ambiente,
      i.nombre AS instructorNombre,
      iu.documento AS instructorDocumento
      FROM registro_asistencia ra 
      JOIN asistencia a ON ra.idAsistencia = a.idAsistencia 
      JOIN ficha f ON a.idFicha = f.idFicha 
      JOIN usuario_detalle ud ON ra.idUsuario = ud.idUsuario 
      JOIN usuario u ON ud.idUsuario = u.idUsuario 
      JOIN ambiente am ON a.idAmbiente = am.idAmbiente 
      LEFT JOIN usuario_detalle i ON a.idInstructor = i.idUsuario
      LEFT JOIN usuario iu ON i.idUsuario = iu.idUsuario
      WHERE a.idFicha = ? AND a.idInstructor = ? 
      GROUP BY a.idAsistencia, f.ficha, f.aprendices, i.nombre, iu.documento
    ";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute([$idSheet, $idInstructor]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
  }

  public function saveRegAssistance($ids, $idAsistencia)
  {
    $conn = $this->getConn();
    $conn->beginTransaction();
    try {
      $placeholders = implode(',', array_fill(0, count($ids), '(?, ?)'));
      $sqlInsert = "INSERT INTO registro_asistencia (idUsuario, idAsistencia) VALUES " . $placeholders;
      $params = [];
      foreach ($ids as $id) {
        $params[] = $id;
        $params[] = $idAsistencia;
      }
      $stmtInsert = $conn->prepare($sqlInsert);
      $stmtInsert->execute($params);
      $conn->commit();
      // echo json_encode(['success' => true, 'message' => "$icon ¡Asistencia Registrada éxitosamente!"]);
      return [
        'insertion' => 'completa'
      ];
    } catch (Exception $e) {
      $conn->rollBack();
      return [
        'insertion' => 'incompleta',
        'details' => [
          'error' => $e->getMessage()
        ]
      ];
    }
  }
}
