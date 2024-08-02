</div>
<footer class="footer">
    <p>Copyright © 2024 <a href="https://cristiandavid.com.co">Cristian David.</a> Todos los
        derechos reservados.
    </p>
</footer>
</section>

<!-- Modal UserProfile -->
<section class="modal" id="userProfile">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="containerModalBg"></div>
        <div class="containerProfile">
            <div class="wrapperQr">
                <div class="wrapperSenaCard">
                    <div class="headerSenaCard">
                        <img class="logoSenaCard" src="./view/img/logoSena.png">
                        <img class="picSenaCard" id="picSenaCard" src="<?= $userImg; ?>">
                        <span class="roleSenaCard" id="roleSenaCard"><?= $userRole; ?></span>
                    </div>
                    <div class="bodySenaCard">
                        <div class="nameSenaCard">
                            <h3 id="nameUserSenaCard"><?= $userName; ?></h3>
                        </div>
                        <div class="dataSenaCard">
                            <p>C.C. <span id="docUserSenaCard"><?= $userDoc; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttonGroup">
            <button class="btn btnAlt" onclick="openModal('userPassEdit'); closeModal('userProfile');" type="button"><i class="fa-solid fa-arrows-rotate"></i> Cambiar Contraseña</button>
        </div>
        <div class="buttonGroup">
            <button class="btn btnAlt" onclick="openModal('userViewQr'); closeModal('userProfile');" type="button"><i class="fa-solid fa-qrcode"></i> Ver Código QR</button>
        </div>
    </div>
</section>

<!-- Modal ChangePass -->
<section class="modal" id="userPassEdit">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="containerModalBg"></div>
        <div class="titlePg">
            <i class="fa-solid fa-arrows-rotate"></i>
            <h3>Cambiar Contraseña</h3>
        </div>
        <form id="userPassEditForm" action="" class="form">
            <input type="hidden" name="userIdPassEdit" id="userIdPassEdit" value="<?php echo $userId; ?>">
            <div class="inputGroup">
                <input class="inputGroupInput" type="password" name="userOldPass" id="userOldPass" autocomplete="off" required>
                <label class="inputGroupLabel" for="userOldPass"><i class="fa-solid fa-lock"></i> Contraseña Actual</label>
                <a class="showPass" onclick="showPass('userOldPass', this)"><i class="fa-solid fa-eye"></i></i></a>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="password" name="userPassProfile" id="userPassProfile" autocomplete="off" required>
                <label class="inputGroupLabel" for="userPassProfile"><i class="fa-solid fa-lock"></i> Nueva
                    Contraseña</label>
                <a class="showPass" onclick="showPass('userPassProfile', this)"><i class="fa-solid fa-eye"></i></i></a>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="password" name="userPassProfileTwo" id="userPassProfileTwo" autocomplete="off" required>
                <label class="inputGroupLabel" for="userPassProfileTwo"><i class="fa-solid fa-lock"></i> Repetir
                    Contraseña</label>
                <a class="showPass" onclick="showPass('userPassProfileTwo', this)"><i class="fa-solid fa-eye"></i></i></a>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="fa-solid fa-save"></i> Guardar</button>
            </div>
        </form>
        <p id="messageUserPassEdit" class="message"></p>
    </div>
</section>

<!-- Modal ViewQR -->
<section class="modal" id="userViewQr">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePgAlt">
            <h1><?= $userName; ?></h1>
            <span>CC <?= $userDoc; ?> - <?= $userRole; ?></span>
        </div>
        <img class="imgQr" src="<?= $userImgQr; ?>" alt="QR">
    </div>
</section>

<?php $connPDO->closeConn(); ?>
<audio id="beepQr" src="./view/sound/scanner.mp3"></audio>
<script src="./view/js/main.js"></script>
<script src="./view/js/perfil.js"></script>
<?php switch ($titlePg) {
    case 'Panel':
        echo '<script src="./view/js/home.js" type="module"></script>';
        break;
    case 'Ambientes':
        echo '<script src="./view/js/ambientes.js"></script>';
        break;
    case 'Registro de Ambientes':
        echo '<script src="./view/js/registroAmbientes.js" type="module"></script>';
        break;
    case 'Fichas':
        echo '<script src="./view/js/fichas.js"></script>';
        break;
    case 'Usuarios':
        echo '<script src="./view/js/usuarios.js"></script>';
        break;
    case 'Equipos':
        echo '<script src="./view/js/equipos.js" type="module"></script>';
        break;
    case 'Mesa de Ayuda':
        echo '<script src="./view/js/mesadeayuda.js" type="module"></script>';
        break;
    case 'Registro de Equipos':
        echo '<script src="./view/js/registroEquipos.js" type="module"></script>';
        break;
    case 'Registro de Mesa de Ayuda':
        echo '<script src="./view/js/registroMesaDeAyuda.js" type="module"></script>';
        break;
    case 'Objetos':
        echo '<script src="./view/js/objetos.js" type="module"></script>';
        break;
    case 'Registro de Objetos':
        echo '<script src="./view/js/registroObjetos.js" type="module"></script>';
        break;
    case 'Registro de Asistencias':
        echo '<script src="./view/js/registroAsistencia.js"></script>';
        break;
    case 'Observaciones':
        echo '<script src="./view/js/observaciones.js" type="module"></script>';
        break;
    case 'Registro de Observaciones':
        echo '<script src="./view/js/registroObservaciones.js" type="module"></script>';
        break;
} ?>

</body>

</html>