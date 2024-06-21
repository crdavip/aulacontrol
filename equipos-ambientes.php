<?php
require_once ('./model/sessions.php');
if ($userIdRole !== 1 && $userIdRole !== 2) {
  header("Location: ./");
  exit();
}

$titlePg = 'Equipos de Ambiente';
$titlePgIcon = 'fa-solid fa-desktop icon';
$titlePgRight = '
    <div class="containerFilterPg">
        <label for="numberInputFilter"><i class="fa-solid fa-magnifying-glass"></i></label>
        <input type="text" class="filterSearchPg" name="numberInputFilter" id="numberInputFilter" placeholder="Buscar:" autocomplete="off">
        <select class="filterSelectPg" id="centerSelectFilter">
            <option value="all">Centro</option>
        </select>
        <select class="filterSelectPg" id="statusSelectFilter">
            <option value="all">Estado</option>
        </select>
    </div>';

include ('./view/layout/header.php');


include_once ('./view/layout/titlePg.php');
?>

<div class="rowEnvDevices">Este es el body</div>

<?php
// include_once('./view/layout/ambienteModal.php');
include ('./view/layout/footer.php');
?>