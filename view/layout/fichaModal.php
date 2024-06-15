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
<section class="modal" id="roomEdit">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Editar Ambiente</h1>
        </div>
        <form id="roomEditForm" action="" class="form">
            <input type="hidden" name="roomIdEdit" id="roomIdEdit">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="numRoomEdit" id="numRoomEdit" autocomplete="off" required>
                <label class="inputGroupLabel" for="numRoomEdit"><i class="fa-solid fa-list-ol"></i> Número</label>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="centerRoomEdit"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="centerRoomEdit" id="centerRoomEdit" required>
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
<section class="modal" id="roomDelete">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Eliminar Ambiente</h1>
        </div>
        <form id="roomDeleteForm" action="" class="form">
            <input type="hidden" name="roomIdDelete" id="roomIdDelete">
            <p class="modalTxt">Esta acción no se puede revertir. <strong>¿Desea continuar?</strong></p>
            <div class="buttonGroup">
                <button id="confirmDeleteButton" class="btn btnAlt" type="submit"><i class="bi bi-trash3"></i> Eliminar</button>
            </div>
        </form>
        <p id="messageDelete" class="message"></p>
    </div>
</section>

<!-- Modal Assoc -->
<section class="modal" id="roomAssoc">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-qrcode"></i>
            <h1 id="titleRoomAssoc"></h1>
        </div>
        <div class="roomAssocInfo"></div>
        <div class="containerQr"></div>
        <p id="messageRoomAssoc" class="message"></p>
    </div>
</section>