<?php require_once('./model/db.php');
$connPDO = new ConnPDO;
$pdo = $connPDO->getConn();
$userId = $_SESSION['userId'];
$userName = $_SESSION['name'];
$userIdRole = $_SESSION['idRole'];
$userRole = $_SESSION['role'];
$userImg = $_SESSION['img'];
$userImgQr = $_SESSION['imgQr'];
$userCenter = $_SESSION['center'];

if (!isset($userId)) {
    header('Location: ./ingreso');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $titlePg; ?> | AulaControl </title>
    <link rel="stylesheet" href="./view/css/main.css" />
    <link rel="shortcut icon" href="./view/img/fav.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="./plugins/qrCode.min.js"></script>
</head>

<body>
    <input type="hidden" id="userIdView" value="<?php echo $userId; ?>">
    <input type="hidden" id="userNameView" value="<?php echo $userName; ?>">
    <input type="hidden" id="userRolView" value="<?php echo $userIdRole; ?>">
    <input type="hidden" id="userImgView" value="<?php echo $userImg; ?>">
    <nav class="sidenav close">
        <div class="sidenavHeader">
            <i class="fa-solid fa-ellipsis-vertical toggle"></i>
            <div class="text">
                <span class="text navText">Menu</span>
            </div>
        </div>
        <div class="sidenavMenu">
            <div class="menu">
                <ul class="menuLinks">
                    <li class="navLink">
                        <a href="./">
                            <i class="fa-solid fa-gauge-high icon"></i>
                            <span class="text navText">Panel</span>
                        </a>
                    </li>
                    <?php if ($userIdRole == 1 || $userIdRole == 2) { ?>
                        <li class="navLink">
                            <a href="./ambientes">
                                <i class="fa-solid fa-kaaba icon"></i>
                                <span class="text navText">Ambientes</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($userIdRole == 1) { ?>
                        <li class="navLink">
                            <a href="./fichas">
                                <i class="fa-solid fa-table icon"></i>
                                <span class="text navText">Fichas</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($userIdRole == 1) { ?>
                        <li class="navLink">
                            <a href="./asistencia">
                                <i class="fa-solid fa-calendar-check icon"></i>
                                <span class="text navText">Asistencia</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($userIdRole == 1) { ?>
                        <li class="navLink">
                            <a href="./instructores">
                                <i class="fa-solid fa-user-tie icon"></i>
                                <span class="text navText">Instructores</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($userIdRole == 1) { ?>
                        <li class="navLink">
                            <a href="./aprendices">
                                <i class="fa-solid fa-user-graduate icon"></i>
                                <span class="text navText">Aprendices</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($userIdRole == 1 || $userIdRole == 4) { ?>
                        <li class="navLink">
                            <a href="./equipos">
                                <i class="fa-solid fa-desktop icon"></i>
                                <span class="text navText">Equipos</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($userIdRole == 1) { ?>
                        <li class="navLink">
                            <a href="./observaciones">
                                <i class="fa-solid fa-binoculars icon"></i>
                                <span class="text navText">Observaciones</span>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="navLink" id="navAlert">
                        <a>
                            <i class="fa-solid fa-bell icon"></i>
                            <span class="text navText">Notificaciones</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="sidenavBottom">
                <li class="darkmode">
                    <label class="darkmodeBtn" for="darkmodeBtn">
                        <input type="checkbox" name="" id="darkmodeBtn" />
                        <span class="dm-span">
                            <i class="fa-solid fa-sun on"></i>
                            <i class="fa-solid fa-moon off"></i>
                        </span>
                    </label>
                    <span class="text" id="darkmodeTxt">Modo Claro</span>
                </li>
                <div class="created">
                    <a href="https://cristiandavid.com.co/" target="_blank">
                        <img src="./view/img/crdavip.svg" alt="crdavip" /></a>
                    <div class="text">
                        <span class="text navText">developed by</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <button class="scrollBtn">
        <i class="fa-solid fa-angles-up"></i>
    </button>

    <section class="containerPg" id="containerPg">
        <header class="header close">
            <i class="bi bi-three-dots-vertical toggleMobile"></i>
            </div>
            <div class="logo">
                <img src="./view/img/logoSena.png" alt="logoSena">
                <div class="separator"></div>
                <a href="./"><img src="./view/img/logo.svg" alt=""></a>
            </div>
            <div class="userInfo">
                <p>Hola, <span id="userName" class="userName"></span><br>
                    <span id="userWelcome" class="userWelcome">¡Buenos Días!</span>
                </p>
                <div class="userProfile">
                    <div class="userImage" id="userImage">
                        <img class="userImagePic" id="userImagePic" src="" alt="profile">
                    </div>
                    <div class="userItems" id="userItems">
                        <a onclick="openModal('userProfile')"><i class="fa-solid fa-user"></i>Perfil</a>
                        <a href="./model/logout"><i class="fa-solid fa-right-from-bracket"></i></i>Salir</a>
                    </div>
                </div>
            </div>
        </header>
        <div class="content">