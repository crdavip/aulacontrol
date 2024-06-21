<?php
require_once ('./model/sessions.php');
if ($userIdRole !== 1 && $userIdRole !== 2) {
  header("Location: ./");
  exit();
}
$titlePg = 'Registro de Equipos de Ambiente';
$titlePgIcon = 'fa-clipboard-list icon';
$titlePgRight = '';
include ('./view/layout/header.php');

if ($userIdRole === 1) {
  $titlePgRight .= '
    <a onclick="" class="btnUi btnUiAlt">
      <i class="fa-solid fa-print"></i>
      <p>Imprimir</p>
    </a>
    <a onclick="" class="btnUi">
      <i class="fa-solid fa-file-pdf"></i>
      <p>Exportar PDF</p>
    </a>';
}

include_once ('./view/layout/titlePg.php');
?>

<div class="rowFull">
  <div class="tablePagination">
    <div class="containerFilterPg">
      <p>Mostrar</p>
      <select class="selectPgLimit" id="selectPgLimit">
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="50">50</option>
      </select>
      <p>Registros</p>
    </div>
    <div class="pagination">
      <button class="paginationBtn" id="pgPrev"><i class="fa-solid fa-angles-left"></i></button>
      <div class="paginationItems" id="paginationItems"></div>
      <button class="paginationBtn" id="pgNext"><i class="fa-solid fa-angles-right"></i></button>
    </div>
  </div>
  <table>
    <thead>
      <tr>
        <th>Instructor</th>
        <th>Ambiente</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th class="tdBool">Llaves</th>
        <th class="tdBool">TV</th>
        <th class="tdBool">Aire</th>
      </tr>
    </thead>
    <tbody id="tableBody"></tbody>
  </table>
</div>

<?php
include_once ('./view/layout/ambienteModal.php');
include ('./view/layout/footer.php');
?>