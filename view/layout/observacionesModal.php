<!-- Modal Create -->
<section class="modal" id="observationCreate">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-binoculars"></i>
            <h1>Nueva Observación</h1>
        </div>
        <form id="observationCreateForm" action="" class="form">
            <div class="inputGroup">
                <label class="inputGroupLabel" for="centerObject"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="typeSubject" id="typeSubject" required>
                    <option value="">Tipo de Asunto</option>
                    <option value="EQUIPOS">Equipos</option>
                    <option value="AMBIENTES">Ambientes</option>
                    <option value="OBJETOS">Objetos</option>
                </select>
            </div>
            <p>Descripción</p>
            <textarea cols="4" rows="8" type="text" class="textAreaObs" name="descriptionObservation"
                id="descriptionObservation" autocomplete="off" required></textarea>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="fa-solid fa-square-plus"></i> Enviar</button>
                <button class="btn btnAlt" type="reset"><i class="fa-solid fa-eraser"></i> Limpiar</button>
            </div>
        </form>
        <p id="messageCreate" class="message"></p>
    </div>
</section>

<!-- Modal Observation Checked -->
<section class="modal" id="markObsChecked">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-regular fa-circle-check"></i>
            <h1>Marcar Revisión</h1>
        </div>
        <form id="markObsCheckedForm" action="" class="form">
            <input type="hidden" name="observationId" id="observationId">
            <p class="modalTxt">Se establecerá la observación como revisada. <strong>¿Desea continuar?</strong></p>
            <h4>(Las observaciones revisadas se pueden visualizar en los registros)</h4>
            <div class="buttonGroup">
                <button id="confirmCheckButton" class="btn btnAlt" type="submit"><i
                        class="fa-regular fa-circle-check"></i>
                    Confirmar</button>
            </div>
        </form>
        <p id="messageObsChecked" class="message"></p>
    </div>
</section>

<!-- Modal Export Excel Registro observaciones -->
<section class="modal" id="regObsExportExcel">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Observaciones Excel</h1>
        </div>
        <form id="regObsExportFormExcel" action="" class="form">
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Desde</label>
                <input class="inputGroupInput" type="date" name="startDateExcel" id="startDateExcel" autocomplete="off"
                    required>
            </div>
            <div class="inputGroup">
                <label><i class="fa-regular fa-calendar"></i> Hasta</label>
                <input class="inputGroupInput" type="date" name="endDateExcel" id="endDateExcel" autocomplete="off"
                    required>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="selectedTypeObs"><i class="fa-solid fa-cubes"></i></label>
                <select class="inputGroupSelect" name="selectedTypeObs" id="selectedTypeObs" required>
                    <option value="All">Todos</option>
                    <option value="EQUIPOS">Equipos</option>
                    <option value="AMBIENTES">Ambientes</option>
                    <option value="OBJETOS">Objetos</option>
                </select>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="selectedStateObs"><i
                        class="fa-solid fa-circle-half-stroke"></i></label>
                <select class="inputGroupSelect" name="selectedStateObs" id="selectedStateObs" required>
                    <option value="All">Todos</option>
                    <option value="0">Pendientes</option>
                    <option value="1">Revisados</option>
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