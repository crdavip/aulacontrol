const docInputFilter = document.getElementById("docInputFilter");
const centerSelectFilter = document.getElementById("centerSelectFilter");
const roleSelectFilter = document.getElementById("roleSelectFilter");

loadSelectFilters(centrosAPI, "centerSelectFilter", ["siglas"]);
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
      const btnEdit = document.createElement("a");
      btnEdit.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Editar';
      const btnDelete = document.createElement("a");
      btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
      cardRoomMenuItems.appendChild(btnEdit);
      cardRoomMenuItems.appendChild(btnDelete);
      cardUserTop.appendChild(cardRoomMenuItems);
      cardUserTop.appendChild(cardRoomMenu);
      dropDown(cardRoomMenu, cardRoomMenuItems);
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
    cardUserTxtCargo.textContent = user.documento;
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
  const center = centerSelectFilter.value;
  const role = roleSelectFilter.value;
  const doc = docInputFilter.value;
  let newUsers = users;
  console.log(newUsers);
  if (center !== "all") {
    newUsers = newUsers.filter((user) => user.siglas == center);
  }
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
centerSelectFilter.addEventListener("change", filterUsers);
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

  // Logica de Exportación
  // Función para exportar a PDF
const exportToPdf = async (urlAPI) => {
  const params = new URLSearchParams({ format: 'pdf' }).toString();
  const url = `${urlAPI}?${params}`;
  window.open(url, '_blank');
};

// Función para exportar a Excel
const exportToExcel = async (urlAPI) => {
  const params = new URLSearchParams({ format: 'excel' }).toString();
  const url = `${urlAPI}?${params}`;

  const response = await fetch(url);
  const blob = await response.blob();

  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(blob);
  link.download = 'reporte.xlsx';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

// Asignar eventos a los botones
document.getElementById('btnExportPdf').addEventListener('click', () => {
  exportToPdf(usuariosAPI);
});

document.getElementById('btnExportExcel').addEventListener('click', () => {
  exportToExcel(usuariosAPI);
});