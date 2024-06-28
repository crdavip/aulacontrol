<?php
require_once ('./db.php');

class Centro extends ConnPDO
{

  // private $connPDO = new ConnPDO;
  // private $pdo = $this->connPDO->getConn();

  function getCenters()
  {
    $sql = "SELECT * FROM centro";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $centers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($centers);
  }

}


