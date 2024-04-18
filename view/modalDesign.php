<!-- Modal View -->
<section class="modal" id="designView">
    <div class="containerModal">
        <button class="closeModal"><i class="bi bi-x-lg"></i></button>
        <div class="titlePg">
            <h1></h1>
            <span></span>
            <p></p>
        </div>
        <img id="designViewImg" src="" alt="">
    </div>
</section>

<!-- Modal Create -->
<section class="modal" id="designCreate">
    <div class="containerModal">
        <button class="closeModal"><i class="bi bi-x-lg"></i></button>
        <div class="titlePg">
            <i class="bi bi-folder-plus"></i>
            <h1>Nuevo Diseño</h1>
        </div>
        <form id="designCreateForm" action="" class="usersForm" enctype="multipart/form-data">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="nameDesign" id="nameDesign" autocomplete="off" required>
                <label class="inputGroupLabel" for="nameDesign"><i class="bi bi-pencil"></i> Titulo</label>
            </div>
            <div class="inputGroup uploadImg">
                <input type="file" name="designImg" id="designImg" accept="image/png, image/jpeg" hidden>
                <div class="designImgPic">
                    <a class="designImgUpload" id="designImgUpload"><i class="bi bi-folder-plus"></i></a>
                    <h3>Subir Diseño</h3>
                    <p>El formato de la imagen debe ser <span>JPG o PNG</span></p>
                    <img class="designImgPreview" id="designImgPreview" src="" alt="">
                </div>
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
<section class="modal" id="designEdit">
    <div class="containerModal">
        <button class="closeModal"><i class="bi bi-x-lg"></i></button>
        <div class="titlePg">
            <i class="bi-pencil-square"></i>
            <h1>Editar Diseño</h1>
        </div>
        <form id="designEditForm" action="" class="usersForm" enctype="multipart/form-data">
            <input type="hidden" name="designIdEdit" id="designIdEdit">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="nameDesignEdit" id="nameDesignEdit" autocomplete="off" required>
                <label class="inputGroupLabel" for="nameDesignEdit"><i class="bi bi-pencil"></i> Titulo</label>
            </div>
            <div class="inputGroup uploadImg">
                <input type="file" name="designImgEdit" id="designImgEdit" accept="image/png, image/jpeg" hidden>
                <div class="designImgPic">
                    <a class="designImgUpload" id="designImgUploadEdit"><i class="bi bi-folder-plus"></i></a>
                    <h3>Subir Diseño</h3>
                    <p>El formato de la imagen debe ser <span>JPG o PNG</span></p>
                    <img class="designImgPreview" id="designImgPreviewEdit" src="" alt="">
                </div>
            </div>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="bi bi-floppy"></i> Guardar</button>
            </div>
        </form>
        <p id="messageEdit" class="message"></p>
    </div>
</section>

<!-- Modal Delete -->
<section class="modal" id="designDelete">
    <div class="containerModal">
        <button class="closeModal"><i class="bi bi-x-lg"></i></button>
        <div class="titlePg">
            <i class="bi bi-file-earmark-excel"></i>
            <h1>Eliminar Diseño</h1>
        </div>
        <form id="designDeleteForm" action="" class="usersForm">
            <input type="hidden" name="designIdDelete" id="designIdDelete">
            <input type="hidden" name="designImgDelete" id="designImgDelete">
            <p class="modalTxt">Esta acción no se puede revertir. <strong>¿Desea continuar?</strong></p>
            <div class="buttonGroup">
                <button id="confirmDeleteButton" class="btn btnAlt" type="submit"><i class="bi bi-trash3"></i> Eliminar</button>
            </div>
        </form>
        <p id="messageDelete" class="message"></p>
    </div>
</section>