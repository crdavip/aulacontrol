<?php
require_once('./model/sessions.php');
if ($userIdRole !== 1 && $userIdRole !== 2) {
    header("Location: ./");
    exit();
}
$titlePg = 'Registro de Equipos';
$titlePgIcon = 'fa-clipboard-list icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="numberInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
        <input type="text" class="filterSearchPg" name="numberInputFilter" id="numberInputFilter" placeholder="Buscar:" autocomplete="off">
        <input type="date" class="filterDatePg" name="dateInputFilter" id="dateInputFilter">
    </div>';
include('./view/layout/header.php');

if ($userIdRole === 1 || $userIdRole === 2) {
    $titlePgRight .= '
        <a onclick="openModal(`regDeviceExportExcel`)" class="btnUi btnUiAlt">
            <i class="fa-solid fa-file-excel"></i>
            <p>Exportar Excel</p>
        </a>
        <a onclick="openModal(`regDeviceExportPdf`)" class="btnUi btnUiAlt">
            <i class="fa-solid fa-file-pdf"></i>
            <p>Exportar PDF</p>
        </a>
        ';
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
                <th>Usuario</th>
                <th>Equipo</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>
</div>

<?php
include_once('./view/layout/equiposModal.php');
include('./view/layout/footer.php');
?>