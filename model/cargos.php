<?php
require_once ('../model/db.php');

class Cargos extends ConnPDO
{

  public function __construct()
  {
    parent::__construct();
  }

  function getRoles()
  {
    $sql = "SELECT * FROM cargo";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $centers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($centers);
  }
}