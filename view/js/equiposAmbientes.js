const numberInputFilter = document.getElementById("numberInputFilter");
const centerSelectFilter = document.getElementById("centerSelectFilter");
const statusSelectFilter = document.getElementById("statusSelectFilter");

let roomsList;
const selectListRooms = document.getElementById("idRoom");
const getDataAmbs = async () => {
  const dataAmbientes = await getData(ambientesAPI);
  roomsList = dataAmbientes;
  let contentSelectTag = roomsList.map((room) => {
    return `<option value="${room.idAmbiente}">${room.numero}</option>`;
  }).join("");

  selectListRooms.innerHTML += contentSelectTag;
}

const filterRoomForSelect = (rooms, idRoom) => {
  const roomFounded = rooms.find((room) => room.idAmbiente === idRoom);
  return roomFounded.idAmbiente;
}

loadSelectFilters(centrosAPI, "centerSelectFilter", ["siglas"]);
// loadSelectFilters(ambientesAPI, "statusSelectFilter", ["estado"]);

let devices = [];
const loadRenderDevices = async () => {
  const data = await getData(equiposAmbientesAPI);
  devices = data;
  console.log(devices)
  renderDevices(devices);
  getDataAmbs();
}

window.addEventListener("DOMContentLoaded", loadRenderDevices);

const updateRenderDevices = async () => {
  await loadRenderDevices();
};

const createDeviceCard = (devices) => {
  const fragment = document.createDocumentFragment();
  devices.forEach((device) => {
    const cardDevice = document.createElement("div");
    cardDevice.classList.add("card");
    const cardBody = document.createElement("div");
    cardBody.classList.add("cardBody", "cardBodyRoom");
    if (userRolView == 1) {
      const cardDeviceMenu = document.createElement("a");
      cardDeviceMenu.classList.add("cardRoomMenu");
      cardDeviceMenu.innerHTML = '<i class="fa-solid fa-ellipsis"></i>';
      const cardDeviceMenuItems = document.createElement("div");
      cardDeviceMenuItems.classList.add("cardRoomMenuItems");
      const btnEdit = document.createElement("a");
      btnEdit.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Editar';
      const btnDelete = document.createElement("a");
      btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
      // cardDeviceMenuItems.appendChild(btnAssoc);
      cardDeviceMenuItems.appendChild(btnEdit);
      cardDeviceMenuItems.appendChild(btnDelete);
      cardBody.appendChild(cardDeviceMenuItems);
      cardBody.appendChild(cardDeviceMenu);
      dropDown(cardDeviceMenu, cardDeviceMenuItems);
      // btnAssoc.addEventListener("click", () => {
      //   deviceAssoc(device);
      // });
      btnEdit.addEventListener("click", () => {
        // ? traer rooms
        const selectEditRoom = document.getElementById("deviceAmbEdit");
        let contentSelectTag = roomsList.map((room) => {
          return `<option value="${room.idAmbiente}">${room.numero}</option>`;
        }).join("");
        selectEditRoom.innerHTML += contentSelectTag;
        
        loadDataForm({
          inputs: ["deviceIdEdit", "deviceRefEdit", "deviceBranchEdit", "deviceStateEdit", "deviceAmbEdit"],
          inputsValue: [device.idComputador, device.ref, device.marca, device.estado, device.ambiente],
          modal: "editDevice",
        });
      });
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
  equiposAmbientesAPI,
  "POST",
  "messageCreate",
  updateRenderDevices,
  "createDevice",
  1500
);

sendForm(
  "deviceEditForm",
  equiposAmbientesAPI,
  "PUT",
  "messageEdit",
  updateRenderDevices,
  "editDevice",
  1500
);

sendForm(
  "deviceDeleteForm",
  equiposAmbientesAPI,
  "DELETE",
  "messageDelete",
  updateRenderDevices,
  "deleteDevice",
  1500
);