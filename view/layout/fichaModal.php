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
                <input class="inputGroupInput" type="number" name="dataSheetNum" id="dataSheetNum" autocomplete="off"
                    required>
                <label class="inputGroupLabel" for="dataSheetNum"><i class="fa-solid fa-hashtag"></i> Ficha</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="dataSheetCourse" id="dataSheetCourse"
                    autocomplete="off" required>
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
                <input class="inputGroupInput" type="number" name="dataSheetNumEdit" id="dataSheetNumEdit"
                    autocomplete="off" required>
                <label class="inputGroupLabel" for="dataSheetNumEdit"><i class="fa-solid fa-hashtag"></i> Ficha</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="dataSheetCourseEdit" id="dataSheetCourseEdit"
                    autocomplete="off" required>
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
                <button id="confirmDeleteButton" class="btn btnAlt" type="submit"><i class="bi bi-trash3"></i>
                    Eliminar</button>
            </div>
        </form>
        <p id="messageDelete" class="message"></p>
    </div>
</section>

<!-- Modal Assistance Trainees -->
<section class="modal" id="dataSheetAssistanceTrainees">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-clipboard-list"></i>
            <h1>Asistencia</h1>
        </div>
        <input type="hidden" id="inputIdSheetAssistance">
        <input type="text" id="traineesAssistanceSearch" placeholder="Buscar por documento">
        <div id="resultsTraineesAssistanceSearch" class="resultsTraineesSearch"></div>
        <p id="messageSheetAssistance" class="message"></p>
    </div>
</section>

<!-- Modal List Trainees -->
<section class="modal" id="dataSheetListTrainees">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-user"></i>
            <h1>Lista</h1>
        </div>
        <input type="hidden" id="inputIdSheetList">
        <input type="text" id="traineesListSearch" placeholder="Buscar por documento">
        <div id="resultsTraineesListSearch" class="resultsTraineesSearch"></div>
        <div class="buttonGroup">
            <button id="openModalAdd" onclick="openModal(`dataSheetAddTrainees`); closeModal(`dataSheetListTrainees`)" class="btn btnAlt" type="submit"><i class="fa-solid fa-square-plus"></i>
                Crear Nuevo</button>
        </div>
        <p id="messageSheetList" class="message"></p>
    </div>
</section>

<!-- Modal Add Trainees -->
<section class="modal" id="dataSheetAddTrainees">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-user-plus"></i>
            <h1>Nuevo Aprendiz</h1>
        </div>
        <input type="hidden" id="inputIdSheetAdd">
        <input type="text" id="traineesAddSearch" placeholder="Buscar por documento">
        <div id="resultsTraineesAddSearch" class="resultsTraineesSearch"></div>
        <div class="buttonGroup">
            <button id="saveTraineesSelected" class="btn btnAlt" type="submit"><i class="fa-solid fa-floppy-disk"></i>
                Guardar</button>
        </div>
        <p id="messageSheetAdd" class="message"></p>
    </div>
</section>

<!-- Modal Remover aprendiz de la ficha -->
<section class="modal" id="dataSheetremoveTrainee">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-user"></i>
            <h1>Remover Aprendiz</h1>
        </div>
        <form id="dataSheetRemoveTraineeForm" action="" class="form">
            <input type="hidden" name="dataSheetIdRemoveTrainee" id="dataSheetIdRemoveTrainee">
            <input type="hidden" name="dataSheetIdRemoveSheet" id="dataSheetIdRemoveSheet">
            <p class="modalTxt">Esta acción no se puede revertir. <strong>¿Desea continuar?</strong></p>
            <div class="buttonGroup">
                <button id="confirmRemoveButton" class="btn btnAlt" type="submit"><i class="bi bi-trash3"></i>
                    Eliminar</button>
            </div>
        </form>
        <p id="messageRemove" class="message"></p>
    </div>
</section>