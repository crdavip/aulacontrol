<?php
session_start();
if (isset($_SESSION['USUARIO_ID'])) {
    header('Location: ./');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso | AulaControl </title>
    <link rel="shortcut icon" href="./view/img/fav.svg" type="image/x-icon">
    <link rel="stylesheet" href="./view/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <div class="contentLoginBgImg"></div>
    <div class="contentLoginBg">
        <div class="contentLogin">
            <div class="loginWall">
                <li class="darkmode darkmodeAlt">
                    <label class="darkmodeBtn" for="darkmodeBtn">
                        <input type="checkbox" name="" id="darkmodeBtn" />
                        <span class="dm-span">
                            <i class="fa-solid fa-sun on"></i>
                            <i class="fa-solid fa-moon off"></i>
                        </span>
                    </label>
                    <span class="text" id="darkmodeTxt">Modo Claro</span>
                </li>
                <div class="logoWall">
                    <img class="favWall" src="./view/img/fav.svg" alt="">
                </div>
                <div class="welcomeTxt">
                    <h2>Bienvenido a <span>AulaControl</span></h2>
                </div>
            </div>
            <div class="loginForm">
                <div class="contentForm" id="contentLogin">
                    <div class="userImgPreview">
                        <div class="userImgPicContent">
                            <img class="userImgPic" id="userImgPic" src="./view/img/users/default.jpg" alt="">
                        </div>
                    </div>
                    <div class="loginTxt">
                        <p class="loginP">Inicia sesión para empezar</p>
                    </div>
                    <form id="login" action="">
                        <div class="inputGroup">
                            <input class="inputGroupInput" type="number" name="doc" id="doc" autocomplete="on" required>
                            <label class="inputGroupLabel" for="doc" id="labelDoc"><i class="fa-solid fa-id-card"></i> Documento</label>
                        </div>
                        <div class="inputGroup">
                            <input class="inputGroupInput" type="password" name="pass" id="pass" autocomplete="off" required>
                            <label class="inputGroupLabel" for="pass" id="labelPass"><i class="fa-solid fa-lock"></i> Contraseña</label>
                            <a class="showPass" onclick="showPass('pass', this)"><i class="fa-solid fa-eye"></i></a>
                        </div>
                        <div class="inputGroup loginExt">
                            <div class="contentRemember">
                                <label for="remember">Recuérdame:</label>
                                <input class="remember" type="checkbox" name="remember" id="remember">
                            </div>
                            <a onclick="openModal('lostPass')" class="lostPassword">Olvidé la contraseña</a>
                        </div>
                        <div class="buttonGroup">
                            <button class="btn" type="submit"><i class="fa-solid fa-right-to-bracket"></i> Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal LostPass -->
    <section class="modal" id="lostPass">
        <div class="containerModal">
            <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
            <div class="containerModalBg"></div>
            <div class="titlePg">
                <i class="fa-solid fa-arrows-rotate"></i>
                <h3>Recuperar Contraseña</h3>
            </div>
            <form id="lostPassForm" action="" class="usersForm" autocomplete="off">
                <input type="hidden" name="userIdPassEdit" id="userIdPassEdit" value="<?php echo $userId; ?>">
                <div class="inputGroup">
                    <input class="inputGroupInput" type="number" name="lostPassDoc" id="lostPassDoc" required>
                    <label class="inputGroupLabel" for="lostPassDoc" id="labelLostPassDoc"><i class="fa-solid fa-id-card"></i> Documento</label>
                </div>
                <div class="inputGroup">
                    <p>En tu correo llegará un enlace para cambiar la contraseña.</p>
                </div>
                <div class="buttonGroup">
                    <button class="btn btnAlt" type="submit"><i class="fa-solid fa-paper-plane"></i> Enviar</button>
                </div>
            </form>
        </div>
    </section>
    <script src="./view/js/main.js"></script>
    <script src="./view/js/ingreso.js"></script>
</body>

</html>