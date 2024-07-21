<!-- Modal Create -->
<section class="modal" id="dataSheetCreate">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-table"></i>
            <h1>Nueva Ficha</h1>
        </div>
        <form id="dataSheetCreateForm" action="" class="form">
            <div class="inputGroup">
                <input class="inputGroupInput" type="number" name="dataSheetNum" id="dataSheetNum" autocomplete="off" required>
                <label class="inputGroupLabel" for="dataSheetNum"><i class="fa-solid fa-hashtag"></i> Ficha</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="dataSheetCourse" id="dataSheetCourse" autocomplete="off" required>
                <label class="inputGroupLabel" for="dataSheetCourse"><i class="fa-solid fa-book"></i> Curso</label>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="dataSheetCenter"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="dataSheetCenter" id="dataSheetCenter" required>
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
<section class="modal" id="dataSheetEdit">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-table"></i>
            <h1>Editar Ficha</h1>
        </div>
        <form id="dataSheetEditForm" action="" class="form">
            <input type="hidden" name="dataSheetIdEdit" id="dataSheetIdEdit">
            <div class="inputGroup">
                <input class="inputGroupInput" type="number" name="dataSheetNumEdit" id="dataSheetNumEdit" autocomplete="off" required>
                <label class="inputGroupLabel" for="dataSheetNumEdit"><i class="fa-solid fa-hashtag"></i> Ficha</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="dataSheetCourseEdit" id="dataSheetCourseEdit" autocomplete="off" required>
                <label class="inputGroupLabel" for="dataSheetCourseEdit"><i class="fa-solid fa-book"></i> Curso</label>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="dataSheetCenterEdit"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="dataSheetCenterEdit" id="dataSheetCenterEdit" required>
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
<section class="modal" id="dataSheetDelete">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-table"></i>
            <h1>Eliminar Ambiente</h1>
        </div>
        <form id="dataSheetDeleteForm" action="" class="form">
            <input type="hidden" name="dataSheetIdDelete" id="dataSheetIdDelete">
            <p class="modalTxt">Esta acción no se puede revertir. <strong>¿Desea continuar?</strong></p>
            <div class="buttonGroup">
                <button id="confirmDeleteButton" class="btn btnAlt" type="submit"><i class="bi bi-trash3"></i> Eliminar</button>
            </div>
        </form>
        <p id="messageDelete" class="message"></p>
    </div>
</section>