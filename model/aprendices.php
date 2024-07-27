<?php
require_once ('../controller/funciones.php');

class Aprendices extends ConnPDO
{
  private $functions;
  public function __construct()
  {
    parent::__construct();
    $this->functions = new Funciones();
  }

  public function saveTrainee($ids, $sheet)
  {
    $conn = $this->getConn();
    $conn->beginTransaction(); // Inicia la transacción
    $excludedIds = [];

    try {
      // 1. Verifica los usuarios que ya están asociados a una ficha activa
      $placeholders = implode(',', array_fill(0, count($ids), '?'));
      $sqlCheck = "SELECT idUsuario FROM aprendices 
                       JOIN ficha ON aprendices.idFicha = ficha.idFicha
                       WHERE idUsuario IN ($placeholders) AND ficha.estado = 'Activa'";
      $stmtCheck = $conn->prepare($sqlCheck);
      $stmtCheck->execute($ids);
      $activeUsers = $stmtCheck->fetchAll(PDO::FETCH_COLUMN);

      // 2. Filtra los IDs excluyendo aquellos que ya están en fichas activas
      $filteredIds = array_diff($ids, $activeUsers);
      $excludedIds = array_intersect($ids, $activeUsers);

      if (!empty($filteredIds)) {
        // 3. Inserta los nuevos registros en la tabla aprendices
        $placeholders = implode(',', array_fill(0, count($filteredIds), '(?, ?)'));
        $sqlInsert = "INSERT INTO aprendices (idUsuario, idFicha)
                            VALUES " . $placeholders;
        $params = [];
        foreach ($filteredIds as $id) {
          $params[] = $id;
          $params[] = $sheet;
        }
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->execute($params);
      }

      // 4. Actualiza la tabla fichas con el nuevo conteo de aprendices
      $sqlUpdate = "UPDATE ficha 
                        SET aprendices = (SELECT COUNT(*) FROM aprendices WHERE idFicha = ficha.idFicha)
                        WHERE idFicha = ?";
      $stmtUpdate = $conn->prepare($sqlUpdate);
      $stmtUpdate->execute([$sheet]);

      $conn->commit(); // Confirma la transacción

      return [
        'insertion' => empty($excludedIds) ? 'completa' : 'parcial',
        'details' => [
          'excludedIds' => $excludedIds
        ]
      ];
    } catch (Exception $e) {
      $conn->rollBack(); // Revierte la transacción en caso de error
      return [
        'insertion' => 'incompleta',
        'details' => [
          'error' => $e->getMessage()
        ]
      ];
    }
  }



  // public function saveTrainee($ids, $sheet)
  // {
  //   $conn = $this->getConn();
  //   $conn->beginTransaction(); // Inicia la transacción
  //   $excludedIds = [];

  //   try {
  //     // 1. Verifica los usuarios que ya están asociados a una ficha activa
  //     $placeholders = implode(',', array_fill(0, count($ids), '?'));
  //     $sqlCheck = "SELECT idUsuario FROM aprendices 
  //                    JOIN ficha ON aprendices.idFicha = ficha.idFicha
  //                    WHERE idUsuario IN ($placeholders) AND ficha.estado = 'Activa'";
  //     $stmtCheck = $conn->prepare($sqlCheck);
  //     $stmtCheck->execute($ids);
  //     $activeUsers = $stmtCheck->fetchAll(PDO::FETCH_COLUMN);

  //     // 2. Filtra los IDs excluyendo aquellos que ya están en fichas activas
  //     $filteredIds = array_diff($ids, $activeUsers);
  //     $excludedIds = array_intersect($ids, $activeUsers);

  //     if (!empty($filteredIds)) {
  //       // 3. Inserta los nuevos registros en la tabla aprendices
  //       $placeholders = implode(',', array_fill(0, count($filteredIds), '(?, ?)'));
  //       $sqlInsert = "INSERT INTO aprendices (idUsuario, idFicha)
  //                         SELECT idUsuario, ? FROM usuario WHERE idUsuario IN ($placeholders)";
  //       $params = array_merge([$sheet], array_map(function ($id) {
  //         return $id; }, $filteredIds));
  //       $stmtInsert = $conn->prepare($sqlInsert);
  //       $stmtInsert->execute($params);
  //     }

  //     // 4. Actualiza la tabla fichas con el nuevo conteo de aprendices
  //     $sqlUpdate = "UPDATE ficha 
  //                     SET aprendices = (SELECT COUNT(*) FROM aprendices WHERE idFicha = ficha.idFicha)
  //                     WHERE idFicha = ?";
  //     $stmtUpdate = $conn->prepare($sqlUpdate);
  //     $stmtUpdate->execute([$sheet]);

  //     $conn->commit(); // Confirma la transacción

  //     return [
  //       'insertion' => empty($excludedIds) ? 'completa' : 'parcial',
  //       'details' => [
  //         'excludedIds' => $excludedIds
  //       ]
  //     ];
  //   } catch (Exception $e) {
  //     $conn->rollBack(); // Revierte la transacción en caso de error
  //     return [
  //       'insertion' => 'incompleta',
  //       'details' => [
  //         'error' => $e->getMessage()
  //       ]
  //     ];
  //   }
  // }
}