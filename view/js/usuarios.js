const docInputFilter = document.getElementById("docInputFilter");
const roleSelectFilter = document.getElementById("roleSelectFilter");

loadSelectFilters(cargosAPI, "roleSelectFilter", ["detalle"]);

let users = [];
const loadRenderUsers = async () => {
  const data = await getData(usuariosAPI);
  users = data;
  renderUsers(users);
};

window.addEventListener("DOMContentLoaded", loadRenderUsers);

const updateRenderUsers = async () => {
  await loadRenderUsers();
};

const createUserCard = (users) => {
  const fragment = document.createDocumentFragment();
  users.forEach((user) => {
    const cardUser = document.createElement("div");
    cardUser.className = "card";
    const cardUserTop = document.createElement("div");
    cardUserTop.className = "cardTop";
    if (userRolView == 1) {
      const cardRoomMenu = document.createElement("a");
      cardRoomMenu.classList.add("cardRoomMenu");
      cardRoomMenu.innerHTML = '<i class="fa-solid fa-ellipsis"></i>';
      const cardRoomMenuItems = document.createElement("div");
      cardRoomMenuItems.classList.add("cardRoomMenuItems");
      const btnViewQr = document.createElement("a");
      btnViewQr.innerHTML = '<i class="fa-solid fa-qrcode"></i></i>Ver QR';
      const btnEdit = document.createElement("a");
      btnEdit.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Editar';
      const btnDelete = document.createElement("a");
      btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
      cardRoomMenuItems.appendChild(btnViewQr);
      cardRoomMenuItems.appendChild(btnEdit);
      cardRoomMenuItems.appendChild(btnDelete);
      cardUserTop.appendChild(cardRoomMenuItems);
      cardUserTop.appendChild(cardRoomMenu);
      dropDown(cardRoomMenu, cardRoomMenuItems);
      btnViewQr.addEventListener("click", () => {
        openModal("usersViewQr");
        const viewQrTitle = document.querySelector(".viewQrTitle");
        const usersViewQrImg = document.getElementById("usersViewQrImg");
        viewQrTitle.textContent = `${user.documento}`;
        usersViewQrImg.src = user.imagenQr;
      });
      btnEdit.addEventListener("click", () => {
        loadDataForm({
          inputs: ["userIdEdit", "nameUserEdit", "docUserEdit", "mailUserEdit", "rolUserEdit", "centerUserEdit"],
          inputsValue: [user.idUsuario, user.nombre, user.documento, user.correo, user.idCargo, user.idCentro],
          modal: "userEdit",
        });
      });
      btnDelete.addEventListener("click", () => {
        loadDataForm({
          inputs: ["userIdDelete"],
          inputsValue: [user.idUsuario],
          modal: "userDelete",
        });
      });
    }
    const cardUserBody = document.createElement("div");
    cardUserBody.classList.add("cardBody");
    const cardUserPic = document.createElement("div");
    cardUserPic.classList.add("cardPic");
    const img = document.createElement("img");
    img.src = user.imagen;
    img.alt = "profile";
    cardUserPic.appendChild(img);
    const cardUserTxt = document.createElement("div");
    cardUserTxt.classList.add("cardBodyTxt");
    const cardUserTxtRol = document.createElement("p");
    const cardUserTxtName = document.createElement("h3");
    const cardUserTxtCargo = document.createElement("span");
    cardUserTxtName.className = "cardUserH3";
    cardUserTxtRol.textContent = user.cargo;
    cardUserTxtName.textContent = user.nombre;
    cardUserTxtCargo.textContent = `C.C. ${user.documento}`;
    cardUserTxt.appendChild(cardUserTxtRol);
    cardUserTxt.appendChild(cardUserTxtName);
    cardUserTxt.appendChild(cardUserTxtCargo);
    cardUserBody.appendChild(cardUserPic);
    cardUserBody.appendChild(cardUserTxt);
    cardUser.appendChild(cardUserTop);
    cardUser.appendChild(cardUserBody);
    fragment.appendChild(cardUser);
  });
  return fragment;
};

const row = document.querySelector(".row");
const renderUsers = async (data) => {
  if (data.length > 0) {
    const cards = createUserCard(data);
    row.innerHTML = "";
    row.appendChild(cards);
  } else {
    row.innerHTML = "No hay resultados para mostrar.";
  }
};

const filterUsers = () => {
  const role = roleSelectFilter.value;
  const doc = docInputFilter.value;
  let newUsers = users;
  if (role !== "all") {
    newUsers = newUsers.filter((user) => user.cargo == role);
  }
  if (doc !== "") {
    newUsers = newUsers.filter((user) =>
      `${user.documento}`.includes(`${doc}`)
    );
  }
  renderUsers(newUsers);
};
roleSelectFilter.addEventListener("change", filterUsers);
docInputFilter.addEventListener("keyup", filterUsers);

loadSelectFilters(centrosAPI, "centerUser", ["idCentro", "detalle"]);
loadSelectFilters(centrosAPI, "centerUserEdit", ["idCentro", "detalle"]);
loadSelectFilters(cargosAPI, "rolUser", ["idCargo", "detalle"]);
loadSelectFilters(cargosAPI, "rolUserEdit", ["idCargo", "detalle"]);

sendForm(
  "userCreateForm",
  usuariosAPI,
  "POST",
  "messageCreate",
  updateRenderUsers,
  "userCreate",
  1500
);

sendForm(
  "userEditForm",
  usuariosAPI,
  "PUT",
  "messageEdit",
  updateRenderUsers,
  "userEdit",
  1500
);

sendForm(
  "userDeleteForm",
  usuariosAPI,
  "DELETE",
  "messageDelete",
  updateRenderUsers,
  "userDelete",
  1500
);

const userImportForm = document.getElementById("userImportForm");
userImportForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(userImportForm);
  const res = await fetch(importUsuariosAPI, {
    method: "POST",
    body: formData,
  });
  const data = await res.json();
  if (data.success == true) {
    updateRenderUsers();
    userImportForm.reset();
    showMessage("messageImport", "messageOK", data.message, "userImport", 1500);
  } else {
    showMessage("messageImport", "messageErr", data.message, "", 1500);
  }
});

// Asignar eventos a los botones
document.getElementById('btnExportPdf').addEventListener('click', () => {
  exportToPdf(usuariosAPI);
});

document.getElementById('btnExportExcel').addEventListener('click', () => {
  exportToExcel(usuariosAPI);
});