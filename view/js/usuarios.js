const docInputFilter = document.getElementById("docInputFilter");
const centerSelectFilter = document.getElementById("centerSelectFilter");
const statusSelectFilter = document.getElementById("roleSelectFilter");

loadSelectFilters(centrosAPI, "centerSelectFilter", ["siglas"]);
loadSelectFilters(ambientesAPI, "statusSelectFilter", ["estado"]);

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
    console.log(user);
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
          inputs: ["userIdEdit", "docUserEdit", "rolUserEdit", "centerUserEdit"],
          inputsValue: [user.idUsuario, user.documento, user.cargo, user.idCentro],
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

loadSelectFilters(centrosAPI, "centerUser", ["idCentro", "detalle"]);
loadSelectFilters(centrosAPI, "centerUserEdit", ["idCentro", "detalle"]);
// loadSelectFilters(cargosAPI, "rolUser", ["idCargo", "detalle"]);
// loadSelectFilters(cargosAPI, "rolUserEdit", ["idCargo", "detalle"]);
