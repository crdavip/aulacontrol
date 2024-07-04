// const numberInputFilter = document.getElementById("numberInputFilter");
// const centerSelectFilter = document.getElementById("centerSelectFilter");
// const statusSelectFilter = document.getElementById("statusSelectFilter");

let objectList;
let roomList;
let centersList;
// const selectListRooms = document.getElementById("idRoom");
// const selectCenterDevice = document.getElementById("centerDevice");
// const getDataAmbs = async () => {
//   const centersList = await getData(centrosAPI);
//   const roomsList = await getData(ambientesAPI);

//   let contentSelectTagCenters = centersList.map((center) => {
//     return `<option value="${center.idCentro}">${center.detalle}</option>`;
//   }).join("");
//   selectCenterDevice.innerHTML = `<option value="">Seleccione un Centro</option>` + contentSelectTagCenters;
//   updateRoomsDropdown();
// }

// const updateRoomsDropdown = () => {
//   const selectedCenter = selectCenterDevice.value;
//   const filteredRooms = roomsList.filter(room => room.idCentro == selectedCenter);
//   let contentSelectTagRooms = filteredRooms.map((room) => {
//     return `<option value="${room.idAmbiente}">${room.numero}</option>`;
//   }).join("");
//   selectListRooms.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
// }

// loadSelectFilters(centrosAPI, "centerSelectFilter", ["siglas"]);
// loadSelectFilters(ambientesAPI, "statusSelectFilter", ["estado"]);

let objects = [];
const loadRenderObjects = async () => {
  const data = await getData(objetosAPI);
  objects = data;
  renderDevices(objects);
  // getDataAmbs();
}

window.addEventListener("DOMContentLoaded", loadRenderObjects);
// selectCenterDevice.addEventListener("change", updateRoomsDropdown);

// const updateRenderObjects = async () => {
//   await loadRenderObjects();
// };

const createObjectCard = (objects) => {
  const fragment = document.createDocumentFragment();
  objects.forEach((object) => {
    const cardObject = document.createElement("div");
    cardObject.classList.add("card");
    const cardBody = document.createElement("div");
    cardBody.classList.add("cardBody", "cardBodyRoom");
    if (userRolView == 1) {
      const cardObjectMenu = document.createElement("a");
      cardObjectMenu.classList.add("cardRoomMenu");
      cardObjectMenu.innerHTML = '<i class="fa-solid fa-ellipsis"></i>';
      const cardObjectMenuItems = document.createElement("div");
      cardObjectMenuItems.classList.add("cardRoomMenuItems");
      const btnEdit = document.createElement("a");
      btnEdit.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Editar';
      const btnDelete = document.createElement("a");
      btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
      cardObjectMenuItems.appendChild(btnEdit);
      cardObjectMenuItems.appendChild(btnDelete);
      cardBody.appendChild(cardObjectMenuItems);
      cardBody.appendChild(cardObjectMenu);
      dropDown(cardObjectMenu, cardObjectMenuItems);
      // btnAssoc.addEventListener("click", () => {
      //   deviceAssoc(device);
      // });

      btnEdit.addEventListener("click", () => {
        // const selectEditCenter = document.getElementById("centerObjectEdit");
        // const selectEditRoom = document.getElementById("deviceAmbEdit");

        let roomFiltered = roomsList.find((room) => room.numero == device.ambiente);
        let centerFiltered = centersList.find((center) => center.idCentro === roomFiltered.idCentro);

        let contentSelectTagCenterFiltered = centersList.filter((center) => center.idCentro !== centerFiltered.idCentro);
        let contentSelectTagCenter = contentSelectTagCenterFiltered.map((center) => {
          return `<option value="${center.idCentro}">${center.detalle}</option>`;
        }).join("");

        selectEditCenter.innerHTML = `<option value="${centerFiltered.idCentro}">${centerFiltered.detalle}</option>` + contentSelectTagCenter;

        const selectedCenter = selectEditCenter.value;
        const filteredRooms = roomsList.filter(room => room.idCentro == selectedCenter && room.numero !== device.ambiente);
        // const filteredRooms = roomsList.filter(room => room.idCentro == selectedCenter);
        let contentSelectTagRooms = filteredRooms.map((room) => {
          return `<option value="${room.idAmbiente}">${room.numero}</option>`;
        }).join("");
        console.log(roomFiltered);
        console.log(contentSelectTagRooms);
        selectEditRoom.innerHTML = `<option value="${roomFiltered.idAmbiente}">${roomFiltered.numero}</option>${contentSelectTagRooms}`;

        loadDataForm({
          inputs: ["deviceIdEdit", "deviceRefEdit", "deviceBrandEdit", "deviceStateEdit"],
          inputsValue: [device.idComputador, device.ref, device.marca, device.estado],
          modal: "editDevice",
        });
      });

      const updateRoomsDropdownEdit = () => {
        const selectEditCenter = document.getElementById("centerDeviceEdit");
        const selectEditRoom = document.getElementById("deviceAmbEdit");

        const selectedCenter = selectEditCenter.value;
        const filteredRooms = roomsList.filter(room => room.idCentro == selectedCenter);

        let contentSelectTagRooms = filteredRooms.map((room) => {
          return `<option value="${room.idAmbiente}">${room.numero}</option>`;
        }).join("");

        selectEditRoom.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
      }

      document.getElementById("centerDeviceEdit").addEventListener("change", updateRoomsDropdownEdit);

      btnDelete.addEventListener("click", () => {
        loadDataForm({
          inputs: ["deviceIdDelete"],
          inputsValue: [device.idComputador],
          modal: "deleteDevice",
        });
      });
    }
    const cardDeviceNum = document.createElement("div");
    cardDeviceNum.classList.add("cardDeviceNum");
    cardDeviceNum.innerHTML = `<h2>${device.ref}</h2>`;
    const cardBodyTxt = document.createElement("div");
    cardBodyTxt.classList.add("cardBodyTxt");
    cardBodyTxt.innerHTML = `<p>${device.estado}</p>
                            <p>${device.ambiente}</p>
                            <h3>${device.marca}</h3>`;
    if (device.estado == "Ocupada") {
      cardDeviceNum.classList.add("cardDeviceNumAlt");
      cardBodyTxt.classList.add("cardBodyTxtAlt");
    }
    cardBody.appendChild(cardDeviceNum);
    cardBody.appendChild(cardBodyTxt);
    cardDevice.appendChild(cardBody);
    fragment.appendChild(cardDevice);
  });
  return fragment;
};



const row = document.querySelector(".row");
const renderDevices = async (data) => {
  if (data.length > 0) {
    const cards = createDeviceCard(data);
    row.innerHTML = "";
    row.appendChild(cards);
  } else {
    row.innerHTML = "No hay resultados para mostrar.";
  }
};

sendForm(
  "createDeviceForm",
  equiposAPI,
  "POST",
  "messageCreate",
  updateRenderDevices,
  "createDevice",
  1500
);

sendForm(
  "deviceEditForm",
  equiposAPI,
  "PUT",
  "messageEdit",
  updateRenderDevices,
  "editDevice",
  1500
);

sendForm(
  "deviceDeleteForm",
  equiposAPI,
  "DELETE",
  "messageDelete",
  updateRenderDevices,
  "deleteDevice",
  1500
);