<?php
require_once('./model/sessions.php');
if ($userIdRole !== 1 && $userIdRole !== 2 && $userIdRole !== 4) {
    header("Location: ./");
    exit();
}
$titlePg = 'Registro de Observaciones';
$titlePgIcon = 'fa-clipboard-list icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <input type="number" name="documentInputFilter" id="documentInputFilter">
        <br>
        <br>
        <input type="date" class="filterDatePg" name="dateInputFilter" id="dateInputFilter">
    </div>';
include('./view/layout/header.php');
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
                <th>Fecha Publicacion</th>
                <th>Descripcion</th>
                <th>Fecha Revision</th>
                <th>Revisado Por</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>
</div>

<?php
include_once('./view/layout/ambienteModal.php');
include('./view/layout/footer.php');
?>