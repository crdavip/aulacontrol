<?php
// session_start();
// if (isset($_SESSION['USUARIO_ID'])) {
//     header('Location: ./');
//     exit();
// }
session_start();
$userId = $_SESSION['userId'];
$userName = $_SESSION['name'];
$userImg = $_SESSION['img'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primera Vez | AulaControl </title>
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
                <div class="welcomeTxt firstTimeTxt">
                    <h2>Hola <span><?= $userName; ?>,</span> antes de empezar debes completar tus datos. <span>Ten en cuenta lo siguiente:</span></h2>
                    <ul>
                        <li>Debes cargar la misma foto del Carnet.</li>
                        <li>La contraseña no puede ser igual a tu Documento.</li>
                    </ul>
                </div>
            </div>
            <div class="loginForm">
                <div class="contentForm" id="contentLogin">
                    <form id="userFirstTime" action="" class="form" enctype="multipart/form-data">
                        <div class="inputGroup uploadProfileImg">
                            <input type="file" name="userImgProfile" id="userImgProfile" accept="image/jpeg, image/png" hidden required>
                            <div class="userImgPreview" id="userImgPreview">
                                <a class="userImgUpload" id="userImgUpload"><i class="fa-solid fa-camera"></i></a>
                                <div class="userImgPicContent">
                                    <img class="userImgPic" id="userImgPic" src="<?php echo $userImg; ?>" alt="">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="userIdProfile" id="userIdProfile" value="<?php echo $userId; ?>">
                        <div class="inputGroup">
                            <input class="inputGroupInput" type="password" name="pass" id="pass" autocomplete="off" required>
                            <label class="inputGroupLabel" for="pass" id="labelPass"><i class="fa-solid fa-lock"></i> Nueva Contraseña</label>
                            <a class="showPass" onclick="showPass('pass', this)"><i class="fa-solid fa-eye"></i></a>
                        </div>
                        <div class="inputGroup">
                            <input class="inputGroupInput" type="password" name="passTwo" id="passTwo" autocomplete="off" required>
                            <label class="inputGroupLabel" for="passTwo" id="labelPassTwo"><i class="fa-solid fa-lock"></i> Repetir Contraseña</label>
                            <a class="showPass" onclick="showPass('passTwo', this)"><i class="fa-solid fa-eye"></i></a>
                        </div>
                        <div class="inputGroup">
                            <input class="inputGroupInput" type="date" name="birthDate" id="birthDate" autocomplete="on" required>
                            <label class="inputGroupLabel inputDate" for="birthDate" id="labelDate"><i class="fa-solid fa-calendar-days"></i> Fecha de Nacimiento</label>
                        </div>
                        <div class="inputGroup">
                            <label class="inputGroupLabel" for="genre"><i class="fa-solid fa-venus-mars"></i></label>
                            <select class="inputGroupSelect" name="genre" id="genre" required>
                                <option value="">Seleccione un Género</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="buttonGroup">
                            <button class="btn" type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                        </div>
                    </form>
                    <p id="messageCreate" class="message"></p>
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
            <form id="lostPassForm" action="" class="form" autocomplete="off">
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
    <script src="./view/js/datos.js"></script>
</body>

</html>