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
