<?php
require_once ('./model/sessions.php');
if ($userIdRole !== 1 && $userIdRole !== 2 && $userIdRole !== 3) {
    header("Location: ./");
    exit();
}
$titlePg = 'Equipos';
$titlePgIcon = 'fa-solid fa-desktop icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="envInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
        <input type="text" class="filterSearchPg" name="envInputFilter" id="envInputFilter" placeholder="Ambiente:" autocomplete="off">
        <label for="numberInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
        <input type="text" class="filterSearchPg" name="numberInputFilter" id="numberInputFilter" placeholder="Buscar Ref:" autocomplete="off">
        <select class="filterSelectPg" id="statusSelectFilter">
            <option value="all">Estado</option>
        </select>
    </div>';
include ('./view/layout/header.php');

$titlePgRight .= '
    <a id="assocDeviceBtn" data-id="'.$userId.'" class="btnUi btnUiAlt">
        <i class="fa-solid fa-qrcode"></i>
        <p>Vincular Equipo</p>
    </a>
';

if ($userIdRole === 1 || $userIdRole === 2) {
    $titlePgRight .= '
    <a href="./registro-equipos" class="btnUi btnUiAlt">
        <i class="fas fa-clipboard-list"></i>
        <p>Ver Registros</p>
    </a>';
}
if ($userIdRole === 1) {
    $titlePgRight .= '
    <a onclick="openModal(`createDevice`)" id="createDeviceBtn" class="btnUi">
        <i class="fa-solid fa-square-plus"></i>
        <p>Nuevo Equipo</p>
    </a>';
}


include_once ('./view/layout/titlePg.php');
?>

<div class="row"></div>

<?php
include_once ('./view/layout/equiposModal.php');
include ('./view/layout/footer.php');
?>