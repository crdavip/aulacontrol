<?php
require_once ('../model/db.php');

class Centro extends ConnPDO
{

  public function __construct()
  {
    parent::__construct();
  }

  function getCenters()
  {
    $sql = "SELECT * FROM centro";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $centers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($centers);
  }

}


