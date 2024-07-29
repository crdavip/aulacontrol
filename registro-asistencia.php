<?php
require_once('./model/sessions.php');
if ($userIdRole !== 1 && $userIdRole !== 2) {
    header("Location: ./");
    exit();
}
$titlePg = 'Registro de Asistencias';
$titlePgIcon = 'fa-clipboard-list icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="numberInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
        <input type="text" class="filterSearchPg" name="numberInputFilter" id="numberInputFilter" placeholder="Buscar:" autocomplete="off">
        <input type="date" class="filterDatePg" name="dateInputFilter" id="dateInputFilter">
    </div>';
include('./view/layout/header.php');

if ($userIdRole === 1) {
    $titlePgRight .= '
    <a onclick="openModal(`regAssistExportExcel`)" class="btnUi btnUiAlt">
            <i class="fa-solid fa-print"></i>
            <p>Exportar Excel</p>
    </a>';
}

include_once('./view/layout/titlePg.php');
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
                <th>Fecha</th>
                <th>Ficha</th>
                <th class="tdBool">Accion</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>
    <!-- <div id="fullContainerResults"></div> -->
</div>

<?php
include_once('./view/layout/modalRegAsistencia.php');
include('./view/layout/footer.php');
?>