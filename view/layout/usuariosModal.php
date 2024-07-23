<!-- Modal Create -->
<section class="modal" id="userCreate">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-user-plus"></i>
            <h1>Nuevo Usuario</h1>
        </div>
        <form id="userCreateForm" action="" class="form">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="nameUser" id="nameUser" autocomplete="off" required>
                <label class="inputGroupLabel" for="nameUser"><i class="fa-solid fa-user"></i> Nombre Completo</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="number" name="docUser" id="docUser" autocomplete="off" required>
                <label class="inputGroupLabel" for="docUser"><i class="fa-solid fa-id-card"></i> Documento</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="mail" name="mailUser" id="mailUser" autocomplete="off" required>
                <label class="inputGroupLabel" for="mailUser"><i class="fa-solid fa-envelope"></i> Correo</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="password" name="passUser" id="passUser" autocomplete="off" required>
                <label class="inputGroupLabel" for="passUser" id="labelPass"><i class="fa-solid fa-lock"></i> Contraseña</label>
                <a class="showPass" onclick="showPass('passUser', this)"><i class="fa-solid fa-eye"></i></a>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="password" name="pass2User" id="pass2User" autocomplete="off" required>
                <label class="inputGroupLabel" for="pass2User" id="labelPass"><i class="fa-solid fa-lock"></i> Repetir Contraseña</label>
                <a class="showPass" onclick="showPass('pass2User', this)"><i class="fa-solid fa-eye"></i></a>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="rolUser"><i class="fa-solid fa-briefcase"></i></label>
                <select class="inputGroupSelect" name="rolUser" id="rolUser" required>
                    <option value="">Seleccione un Cargo</option>
                </select>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="centerUser"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="centerUser" id="centerUser" required>
                    <option value="">Seleccione un Centro</option>
                </select>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="fa-solid fa-square-plus"></i> Crear</button>
                <button class="btn btnAlt" type="reset"><i class="fa-solid fa-eraser"></i> Limpiar</button>
            </div>
        </form>
        <p id="messageCreate" class="message"></p>
    </div>
</section>

<!-- Modal Edit -->
<section class="modal" id="userEdit">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-user-gear"></i>
            <h1>Editar Usuario</h1>
        </div>
        <form id="userEditForm" action="" class="form">
            <input type="hidden" name="userIdEdit" id="userIdEdit">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="nameUserEdit" id="nameUserEdit" autocomplete="off" required>
                <label class="inputGroupLabel" for="nameUserEdit"><i class="fa-solid fa-user"></i> Nombre Completo</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="number" name="docUserEdit" id="docUserEdit" autocomplete="off" required>
                <label class="inputGroupLabel" for="docUserEdit"><i class="fa-solid fa-id-card"></i> Documento</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="mail" name="mailUserEdit" id="mailUserEdit" autocomplete="off" required>
                <label class="inputGroupLabel" for="mailUserEdit"><i class="fa-solid fa-envelope"></i> Correo</label>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="rolUserEdit"><i class="fa-solid fa-briefcase"></i></label>
                <select class="inputGroupSelect" name="rolUserEdit" id="rolUserEdit" required>
                    <option value="">Seleccione un Cargo</option>
                </select>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="centerUserEdit"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="centerUserEdit" id="centerUserEdit" required>
                    <option value="">Seleccione un Centro</option>
                </select>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                <button class="btn btnAlt" type="reset"><i class="fa-solid fa-eraser"></i> Limpiar</button>
            </div>
        </form>
        <p id="messageEdit" class="message"></p>
    </div>
</section>

<!-- Modal Delete -->
<section class="modal" id="userDelete">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-user-minus"></i>
            <h1>Eliminar Usuario</h1>
        </div>
        <form id="userDeleteForm" action="" class="form">
            <input type="hidden" name="userIdDelete" id="userIdDelete">
            <p class="modalTxt">Esta acción no se puede revertir. <strong>¿Desea continuar?</strong></p>
            <div class="buttonGroup">
                <button id="confirmDeleteButton" class="btn btnAlt" type="submit"><i class="bi bi-trash3"></i> Eliminar</button>
            </div>
        </form>
        <p id="messageDelete" class="message"></p>
    </div>
</section>

<!-- Modal Import -->
<section class="modal" id="userImport">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-file-csv"></i>
            <h1>Importar Usuarios</h1>
        </div>
        <form id="userImportForm" action="" class="form" enctype="multipart/form-data">
            <p class="modalTxt">Recuerde usar la plantilla recomendada para importar usuarios.</p>
            <a href="./view/docs/plantillaUsuarios.csv" class="modalLink"><i class="fa-solid fa-download"></i> Descargar Plantilla</a>
            <input  type="file" name="dataUsers" id="dataUsers" accept="text/csv"/>
            <div class="buttonGroup">
                <button id="uploadDataUsers" class="btn" type="submit"><i class="fa-solid fa-file-arrow-up"></i> Cargar</button>
            </div>
        </form>
        <p id="messageImport" class="message"></p>
    </div>
</section>