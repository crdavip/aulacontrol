const numberInputFilter = document.getElementById("numberInputFilter");
const centerSelectFilter = document.getElementById("centerSelectFilter");
const statusSelectFilter = document.getElementById("statusSelectFilter");

loadSelectFilters(centrosAPI, "centerSelectFilter", ["siglas"]);
loadSelectFilters(ambientesAPI, "statusSelectFilter", ["estado"]);

let rooms = [];
const loadRenderRooms = async () => {
  const data = await getData(ambientesAPI);
  rooms = data;
  renderRooms(rooms);
};

window.addEventListener("DOMContentLoaded", loadRenderRooms);

const updateRenderRooms = async () => {
  await loadRenderRooms();
};

const createRoomCard = (rooms) => {
  const fragment = document.createDocumentFragment();
  rooms.forEach((room) => {
    const cardRoom = document.createElement("div");
    cardRoom.classList.add("card");
    const cardBody = document.createElement("div");
    cardBody.classList.add("cardBody", "cardBodyRoom");
    if (userRolView == 1) {
      const cardRoomMenu = document.createElement("a");
      cardRoomMenu.classList.add("cardRoomMenu");
      cardRoomMenu.innerHTML = '<i class="fa-solid fa-ellipsis"></i>';
      const cardRoomMenuItems = document.createElement("div");
      cardRoomMenuItems.classList.add("cardRoomMenuItems");
      const btnAssoc = document.createElement("a");
      // room.estado == "Disponible"
      //   ? (btnAssoc.innerHTML = '<i class="fa-solid fa-qrcode"></i>Ver equipos')
      //   : (btnAssoc.innerHTML =
      //       '<i class="fa-solid fa-qrcode"></i>Desvincular');
      const btnDevices = document.createElement("a");
      btnDevices.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Ver equipos';
      const btnNewDevice = document.createElement("a");
      btnNewDevice.innerHTML = '<i class="fa-solid fa-square-plus"></i>Agregar';
      const btnRegistrationDevices = document.createElement("a");
      btnRegistrationDevices.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Registro equipos';
      // const btnDelete = document.createElement("a");
      // btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
      // cardRoomMenuItems.appendChild(btnAssoc);
      cardRoomMenuItems.appendChild(btnDevices);
      cardRoomMenuItems.appendChild(btnNewDevice);
      cardRoomMenuItems.appendChild(btnRegistrationDevices);
      cardBody.appendChild(cardRoomMenuItems);
      cardBody.appendChild(cardRoomMenu);
      dropDown(cardRoomMenu, cardRoomMenuItems);
      btnAssoc.addEventListener("click", () => {
        roomAssoc(room);
      });
      
      btnNewDevice.addEventListener("click", () => {
        // ! INSERT OPERATION
      })
      btnDevices.addEventListener("click", () => {
        // ! COnsulta a los equipo de la base de datos asociados con el ambiente seleccionado (que se pueda eliminar un equipo)
      })

      btnRegistrationDevices.addEventListener("click", () => {
        window.location.href = "./registro-equipos-amb.php"
      });
      // btnDelete.addEventListener("click", () => {
      //   loadDataForm({
      //     inputs: ["roomIdDelete"],
      //     inputsValue: [room.idAmbiente],
      //     modal: "roomDelete",
      //   });
      // });
    }
    const cardRoomNum = document.createElement("div");
    cardRoomNum.classList.add("cardRoomNum");
    cardRoomNum.innerHTML = `<h2>${room.numero}</h2>`;
    const cardBodyTxt = document.createElement("div");
    cardBodyTxt.classList.add("cardBodyTxt");
    cardBodyTxt.innerHTML = `<p>${room.estado}</p>
                            <h3>${room.centro}</h3>
                            <span>${room.afluencia} ${room.afluencia == 1 ? "Equipo" : "Equipos"
      }</span>`;
    if (room.estado == "Ocupada") {
      cardRoomNum.classList.add("cardRoomNumAlt");
      cardBodyTxt.classList.add("cardBodyTxtAlt");
    }
    cardBody.appendChild(cardRoomNum);
    cardBody.appendChild(cardBodyTxt);
    cardRoom.appendChild(cardBody);
    fragment.appendChild(cardRoom);
  });
  return fragment;
};

const row = document.querySelector(".rowEnvDevices");
const renderRooms = async (data) => {
  if (data.length > 0) {
    const cards = createRoomCard(data);
    row.innerHTML = "";
    row.appendChild(cards);
  } else {
    row.innerHTML = "No hay resultados para mostrar.";
  }
};

const filterRooms = () => {
  const center = centerSelectFilter.value;
  const status = statusSelectFilter.value;
  const number = numberInputFilter.value;
  let newRooms = rooms;
  if (center !== "all") {
    newRooms = newRooms.filter((room) => room.centro == center);
  }
  if (status !== "all") {
    newRooms = newRooms.filter((room) => room.estado == status);
  }
  if (number !== "") {
    newRooms = newRooms.filter((room) =>
      `${room.numero.toLowerCase()}`.includes(`${number.toLowerCase()}`)
    );
  }
  renderRooms(newRooms);
};
centerSelectFilter.addEventListener("change", filterRooms);
statusSelectFilter.addEventListener("change", filterRooms);
numberInputFilter.addEventListener("keyup", filterRooms);

// loadSelectFilters(centrosAPI, "centerRoom", ["idCentro", "detalle"]);
// loadSelectFilters(centrosAPI, "centerRoomEdit", ["idCentro", "detalle"]);

// sendForm(
//   "roomCreateForm",
//   ambientesAPI,
//   "POST",
//   "messageCreate",
//   updateRenderRooms,
//   "roomCreate",
//   1500
// );

// sendForm(
//   "roomEditForm",
//   ambientesAPI,
//   "PUT",
//   "messageEdit",
//   updateRenderRooms,
//   "roomEdit",
//   1500
// );

// sendForm(
//   "roomDeleteForm",
//   ambientesAPI,
//   "DELETE",
//   "messageDelete",
//   updateRenderRooms,
//   "roomDelete",
//   1500
// );

// const roomAssocInfo = async (room) => {
//   const roomAssocInfo = document.querySelector(".roomAssocInfo");
//   roomAssocInfo.innerHTML = "";
//   roomAssocInfo.innerHTML += `<input type="hidden" name="roomIdAssoc" id="roomIdAssoc" value="${room.idAmbiente}">`;
//   roomAssocInfo.innerHTML += `<input type="hidden" name="roomStatusAssoc" id="roomStatusAssoc" value="${room.estado}">`;
//   const roomAssocTitle = document.createElement("h3");
//   roomAssocTitle.innerHTML = `Ambiente ${room.numero} - ${room.centro}`;
//   roomAssocInfo.appendChild(roomAssocTitle);
//   if (room.estado == "Disponible") {
//     const inputGroupQr = document.createElement("div");
//     inputGroupQr.classList.add("inputGroup", "inputGroupQr");
//     inputGroupQr.innerHTML = `
//     <label for="rommKey">Llaves</label>
//     <input class="inputCheck" type="checkbox" name="roomKey" id="rommKey" value="key" checked>
//     <label for="controlTv">Control Tv</label>
//     <input class="inputCheck" type="checkbox" name="controlTv" id="controlTv" value="tv" checked>
//     <label for="controlAir">Control Aire</label>
//     <input class="inputCheck" type="checkbox" name="controlAir" id="controlAir" value="air" checked>
//     `;
//     roomAssocInfo.appendChild(inputGroupQr);
//   } else {
//     const res = await fetch(`${regAmbientesAPI}?idAmbiente=${room.idAmbiente}`);
//     const data = await res.json();
//     const roomAssocUser = document.createElement("p");
//     roomAssocUser.innerHTML = `Vinculada con <strong>${data.instructor}</strong>`;
//     roomAssocInfo.appendChild(roomAssocUser);
//     const roomAssocStart = document.createElement("p");
//     roomAssocStart.classList.add("roomAssocStart");
//     roomAssocStart.innerHTML = `<strong>Inicio:</strong> ${data.inicio}`;
//     roomAssocInfo.appendChild(roomAssocStart);
//   }
// };

// const roomAssoc = (room) => {
//   openModal("roomAssoc");
//   const titleRoomAssoc = document.getElementById("titleRoomAssoc");
//   room.estado == "Disponible"
//     ? (titleRoomAssoc.innerHTML = `Vincular Ambiente`)
//     : (titleRoomAssoc.innerHTML = `Desvincular Ambiente`);
//   roomAssocInfo(room);
//   renderScanQR();
//   configScanQR(filterDoc);
// };

const roomAssocHistory = async (method, json, userData) => {
  const jsonData = JSON.stringify(json);
  const res = await fetch(regAmbientesAPI, {
    method: method,
    headers: {
      "Content-Type": "application/json",
    },
    body: jsonData,
  });
  const data = await res.json();
  if (data.success == true) {
    updateRenderRooms();
    instructorSenaCard();
    loadSenaCard(
      "picSenaCard",
      "roleSenaCard",
      "nameUserSenaCard",
      "docUserSenaCard",
      "fichaSenaCard",
      userData
    );
    showMessage(
      "messageRoomAssoc",
      "messageOK",
      data.message,
      "roomAssoc",
      2500
    );
  } else {
    showMessage("messageRoomAssoc", "messageErr", data.message, "", 2500);
  }
};

const filterDoc = async (doc) => {
  const roomIdAssoc = document.getElementById("roomIdAssoc").value;
  const roomStatusAssoc = document.getElementById("roomStatusAssoc").value;
  const rommKey = document.getElementById("rommKey");
  const controlTv = document.getElementById("controlTv");
  const controlAir = document.getElementById("controlAir");
  const res = await fetch(`${usuariosAPI}?docUserAssoc=${doc}`);
  const data = await res.json();

  if (data.success == true) {
    const userData = data.user;
    if (roomStatusAssoc == "Disponible") {
      const dataAssoc = {
        idUserAssoc: data.user.idUsuario,
        idRoom: roomIdAssoc,
        keys: rommKey.checked,
        controlTv: controlTv.checked,
        controlAir: controlAir.checked,
      };
      roomAssocHistory("POST", dataAssoc, userData);
    } else {
      const dataAssoc = {
        idUserAssoc: data.user.idUsuario,
        idRoom: roomIdAssoc,
      };
      roomAssocHistory("PUT", dataAssoc, userData);
    }
  } else {
    showMessage("messageRoomAssoc", "messageErr", data.message, "", 2000);
  }
};