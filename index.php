<?php
require_once('./model/sessions.php');
$titlePg = 'Panel';
$titlePgIcon = 'fa-gauge-high';
$titlePgRight = '';
include_once('./view/layout/header.php');
include_once('./view/layout/titlePg.php');
?>

<main class="homePg">
  <div class="rowFull hero">
    <div class="heroLeft">
      <p>Un lugar donde la gestión de nuestro centro de formación se vuelve <strong>sencilla, cómoda y
          altamente satisfactoria.</strong></p>
    </div>
    <div class="heroRight">
      <img class="heroBg" src="./view/img/heroBg.png" alt="heroBg">
    </div>
  </div>

  <div class="row rowAlt">
    <div class="card">
      <div class="cardBody cardBodyRoom">
        <div class="cardRoomNum cardHomeNum">
          <h2><i class="fa-solid fa-user-graduate"></i></h2>
        </div>
        <div class="cardBodyTxt cardHome">
          <h3>Estudiantes</h3>
          <p>¡Ahora son <strong id="strongStudents" class="statisticValue"></strong> aprendices los que hacen parte del <?= $userCenter; ?>!</p>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="cardBody cardBodyRoom">
        <div class="cardRoomNum cardHomeNum">
          <h2><i class="fa-solid fa-user-tie"></i></h2>
        </div>
        <div class="cardBodyTxt cardHome">
          <h3>Instructores</h3>
          <p>En nuestro centro nos apoyan <strong id="strongInstructor" class="statisticValue">34</strong> instructores
          con mucho conocimiento por entregar.</p>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="cardBody cardBodyRoom">
        <div class="cardRoomNum cardHomeNum">
          <h2><i class="fa-solid fa-display"></i></h2>
        </div>
        <div class="cardBodyTxt cardHome">
          <h3>Equipos</h3>
          <p>Contamos con <strong id="strongDevices" class="statisticValue"></strong> equipos
          tecnológicos disponibles para el diario aprendizaje!</p>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="cardBody cardBodyRoom">
        <div class="cardRoomNum cardHomeNum">
          <h2><i class="fa-solid fa-kaaba"></i></h2>
        </div>
        <div class="cardBodyTxt cardHome">
          <h3>Ambientes</h3>
          <p>Tenemos <strong id="strongRooms" class="statisticValue"></strong> ambientes con todas las herramientas para el correcto aprendizaje.</p>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include('./view/layout/footer.php'); ?>