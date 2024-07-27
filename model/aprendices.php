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
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "INSERT INTO aprendices (idUsuario, idFicha)
            SELECT idUsuario, ? FROM usuario WHERE idUsuario IN ($placeholders)";
    $stmt = $this->getConn()->prepare($sql);
    if ($stmt->execute(array_merge([$sheet], $ids))) {
      return true;
    } else {
      return false;
    }
  }
}