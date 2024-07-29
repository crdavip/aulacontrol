<!-- Modal Create -->
<section class="modal" id="observationCreate">
    <div class="containerModal">
        <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
        <div class="titlePg">
            <i class="fa-solid fa-cube"></i>
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
                <textarea cols="4" rows="8" type="text" name="descriptionObservation" id="descriptionObservation"
                    autocomplete="off" required></textarea>
            <div class="buttonGroup">
                <button class="btn" type="submit"><i class="fa-solid fa-square-plus"></i> Enviar</button>
                <button class="btn btnAlt" type="reset"><i class="fa-solid fa-eraser"></i> Limpiar</button>
            </div>
        </form>
        <p id="messageCreate" class="message"></p>
    </div>
</section>