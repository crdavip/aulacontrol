<!-- Modal Create -->
<section class="modal" id="roomCreate">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Nuevo Ambiente</h1>
        </div>
        <form id="roomCreateForm" action="" class="form">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="numRoom" id="numRoom" autocomplete="off" required>
                <label class="inputGroupLabel" for="numRoom"><i class="fa-solid fa-list-ol"></i> Número</label>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="centerRoom"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="centerRoom" id="centerRoom" required>
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

<!-- Modal Export PDF -->
<section class="modal" id="roomExportPdf">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Registros PDF</h1>
        </div>
        <form id="roomExportFormPdf" action="" class="form">
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Desde</label>
                <input class="inputGroupInput" type="date" name="startDatePdf" id="startDatePdf" autocomplete="off" required>
            </div>
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Hasta</label>
                <input class="inputGroupInput" type="date" name="endDatePdf" id="endDatePdf" autocomplete="off" required>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="selectedRoomPdf"><i class="bi bi-door-open"></i></label>
                <select class="inputGroupSelect" name="selectedRoomPdf" id="selectedRoomPdf" required>
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

<!-- Modal Export Excel -->
<section class="modal" id="roomExportExcel">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Registros Excel</h1>
        </div>
        <form id="roomExportFormExcel" action="" class="form">
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Desde</label>
                <input class="inputGroupInput" type="date" name="startDateExcel" id="startDateExcel" autocomplete="off" required>
            </div>
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Hasta</label>
                <input class="inputGroupInput" type="date" name="endDateExcel" id="endDateExcel" autocomplete="off" required>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="selectedRoomExcel"><i class="bi bi-door-open"></i></label>
                <select class="inputGroupSelect" name="selectedRoomExcel" id="selectedRoomExcel" required>
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