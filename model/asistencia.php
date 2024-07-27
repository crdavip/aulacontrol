<?php
require_once ('../controller/funciones.php');

class Asistencia extends ConnPDO
{
  private $functions;
  public function __construct()
  {
    parent::__construct(); // Llamar al constructor de la clase padre
    $this->functions = new Funciones();
  }
}
