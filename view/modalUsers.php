<!-- Modal Create -->
<?php
$sql = "SELECT ROL_ID, DESCRIPCION FROM rol";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$resultsRol = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="modal" id="usersCreate">
    <div class="containerModal">
        <button class="closeModal"><i class="bi bi-x-lg"></i></button>
        <div class="titlePg">
            <i class="bi bi bi-person-add"></i>
            <h1>Crear Usuario</h1>
        </div>
        <form id="usersCreateForm" action="" class="usersForm">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="names" id="names" autocomplete="off" required>
                <label class="inputGroupLabel" for="names"><i class="bi bi-person"></i> Nombre</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="number" name="doc" id="doc" autocomplete="off" required>
                <label class="inputGroupLabel" for="doc"><i class="bi bi-credit-card"></i> Documento</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="email" name="mail" id="mail" autocomplete="off" required>
                <label class="inputGroupLabel" for="mail"><i class="bi bi-envelope"></i> Correo</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="password" name="pass" id="pass" autocomplete="off" required>
                <label class="inputGroupLabel" for="pass"><i class="bi bi-lock"></i> Contraseña</label>
                <a class="showPass" onclick="showPass('pass', this)"><i class="bi bi-eye"></i></a>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="password" name="passTwo" id="passTwo" autocomplete="off" required>
                <label class="inputGroupLabel" for="passTwo"><i class="bi bi-lock"></i> Repetir Contraseña</label>
                <a class="showPass" onclick="showPass('passTwo', this)"><i class="bi bi-eye"></i></a>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="role"><i class="bi bi-briefcase"></i></label>
                <select class="inputGroupSelect" name="role" id="role" required>
                    <option value="">Seleccione un Rol</option>
                    <?php foreach ($resultsRol as $rowUsersRol) {
                        if ($rowUsersRol['ROL_ID'] != 1) { ?>
                            <option value="<?php echo $rowUsersRol['ROL_ID']; ?>"><?php echo $rowUsersRol['DESCRIPCION']; ?></option>
                    <?php }
                    } ?>
                </select>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Crear</button>
                <button class="btn btnAlt" type="reset"><i class="bi bi-eraser"></i> Limpiar</button>
            </div>
        </form>
        <p id="messageCreate" class="message"></p>
    </div>
</section>

<!-- Modal Edit -->
<section class="modal" id="usersEdit">
    <div class="containerModal">
        <button class="closeModal"><i class="bi bi-x-lg"></i></button>
        <div class="titlePg">
            <i class="bi bi-person-gear"></i>
            <h1>Editar Usuario</h1>
        </div>
        <form id="usersEditForm" action="" class="usersForm">
            <input type="hidden" name="userId" id="userId">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="namesEdit" id="namesEdit" value="" autocomplete="off" required>
                <label class="inputGroupLabel" for="namesEdit"><i class="bi bi-person"></i> Nombre</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="number" name="docEdit" id="docEdit" value="" autocomplete="off" required>
                <label class="inputGroupLabel" for="docEdit"><i class="bi bi-credit-card"></i> Documento</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="email" name="mailEdit" id="mailEdit" autocomplete="off" required>
                <label class="inputGroupLabel" for="mailEdit"><i class="bi bi-envelope"></i> Correo</label>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="roleEdit"><i class="bi bi-briefcase"></i></label>
                <select class="inputGroupSelect" name="roleEdit" id="roleEdit" required>
                    <option value="">Seleccione un Rol</option>
                    <?php foreach ($resultsRol as $rowUsersRol) { ?>
                        <option value="<?php echo $rowUsersRol['ROL_ID']; ?>"><?php echo $rowUsersRol['DESCRIPCION']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="buttonGroup">
                <button class="btn btnAlt" id="btnPassEdit" type="button"><i class="bi bi-lock"></i> Cambiar Contraseña</button>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="bi bi-floppy"></i> Guardar</button>
            </div>
        </form>
        <p id="messageEdit" class="message"></p>
    </div>
</section>

<!-- Modal ChangePass -->
<section class="modal" id="usersPassEdit">
    <div class="containerModal">
        <button class="closeModal"><i class="bi bi-x-lg"></i></button>
        <div class="titlePg">
            <i class="bi bi-person"></i>
            <h2 id="namesPassEdit"></h2>
        </div>
        <form id="usersPassEditForm" action="" class="usersForm">
            <input type="hidden" name="userPassId" id="userPassId">
            <div class="inputGroup">
                <input class="inputGroupInput" type="password" name="passEdit" id="passEdit" autocomplete="off" required>
                <label class="inputGroupLabel" for="passEdit"><i class="bi bi-lock"></i> Nueva Contraseña</label>
                <a class="showPass" onclick="showPass('passEdit', this)"><i class="bi bi-eye"></i></a>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="password" name="passEditTwo" id="passEditTwo" autocomplete="off" required>
                <label class="inputGroupLabel" for="passEditTwo"><i class="bi bi-lock"></i> Repetir Contraseña</label>
                <a class="showPass" onclick="showPass('passEditTwo', this)"><i class="bi bi-eye"></i></a>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="bi bi-floppy"></i> Guardar</button>
            </div>
        </form>
        <p id="messagePassEdit" class="message"></p>
    </div>
</section>

<!-- Modal Delete -->
<section class="modal" id="usersDelete">
    <div class="containerModal">
        <button class="closeModal"><i class="bi bi-x-lg"></i></button>
        <div class="titlePg">
            <i class="bi bi-person-dash"></i>
            <h1>Eliminar Usuario</h1>
        </div>
        <form id="usersDeleteForm" action="" class="usersForm">
            <input type="hidden" name="userIdDelete" id="userIdDelete">
            <p class="modalTxt">Esta acción no se puede revertir. <strong>¿Desea continuar?</strong></p>
            <div class="buttonGroup">
                <button id="confirmDeleteButton" class="btn btnAlt" type="submit"><i class="bi bi-trash3"></i> Eliminar</button>
            </div>
        </form>
        <p id="messageDelete" class="message"></p>
    </div>
</section>