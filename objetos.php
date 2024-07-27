<?php
require_once ('./model/sessions.php');
if ($userIdRole !== 1 && $userIdRole !== 2) {
    header("Location: ./");
    exit();
}
$titlePg = 'Objetos';
$titlePgIcon = 'fa-solid fa-cubes icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="numberInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
        <input type="text" class="filterSearchPg" name="numberInputFilter" id="numberInputFilter" placeholder="Buscar:" autocomplete="off">
    </div>';
include ('./view/layout/header.php');

if ($userIdRole === 1) {
    $titlePgRight .= '
    <a id="btnExportExcelObjects" onclick="" class="btnUi btnUiAlt">
        <i class="fa-solid fa-print"></i>
        <p>Exportar Excel</p>
    </a>
    <a id="btnExportPdfObjects" onclick="" class="btnUi">
        <i class="fa-solid fa-file-pdf"></i>
        <p>Exportar PDF</p>
    </a>
    <a href="./registro-objetos" class="btnUi btnUiAlt">
        <i class="fas fa-clipboard-list"></i>
        <p>Ver Registros</p>
    </a>
    <a onclick="openModal(`createObject`)" id="createObjectBtn" class="btnUi btnUiAlt">
        <i class="fa-solid fa-square-plus"></i>
        <p>Nuevo objeto</p>
    </a>';
}

include_once ('./view/layout/titlePg.php');
?>

<div class="row"></div>

<?php
include_once ('./view/layout/objetosModal.php');
include ('./view/layout/footer.php');
?>