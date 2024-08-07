<?php
require_once('./model/sessions.php');

$titlePg = 'Observaciones';
$titlePgIcon = 'fa-binoculars icon';

include('./view/layout/header.php');
if ($userIdRole === 1 || $userIdRole === 4) {
    $titlePgRight = '
        <div class="containerFilterPg">
            <label for="docInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
            <input type="text" class="filterSearchPg" name="docInputFilter" id="docInputFilter" placeholder="Buscar Doc:" autocomplete="off">
        </div>
        <a href="./registro-observaciones" class="btnUi btnUiAlt">
            <i class="fas fa-clipboard-list"></i>
            <p>Ver Registros</p>
        </a>
        <a onclick="openModal(`observationCreate`)" class="btnUi">
            <i class="fa-solid fa-square-plus"></i>
            <p>Nueva Observación</p>
        </a>';
} else {
    $titlePgRight = '
        <a onclick="openModal(`observationCreate`)" class="btnUi">
            <i class="fa-solid fa-square-plus"></i>
            <p>Nueva Observacion</p>
        </a>';
}

include_once('./view/layout/titlePg.php');
?>

<div class="row"></div>

<?php
include_once('./view/layout/observacionesModal.php');
include('./view/layout/footer.php');
?>