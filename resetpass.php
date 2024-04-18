<?php
session_start();
require_once('./model/db.php');

$connPDO = new ConnPDO;
$pdo = $connPDO->getConn();

$ID = $_GET['id'] ?? '';
$token = $_GET['token'] ?? '';

$sql = "SELECT USUARIO_ID, NOMBRE, IMAGEN, TOKEN FROM usuario WHERE USUARIO_ID=? AND TOKEN LIKE ? AND LOSTPASS=1 LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$ID, $token]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    header('Location: ./');
    exit();
}

$NAMES = $row['NOMBRE'];
$IMG = $row['IMAGEN'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña | AulaControl </title>
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
                    <h2>¡Hola, <?php echo $NAMES; ?>!</h2>
                </div>
            </div>
            <div class="loginForm">
                <div class="contentForm" id="contentLogin">
                    <div class="userImgPreview">
                        <div class="userImgPicContent">
                            <img class="userImgPic" id="userImgPic" src="<?php echo $IMG; ?>" alt="">
                        </div>
                    </div>
                    <div class="loginTxt">
                        <p class="loginP">Crea una nueva contraseña</p>
                    </div>
                    <form id="usersPassEditForm" action="" class="usersForm">
                        <input type="hidden" name="userPassId" id="userPassId" value="<?php echo $ID; ?>">
                        <div class="inputGroup">
                            <input class="inputGroupInput" type="password" name="passEdit" id="passEdit" autocomplete="off" required>
                            <label class="inputGroupLabel" for="passEdit"><i class="fa-solid fa-lock"></i> Nueva Contraseña</label>
                            <a class="showPass" onclick="showPass('passEdit', this)"><i class="fa-solid fa-eye"></i></a>
                        </div>
                        <div class="inputGroup">
                            <input class="inputGroupInput" type="password" name="passEditTwo" id="passEditTwo" autocomplete="off" required>
                            <label class="inputGroupLabel" for="passEditTwo"><i class="fa-solid fa-lock"></i> Repetir Contraseña</label>
                            <a class="showPass" onclick="showPass('passEditTwo', this)"><i class="fa-solid fa-eye"></i></a>
                        </div>
                        <div class="buttonGroup">
                            <button class="btn" type="submit"><i class="fa-solid fa-save"></i> Guardar</button>
                        </div>
                    </form>
                    <p id="messagePassEdit" class="message"></p>
                </div>
            </div>
        </div>
    </div>
    <script src="./view/js/main.js"></script>
    <script src="./view/js/resetpass.js"></script>
</body>

</html>