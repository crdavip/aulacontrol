<?php
require_once('./model/sessions.php');
if ($userIdRole != 1 && $userIdRole != 2) {
    header("Location: ./");
    exit();
}
$titlePg = 'Aulas';
$titlePgIcon = 'fa-kaaba icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="filterPg"><i class="bi bi-funnel-fill"></i> Filtrar</label>
        <select class="filterPg" name="filterPg" id="filterPg">
            <option value="all">Todos</option>
            <option value="null">Sin Revisar</option>
            <option value="1">Aprobados</option>
            <option value="0">Rechazados</option>
        </select>
    </div>';
include('./view/header.php');
if ($userIdRole == 2) {
    $titlePgRight .=
        '<a onclick="openModal(`designCreate`)" class="btnAdd">
            <i class="bi bi-folder-plus"></i>
            <p>Nuevo Diseño</p>
        </a>';
}
include_once('./view/titlePg.php');
?>

<div class="row">
    
</div>

<?php
include_once('./view/modalDesign.php');
include('./view/footer.php');
?>