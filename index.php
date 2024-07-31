<?php
require_once ('./model/sessions.php');
$titlePg = 'Panel';
$titlePgIcon = 'fa-gauge-high';
$titlePgRight = '';
include_once ('./view/layout/header.php');
// include_once ('./view/layout/titlePg.php');
?>
<!-- <div class="mainContainerHome">
  <div class="containerContent"></div>
  <div class="banner1 containerBannerHome">
    <h3>Un lugar de aprendizaje y conocimiento</h3>
  </div>
</div> -->
<div>
  <header class="headerHome">
    <div class="containerTitleHome">
      <h2 class="titleHome">Un lugar donde la gestión de nuestro centro de formación se vuelve sencilla, cómoda y
        altamente satisfactoria.</h2>
    </div>
    <figure>
      <img
        src="https://www.wradio.com.co/resizer/v2/RBPI3CCZQVAL5KGHQ4UY5MF4BY.png?auth=3e404e44852e04ad19b36db523ffca8b3029046b9b56eff9684f9dd54d2bbf4b&width=1440&quality=70&smart=true"
        alt="">
      <figCaption>#En el sena se progresa</figCaption>
    </figure>
  </header>

  <main>
    <div class="containerCardMainHome">
      <div class="cardMainHome">
        <img src="./view/img/logoSena.png" alt="">
        <div class="card__content">
          <p class="card__title">Card Title</p>
          <p class="card__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
        </div>
      </div>

    </div>
    <div class="containerCardMainHomeRigh">
      <div class="cardMainHome">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path
            d="M20 5H4V19L13.2923 9.70649C13.6828 9.31595 14.3159 9.31591 14.7065 9.70641L20 15.0104V5ZM2 3.9934C2 3.44476 2.45531 3 2.9918 3H21.0082C21.556 3 22 3.44495 22 3.9934V20.0066C22 20.5552 21.5447 21 21.0082 21H2.9918C2.44405 21 2 20.5551 2 20.0066V3.9934ZM8 11C6.89543 11 6 10.1046 6 9C6 7.89543 6.89543 7 8 7C9.10457 7 10 7.89543 10 9C10 10.1046 9.10457 11 8 11Z">
          </path>
        </svg>
        <div class="card__content">
          <p class="card__title">Card Title</p>
          <p class="card__description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
        </div>
      </div>
    </div>
  </main>
  <div class="containerStatistics">
    <div class="cardStatistics">
      <div class="cardStatisticsHeader">
        <h4 class="statisticTitle">Estudiantes</h4>
      </div>
      <div class="cardStatisticBody">
        <p class="statisticText">¡Ahora somos <strong id="strongStudents" class="statisticValue"></strong> aprendices que hacen parte del CDMC!</p>
        <div>
          <i class="fa-solid fa-graduation-cap"></i>
        </div>
      </div>
    </div>
    <div class="cardStatistics">
      <div class="cardStatisticsHeader">
        <h4 class="statisticTitle">Instructores</h4>
      </div>
      <div class="cardStatisticBody">
        <p class="statisticText">En nuestro centro nos apoyan <strong id="strongInstructor" class="statisticValue">34</strong> instructores
          con mucho conocimiento por entregar</p>
        <div>
          <i class="fa-solid fa-user-tie"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="containerStatistics">
  <div class="cardStatistics">
    <div class="cardStatisticsHeader">
      <h4 class="statisticTitle">Equipos</h4>
    </div>
    <div class="cardStatisticBody">
      <p class="statisticText">Nuestro centro cuenta con <strong id="strongDevices" class="statisticValue"></strong> equipos
        tecnológicos disponibles para el diario aprendizaje!</p>
      <div>
        <i class="fa-solid fa-display"></i>
      </div>
    </div>
  </div>
  <div class="cardStatistics">
    <div class="cardStatisticsHeader">
      <h4 class="statisticTitle">Observaciones</h4>
    </div>
    <div class="cardStatisticBody">
      <p class="statisticText">Se han creado <strong id="strongObs" class="statisticValue">34</strong> observaciones</p>
      <div>
        <i class="fa-solid fa-binoculars"></i>
      </div>
    </div>
  </div>
</div>
</div>

<?php include ('./view/layout/footer.php'); ?>