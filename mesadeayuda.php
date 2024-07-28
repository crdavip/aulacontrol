<?php
require_once ('./model/sessions.php');
if ($userIdRole !== 1 && $userIdRole !== 2) {
    header("Location: ./");
    exit();
}
$titlePg = 'Mesa de Ayuda';
$titlePgIcon = 'fa-solid fa-handshake-angle icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="numberInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
        <input type="text" class="filterSearchPg" name="numberInputFilter" id="numberInputFilter" placeholder="Buscar Ref:" autocomplete="off">
        <select class="filterSelectPg" id="statusSelectFilter">
            <option value="all">Estado</option>
        </select>
    </div>';
include ('./view/layout/header.php');

if ($userIdRole === 1) {
    $titlePgRight .= '
    <a href="./registro-equipos" class="btnUi btnUiAlt">
        <i class="fas fa-clipboard-list"></i>
        <p>Ver Registros</p>
    </a>
    <a onclick="openModal(`createDevice`)" id="createDeviceBtn" class="btnUi">
        <i class="fa-solid fa-square-plus"></i>
        <p>Nuevo Equipo</p>
    </a>';
}

include_once ('./view/layout/titlePg.php');
?>

<div class="row"></div>

<?php
include_once ('./view/layout/mesadeayudaModal.php');
include ('./view/layout/footer.php');
?>