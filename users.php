<?php
require_once('./model/sessions.php');
if ($userRole != 1) {
    header("Location: ./");
    exit();
}
$titlePg = 'Usuarios';
$titlePgIcon = 'bi-people icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="filterPg"><i class="bi bi-funnel-fill"></i> Filtrar</label>
        <select class="filterPg" name="filterPg" id="filterPg">
            <option value="all">Todos</option>
            <option value="1">Gerente</option>
            <option value="2">Diseñador</option>
            <option value="3">Logistico</option>
            <option value="4">Cajero</option>
            <option value="5">Contador</option>
        </select>
    </div>';
include('./view/header.php');
$titlePgRight .= '
    <a class="btnAdd" onclick="openModal(`usersCreate`)">
        <i class="bi bi-person-add"></i>
        <p>Crear Usuario</p>
    </a>';
include_once('./view/titlePg.php');
?>

<div class="row"></div>

<?php
include_once('./view/modalUsers.php');
include('./view/footer.php');
?>