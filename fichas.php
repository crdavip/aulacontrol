<?php
require_once('./model/sessions.php');
if ($userIdRole !== 1 && $userIdRole !== 2) {
    header("Location: ./");
    exit();
}
$titlePg = 'Fichas';
$titlePgIcon = 'fa-table icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="numberInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
        <input type="text" class="filterSearchPg" name="numberInputFilter" id="numberInputFilter" placeholder="Buscar Ficha:" autocomplete="off">
    </div>';
include('./view/layout/header.php');
if ($userIdRole === 1) {
    $titlePgRight .= '
        <a onclick="openModal(`dataSheetCreate`)" class="btnUi">
            <i class="fa-solid fa-square-plus"></i>
            <p>Nueva Ficha</p>
        </a>';
}

include_once('./view/layout/titlePg.php');
?>

<div class="row">
    <div class="card">
        <div class="cardBody cardBodyRoom">
            <a class="cardMenu">
                <i class="fa-solid fa-ellipsis"></i>
            </a>
            <div class="cardMenuItems">
                <a><i class="fa-solid fa-qrcode"></i>Vincular</a>
                <a><i class="fa-solid fa-pen-to-square"></i>Editar</a>
                <a><i class="fa-solid fa-trash"></i>Eliminar</a>
            </div>
            <div class="cardDataSheetNum">
                <h2>2617416</h2>
            </div>
            <div class="cardBodyTxt">
                <p>Analisis y Desarrollo de Software</p>
                <h3>CDMC</h3>
                <span>12 Aprendices</span>
            </div>
        </div>
    </div>
</div>

<?php
include_once('./view/layout/fichaModal.php');
include('./view/layout/footer.php');
?>