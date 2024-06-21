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
        <form id="userProfileForm" action="" class="form" enctype="multipart/form-data">
            <div class="inputGroup uploadProfileImg">
                <input type="file" name="userImgProfile" id="userImgProfile" accept="image/jpeg, image/png" hidden>
                <div class="userImgPreview" id="userImgPreview">
                    <a class="userImgUpload" id="userImgUpload"><i class="fa-solid fa-camera"></i></a>
                    <div class="userImgPicContent">
                        <img class="userImgPic" id="userImgPic" src="<?php echo $userImg; ?>" alt="">
                    </div>
                </div>
            </div>
            <input type="hidden" name="userIdProfile" id="userIdProfile" value="<?php echo $userId; ?>">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="userNameProfile" id="userNameProfile" value="<?php echo $userName; ?>" autocomplete="off" required>
                <label class="inputGroupLabel" for="userNameProfile"><i class="fa-solid fa-user"></i> Nombre</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="number" name="userDocProfile" id="userDocProfile" value="<?php echo $userDoc; ?>" autocomplete="off" required>
                <label class="inputGroupLabel" for="userDocProfile"><i class="fa-solid fa-id-card"></i> Documento</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="email" name="userMailProfile" id="userMailProfile" value="<?php echo $userEmail; ?>" autocomplete="off" required>
                <label class="inputGroupLabel" for="userMailProfile"><i class="fa-solid fa-envelope"></i> Correo</label>
            </div>
            <div class="buttonGroup">
                <button class="btn btnAlt" onclick="openModal('userPassEdit'); closeModal('userProfile');" type="button"><i class="fa-solid fa-arrows-rotate"></i> Cambiar Contraseña</button>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="fa-solid fa-save"></i> Guardar</button>
            </div>
        </form>
        <p id="messageUserProfile" class="message"></p>
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
                <label class="inputGroupLabel" for="userPassProfile"><i class="fa-solid fa-lock"></i> Nueva Contraseña</label>
                <a class="showPass" onclick="showPass('userPassProfile', this)"><i class="fa-solid fa-eye"></i></i></a>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="password" name="userPassProfileTwo" id="userPassProfileTwo" autocomplete="off" required>
                <label class="inputGroupLabel" for="userPassProfileTwo"><i class="fa-solid fa-lock"></i> Repetir Contraseña</label>
                <a class="showPass" onclick="showPass('userPassProfileTwo', this)"><i class="fa-solid fa-eye"></i></i></a>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="fa-solid fa-save"></i> Guardar</button>
            </div>
        </form>
        <p id="messageUserPassEdit" class="message"></p>
    </div>
</section>

<?php $connPDO->closeConn(); ?>
<audio id="beepQr" src="./view/sound/scanner.mp3"></audio>
<script src="./view/js/main.js"></script>
<script src="./view/js/perfil.js"></script>
<?php switch ($titlePg) {
    case 'Ambientes':
        echo '<script src="./view/js/ambientes.js"></script>';
        break;
    case 'Registro de Ambientes':
        echo '<script src="./view/js/registroAmbientes.js" type="module"></script>';
        break;
    case 'Fichas':
        echo '<script src="./view/js/fichas.js"></script>';
        break;
    case 'Equipos de Ambiente':
        echo '<script src="./view/js/equiposAmbientes.js" type="module"></script>';
} ?>

</body>

</html>