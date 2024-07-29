<!-- Modal Edit -->
<section class="modal" id="regAssistEdit">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Editar Asistencia</h1>
        </div>
        <form id="regAssistEditForm" action="" class="form">
            <input type="text" name="regAssistIdEdit" id="regAssistIdEdit">
            <input type="number" id="traineesAssistanceSearch" placeholder="Buscar por documento">
            <div id="resultsTraineesAssistanceSearch" class="resultsTraineesSearch"></div>
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Fecha Asistencia</label>
                <input class="inputGroupInput" type="date" name="date" id="date" autocomplete="off" required>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="selectedRoom"><i class="bi bi-door-open"></i></label>
                <select class="inputGroupSelect" name="selectedRoom" id="selectedRoom" required>
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

<!-- Modal Export PDF Registro Asistencias -->
<section class="modal" id="regAssistExportPdf">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Registros PDF</h1>
        </div>
        <form id="regAssistExportFormPdf" action="" class="form">
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

<!-- Modal Export Excel Registro Asistencias -->
<section class="modal" id="regAssistExportExcel">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Registros Excel</h1>
        </div>
        <form id="regAssistExportFormExcel" action="" class="form">
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