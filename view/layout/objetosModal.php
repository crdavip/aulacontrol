<!-- Modal Create -->
<section class="modal" id="createObject">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-cube"></i>
            <h1>Nuevo Objeto</h1>
        </div>
        <form id="createObjectForm" action="" class="form">
            <div class="inputGroup">
                <textarea cols="4" rows="4" class="inputGroupInput" type="text" name="descriptionObject"
                    id="descriptionObject" autocomplete="off" required></textarea>
                <label class="inputGroupLabel" for="descriptionObject"><i class="fa-regular fa-file-lines"></i> Descripción</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="colorObject" id="colorObject" autocomplete="off"
                    required>
                <label class="inputGroupLabel" for="colorObject"><i class="fa-solid fa-droplet"></i> Color</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="userObject" id="userObject" autocomplete="off"
                    required>
                <label class="inputGroupLabel" for="userObject"><i class="fa-solid fa-user"></i> Documento Usuario</label>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="centerObject"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="centerObject" id="centerObject" required>
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
<section class="modal" id="editObject">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-cube"></i>
            <h1>Editar Objeto</h1>
        </div>
        <form id="objectEditForm" action="" class="form">
            <input type="hidden" name="objectIdEdit" id="objectIdEdit">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="objectDescriptionEdit" id="objectDescriptionEdit"
                    autocomplete="off" required>
                <label class="inputGroupLabel" for="objectDescriptionEdit"><i class="fa-solid fa-list-ol"></i>
                    Descripción</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="objectColorEdit" id="objectColorEdit"
                    autocomplete="off" required>
                <label class="inputGroupLabel" for="objectColorEdit"><i class="fa-solid fa-list-ol"></i> Color</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="userObjectEdit" id="userObjectEdit" autocomplete="off"
                    required>
                <label class="inputGroupLabel" for="userObjectEdit"><i class="fa-solid fa-user"></i> Documento Usuario</label>
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
<section class="modal" id="deleteObject">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-cube"></i>
            <h1>Eliminar Objeto</h1>
        </div>
        <form id="objectDeleteForm" action="" class="form">
            <input type="hidden" name="objectIdDelete" id="objectIdDelete">
            <p class="modalTxt">Se eliminará el objeto y todos los registros asociados. <strong>¿Desea continuar?</strong></p>
            <div class="buttonGroup">
                <button id="confirmDeleteButton" class="btn btnAlt" type="submit"><i class="fa-solid fa-trash-can"></i>
                    Eliminar</button>
            </div>
        </form>
        <p id="messageDelete" class="message"></p>
    </div>
</section>

<!-- Modal ExitMark -->
<section class="modal" id="exitObjectMark">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-cube"></i>
            <h1>Salida de Objeto</h1>
        </div>
        <form id="objectExitMark" action="" class="form">
            <input type="hidden" name="objectIdExitMark" id="objectIdExitMark">
            <input type="hidden" name="objectIdUser" id="objectIdUser"/>
            <p class="modalTxt">Esta acción no se puede revertir. <br><strong>¿Desea marcar la salida del objeto?</strong></p>
            <div class="buttonGroup">
                <button id="confirmExitMarkButton" class="btn btnAlt" type="submit"><i class="fa-regular fa-circle-check"></i>
                    Aceptar</button>
            </div>
        </form>
        <p id="messageExitMark" class="message"></p>
    </div>
</section>

<!-- Modal EntranceMark -->
<section class="modal" id="entranceObjectMark">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-cube"></i>
            <h1>Entrada de Objeto</h1>
        </div>
        <form id="objectEntranceMark" action="" class="form">
            <input type="hidden" name="objectIdEntranceMark" id="objectIdEntranceMark">
            <input type="hidden" name="objectIdEntranceCenter" id="objectIdEntranceCenter"/>
            <p class="modalTxt">Vas a crear un nuevo registro de entrada de este objeto. <br><strong>¿Desea Continuar?</strong></p>
            <div class="buttonGroup">
                <button id="confirmEntranceMarkButton" class="btn btnAlt" type="submit"><i class="fa-regular fa-circle-check"></i>
                    Aceptar</button>
            </div>
        </form>
        <p id="messageEntranceMark" class="message"></p>
    </div>
</section>

<!-- Modal Export PDF Registro Objetos -->
<section class="modal" id="regObjectExportPdf">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Registros PDF</h1>
        </div>
        <form id="regObjectExportFormPdf" action="" class="form">
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Desde</label>
                <input class="inputGroupInput" type="date" name="startDatePdf" id="startDatePdf" autocomplete="off" required>
            </div>
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Hasta</label>
                <input class="inputGroupInput" type="date" name="endDatePdf" id="endDatePdf" autocomplete="off" required>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="selectedCenterPdf"><i class="fa-solid fa-door-open"></i></label>
                <select class="inputGroupSelect" name="selectedCenterPdf" id="selectedCenterPdf" required>
                </select>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="fa-solid fa-square-plus"></i> Crear</button>
                <button class="btn btnAlt" type="reset"><i class="fa-solid fa-eraser"></i> Limpiar</button>
            </div>
        </form>
        <p id="messageRoomExport" class="message"></p>
    </div>
</section>

<!-- Modal Export Excel Registro Objetos -->
<section class="modal" id="regObjectExportExcel">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Registros Excel</h1>
        </div>
        <form id="regObjectExportFormExcel" action="" class="form">
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Desde</label>
                <input class="inputGroupInput" type="date" name="startDateExcel" id="startDateExcel" autocomplete="off" required>
            </div>
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Hasta</label>
                <input class="inputGroupInput" type="date" name="endDateExcel" id="endDateExcel" autocomplete="off" required>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="selectedCenterExcel"><i class="fa-solid fa-door-open"></i></label>
                <select class="inputGroupSelect" name="selectedCenterExcel" id="selectedCenterExcel" required>
                </select>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="fa-solid fa-square-plus"></i> Crear</button>
                <button class="btn btnAlt" type="reset"><i class="fa-solid fa-eraser"></i> Limpiar</button>
            </div>
        </form>
        <p id="messageRoomExport" class="message"></p>
    </div>
</section>