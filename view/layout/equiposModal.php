<!-- Modal Create -->
<section class="modal" id="createDevice">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-desktop"></i>
            <h1>Nuevo Equipo</h1>
        </div>
        <form id="createDeviceForm" action="" class="form">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="refDevice" id="refDevice" autocomplete="off" required>
                <label class="inputGroupLabel" for="refDevice"><i class="bi bi-tag"></i> Referencia</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="brandDevice" id="brandDevice" autocomplete="off"
                    required>
                <label class="inputGroupLabel" for="brandDevice"><i class="bi bi-tag"></i> Marca</label>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="centerDevice"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="centerDevice" id="centerDevice" required>
                    <!-- <option value="">Seleccione un Centro</option> -->
                </select>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="idRoom"><i class="bi bi-door-open"></i></label>
                <select class="inputGroupSelect" name="idRoom" id="idRoom" required>
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
<section class="modal" id="editDevice">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-desktop"></i>
            <h1>Editar Ambiente</h1>
        </div>
        <form id="deviceEditForm" action="" class="form">
            <input type="hidden" name="deviceIdEdit" id="deviceIdEdit">
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="deviceRefEdit" id="deviceRefEdit" autocomplete="off"
                    required>
                <label class="inputGroupLabel" for="deviceRefEdit"><i class="fa-solid fa-list-ol"></i> Referencia</label>
            </div>
            <div class="inputGroup">
                <input class="inputGroupInput" type="text" name="deviceBrandEdit" id="deviceBrandEdit" autocomplete="off"
                    required>
                <label class="inputGroupLabel" for="deviceBrandEdit"><i class="fa-solid fa-list-ol"></i> Marca</label>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="centerDevice"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="centerDeviceEdit" id="centerDeviceEdit" required>
                </select>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="deviceAmbEdit"><i class="bi bi-door-open"></i></label>
                <select class="inputGroupSelect" name="deviceAmbEdit" id="deviceAmbEdit" required>
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
<section class="modal" id="deleteDevice">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-desktop"></i>
            <h1>Eliminar Equipo</h1>
        </div>
        <form id="deviceDeleteForm" action="" class="form">
            <input type="hidden" name="deviceIdDelete" id="deviceIdDelete">
            <p class="modalTxt">Esta acción no se puede revertir. <strong>¿Desea continuar?</strong></p>
            <div class="buttonGroup">
                <button id="confirmDeleteButton" class="btn btnAlt" type="submit"><i class="bi bi-trash3"></i>
                    Eliminar</button>
            </div>
        </form>
        <p id="messageDelete" class="message"></p>
    </div>
</section>

<!-- Modal Assoc -->
<section class="modal" id="deviceAssoc">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-qrcode"></i>
            <h1 id="titleDeviceAssoc"></h1>
        </div>
        <div class="deviceAssocInfo"></div>
        <div class="containerQr"></div>
        <p id="messageDeviceAssoc" class="message"></p>
    </div>
</section>

<!-- Modal Export PDF Equipos -->
<section class="modal" id="deviceExportPdf">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Registros PDF</h1>
        </div>
        <form id="deviceExportFormPdf" action="" class="form">
            <div class="inputGroup">
                <label class="inputGroupLabel" for="selectedRoomDevicesPdf"><i class="bi bi-door-open"></i></label>
                <select class="inputGroupSelect" name="selectedRoomDevicesPdf" id="selectedRoomDevicesPdf" required>
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

<!-- Modal Export Excel Equipos -->
<section class="modal" id="deviceExportExcel">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Registros Excel</h1>
        </div>
        <form id="deviceExportFormExcel" action="" class="form">
            <div class="inputGroup">
                <label class="inputGroupLabel" for="selectedRoomDevicesExcel"><i class="bi bi-door-open"></i></label>
                <select class="inputGroupSelect" name="selectedRoomDevicesExcel" id="selectedRoomDevicesExcel" required>
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

<!-- Modal Export PDF Registro Equipos -->
<section class="modal" id="regDeviceExportPdf">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Registros PDF</h1>
        </div>
        <form id="regDeviceExportFormPdf" action="" class="form">
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

<!-- Modal Export Excel Registro Equipos -->
<section class="modal" id="regDeviceExportExcel">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
            <h1>Exportar Registros Excel</h1>
        </div>
        <form id="regDeviceExportFormExcel" action="" class="form">
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