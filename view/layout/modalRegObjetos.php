<!-- Modal Create -->
<section class="modal" id="createRegObject">
  <div class="containerModal">
    <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
    <div class="titlePg">
      <i class="fa-solid fa-kaaba"></i>
      <h1>Nuevo Objeto</h1>
    </div>
    <form id="createRegObjectForm" action="" class="form">
      <div class="inputGroup">
        <input class="inputGroupInput" type="text" name="descriptionObject" id="descriptionObject"
          autocomplete="off" required />
        <label class="inputGroupLabel" for="descriptionObject"><i class="fa-regular fa-file-lines"></i>
          Descripción</label>
      </div>
      <div class="inputGroup">
        <input class="inputGroupInput" type="text" name="colorObject" id="colorObject" autocomplete="off" required>
        <label class="inputGroupLabel" for="colorObject"><i class="fa-solid fa-droplet"></i> Color</label>
      </div>
      <div class="inputGroup">
        <input class="inputGroupInput" type="text" name="userObject" id="userObject" autocomplete="off" required>
        <label class="inputGroupLabel" for="userObject"><i class="fa-solid fa-user"></i> Documento Usuario</label>
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
<section class="modal" id="editObject">
  <div class="containerModal">
    <button class="closeModal"><i class="fa-solid fa-xmark"></i></button>
    <div class="titlePg">
      <i class="fa-solid fa-kaaba"></i>
      <h1>Editar Objeto</h1>
    </div>
    <form id="objectEditForm" action="" class="form">
      <input type="hidden" name="objectIdEdit" id="objectIdEdit">
      <div class="inputGroup">
        <input class="inputGroupInput" type="text" name="objectDescriptionEdit" id="objectDescriptionEdit"
          autocomplete="off" required>
        <label class="inputGroupLabel" for="objectDescriptionEdit"><i class="fa-solid fa-list-ol"></i>
          Descripción</label>
      </div>
      <div class="inputGroup">
        <input class="inputGroupInput" type="text" name="objectColorEdit" id="objectColorEdit" autocomplete="off"
          required>
        <label class="inputGroupLabel" for="objectColorEdit"><i class="fa-solid fa-list-ol"></i> Color</label>
      </div>
      <div class="inputGroup">
        <input class="inputGroupInput" type="text" name="userObjectEdit" id="userObjectEdit" autocomplete="off"
          required>
        <label class="inputGroupLabel" for="userObjectEdit"><i class="fa-solid fa-user"></i> Documento Usuario</label>
      </div>
      <div class="buttonGroup">
        <button class="btn" type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
        <button class="btn btnAlt" type="reset"><i class="fa-solid fa-eraser"></i> Limpiar</button>
      </div>
    </form>
    <p id="messageEdit" class="message"></p>
  </div>
</section>