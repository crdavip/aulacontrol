<!-- Modal Create -->
<section class="modal" id="createDevice">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-kaaba"></i>
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
            <i class="fa-solid fa-kaaba"></i>
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
                <input class="inputGroupInput" type="text" name="deviceBranchEdit" id="deviceBranchEdit" autocomplete="off"
                    required>
                <label class="inputGroupLabel" for="deviceBranchEdit"><i class="fa-solid fa-list-ol"></i> Marca</label>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="deviceStateEdit"><i class="bi bi-laptop"></i></label>
                <select class="inputGroupSelect" name="deviceStateEdit" id="deviceStateEdit" required>
                    <option value="">Seleccione un estado</option>
                    <option value="Ocupado">Ocupado</option>
                    <option value="Disponible">Disponible</option>
                </select>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="centerDevice"><i class="fa-solid fa-school-flag"></i></label>
                <select class="inputGroupSelect" name="centerDeviceEdit" id="centerDeviceEdit" required>
                    <!-- <option value="">Seleccione un Centro</option> -->
                </select>
            </div>
            <div class="inputGroup">
                <label class="inputGroupLabel" for="deviceAmbEdit"><i class="bi bi-door-open"></i></label>
                <select class="inputGroupSelect" name="deviceAmbEdit" id="deviceAmbEdit" required>
                    <!-- <option value="">Seleccione un Ambiente</option> -->
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
            <i class="fa-solid fa-kaaba"></i>
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