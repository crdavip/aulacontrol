<?php
require_once('./model/sessions.php');
if ($userIdRole != 1) {
    header("Location: ./");
    exit();
}
$titlePg = 'Usuarios';
$titlePgIcon = 'fa-user icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="docInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
        <input type="text" class="filterSearchPg" name="docInputFilter" id="docInputFilter" placeholder="Buscar:" autocomplete="off">
        <select class="filterSelectPg" id="centerSelectFilter">
            <option value="all">Centro</option>
        </select>
        <select class="filterSelectPg" id="roleSelectFilter">
            <option value="all">Cargo</option>
        </select>
    </div>';
include('./view/layout/header.php');
if ($userIdRole === 1) {
    $titlePgRight .= '
        <a onclick="openModal(`userImport`)" class="btnUi btnUiAlt">
            <i class="fa-solid fa-file-csv"></i>
            <p>Importar CSV</p>
        </a>
        <a id="btnExportExcel" onclick="" class="btnUi btnUiAlt">
            <i class="fa-solid fa-print"></i>
            <p>Exportar Excel</p>
        </a>
        <a id="btnExportPdf" onclick="" class="btnUi">
            <i class="fa-solid fa-file-pdf"></i>
            <p>Exportar PDF</p>
        </a>
        <a onclick="openModal(`userCreate`)" class="btnUi">
            <i class="fa-solid fa-user-plus"></i>
            <p>Nuevo Usuario</p>
        </a>';
}

include_once('./view/layout/titlePg.php');
?>

<div class="row"></div>

<?php
include_once('./view/layout/usuariosModal.php');
include('./view/layout/footer.php');
?>