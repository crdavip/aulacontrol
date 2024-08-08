<?php
require_once('./model/sessions.php');
if ($userIdRole !== 1 && $userIdRole !== 2 && $userIdRole !== 5) {
    header("Location: ./");
    exit();
}
$titlePg = 'Ambientes';
$titlePgIcon = 'fa-kaaba icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="numberInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
        <input type="text" class="filterSearchPg" name="numberInputFilter" id="numberInputFilter" placeholder="Buscar Num:" autocomplete="off">
        <select class="filterSelectPg" id="statusSelectFilter">
            <option value="all">Estado</option>
        </select>
    </div>';
include('./view/layout/header.php');
if ($userIdRole === 1 || $userIdRole === 2 || $userIdRole == 5) {
    $titlePgRight .= '
        <a id="btnExportExcel" onclick="" class="btnUi btnUiAlt">
            <i class="fa-solid fa-file-excel"></i>
            <p>Exportar Excel</p>
        </a>
        <a href="./registro-ambientes" class="btnUi btnUiAlt">
            <i class="fas fa-clipboard-list"></i>
            <p>Ver Registros</p>
        </a>';
}
if ($userIdRole === 1) {
    $titlePgRight .= '
            <a onclick="openModal(`roomCreate`)" class="btnUi">
            <i class="fa-solid fa-square-plus"></i>
            <p>Nuevo Ambiente</p>
        </a>
    ';
}



include_once('./view/layout/titlePg.php');
?>

<div class="row"></div>

<?php
include_once('./view/layout/ambienteModal.php');
include('./view/layout/footer.php');
?>