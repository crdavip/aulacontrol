<?php

require_once ('../model/db.php');

class Fichas extends ConnPDO
{

  public function __construct()
  {
    parent::__construct();
  }

  function getDataSheets()
  {
    $sql = "SELECT f.idFicha, f.ficha, f.detalle AS curso, f.aprendices, c.siglas AS centro
            FROM ficha AS f
            INNER JOIN centro AS c ON c.idCentro = f.idCentro ";
    $stmt = $this->getConn()->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($row, JSON_UNESCAPED_UNICODE);
  }

}