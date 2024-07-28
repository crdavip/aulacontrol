const numberInputFilter = document.getElementById("numberInputFilter");
const centerSelectFilter = document.getElementById("centerSelectFilter");
const statusSelectFilter = document.getElementById("statusSelectFilter");

loadSelectFilters(centrosAPI, "centerSelectFilter", ["siglas"]);
loadSelectFilters(equiposAPI, "statusSelectFilter", ["estado"]);

const roomPdf = document.getElementById("selectedRoomDevicesPdf");
const roomExcel = document.getElementById("selectedRoomDevicesExcel");

let renderRoomsInExport;
let roomsList;
let centersList;
const selectListRooms = document.getElementById("idRoom");
const selectCenterDevice = document.getElementById("centerDevice");

const getAmbsForExport = async () =>{ 
  const dataAmbientesExport = await getData(ambientesAPI);
  let roomsListExport = await dataAmbientesExport;

  let contentSelectTagRoomsExport = roomsListExport.map((room) => {
    return `<option value="${room.idAmbiente}">${room.numero}</option>`;
  }).join("");

  roomPdf.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRoomsExport;
  roomExcel.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRoomsExport;
}

const getDataAmbs = async () => {
  const dataCentros = await getData(centrosAPI);
  const dataAmbientes = await getData(ambientesAPI);
  centersList = dataCentros;
  roomsList = dataAmbientes;

  let contentSelectTagCenters = centersList.map((center) => {
    return `<option value="${center.idCentro}">${center.detalle}</option>`;
  }).join("");
  selectCenterDevice.innerHTML = `<option value="">Seleccione un Centro</option>` + contentSelectTagCenters;
  updateRoomsDropdown();
}

const updateRoomsDropdown = () => {
  const selectedCenter = selectCenterDevice.value;
  const filteredRooms = roomsList.filter(room => room.idCentro == selectedCenter);
  let contentSelectTagRooms = filteredRooms.map((room) => {
    return `<option value="${room.idAmbiente}">${room.numero}</option>`;
  }).join("");
  renderRoomsInExport = contentSelectTagRooms;
  selectListRooms.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
}

let devices = [];
const loadRenderDevices = async () => {
  const data = await getData(equiposAPI);
  devices = data;
  renderDevices(devices);
  getDataAmbs();
  getAmbsForExport();
}

window.addEventListener("DOMContentLoaded", loadRenderDevices);
selectCenterDevice.addEventListener("change", updateRoomsDropdown);

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
      const btnAssoc = document.createElement("a");
      device.estado == "Disponible"
        ? (btnAssoc.innerHTML = '<i class="fa-solid fa-qrcode"></i>Vincular')
        : (btnAssoc.innerHTML =
          '<i class="fa-solid fa-qrcode"></i>Desvincular');
      const btnEdit = document.createElement("a");
      btnEdit.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Editar';
      const btnDelete = document.createElement("a");
      btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
      cardDeviceMenuItems.appendChild(btnAssoc);
      cardDeviceMenuItems.appendChild(btnEdit);
      cardDeviceMenuItems.appendChild(btnDelete);
      cardBody.appendChild(cardDeviceMenuItems);
      cardBody.appendChild(cardDeviceMenu);
      dropDown(cardDeviceMenu, cardDeviceMenuItems);
      btnAssoc.addEventListener("click", () => {
        deviceAssoc(device);
      });

      btnEdit.addEventListener("click", () => {
        const selectEditCenter = document.getElementById("centerDeviceEdit");
        const selectEditRoom = document.getElementById("deviceAmbEdit");

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
          inputs: ["deviceIdEdit", "deviceRefEdit", "deviceBrandEdit"],
          inputsValue: [device.idComputador, device.ref, device.marca],
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
    if (device.estado == "Ocupado") {
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

const filterDevices = () => {
  const center = centerSelectFilter.value;
  const status = statusSelectFilter.value;
  const number = numberInputFilter.value;
  let newdevices = devices;
  console.log(newdevices);
  if (center !== "all") {
    newdevices = newdevices.filter((device) => device.centro == center);
  }
  if (status !== "all") {
    newdevices = newdevices.filter((device) => device.estado == status);
  }
  if (number !== "") {
    newdevices = newdevices.filter((device) =>
      `${device.ref.toLowerCase()}`.includes(`${number.toLowerCase()}`)
    );
  }
  renderDevices(newdevices);
};
centerSelectFilter.addEventListener("change", filterDevices);
statusSelectFilter.addEventListener("change", filterDevices);
numberInputFilter.addEventListener("keyup", filterDevices);

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

ExportFormExcel(
  "deviceExportFormExcel",
  equiposAPI,
);

ExportFormPdf(
  "deviceExportFormPdf",
  equiposAPI,
);

const deviceAssoc = (device) => {
  openModal("deviceAssoc");
  const titleDeviceAssoc = document.getElementById("titleDeviceAssoc");
  device.estado == "Disponible"
    ? (titleDeviceAssoc.innerHTML = `Vincular Equipo`)
    : (titleDeviceAssoc.innerHTML = `Desvincular Equipo`);
  deviceAssocInfo(device);
  renderScanQR();
  configScanQR(filterDoc);
};

const deviceAssocInfo = async (device) => {
  const deviceAssocInfo = document.querySelector(".deviceAssocInfo");
  deviceAssocInfo.innerHTML = "";
  deviceAssocInfo.innerHTML += `<input type="hidden" name="deviceIdAssoc" id="deviceIdAssoc" value="${device.idComputador}">`;
  deviceAssocInfo.innerHTML += `<input type="hidden" name="deviceStatusAssoc" id="deviceStatusAssoc" value="${device.estado}">`;
  const deviceAssocTitle = document.createElement("h3");
  deviceAssocTitle.innerHTML = `Equipo ${device.ref} - ${device.idAmbiente}`;
  deviceAssocInfo.appendChild(deviceAssocTitle);
  if (device.estado == "Disponible") {
    const inputGroupQr = document.createElement("div");
    inputGroupQr.classList.add("inputGroup", "inputGroupQr");
    inputGroupQr.innerHTML = `
    <label for="deviceCharge">Cargador</label>
    <input class="inputCheck" type="checkbox" name="deviceCharge" id="deviceCharge" value="charge" checked>
    <label for="mouseDevice">Mouse</label>
    <input class="inputCheck" type="checkbox" name="mouseDevice" id="mouseDevice" value="mouse">
    <label for="allInOneDevice">Todo en Uno</label>
    <input class="inputCheck" type="checkbox" name="allInOneDevice" id="allInOneDevice" value="allInOne">
    `;
    deviceAssocInfo.appendChild(inputGroupQr);
  } else {
    const res = await fetch(`${regAmbientesAPI}?idAmbiente=${room.idAmbiente}`);
    const data = await res.json();
    const roomAssocUser = document.createElement("p");
    roomAssocUser.innerHTML = `Vinculada con <strong>${data.instructor}</strong>`;
    roomAssocInfo.appendChild(roomAssocUser);
    const roomAssocStart = document.createElement("p");
    roomAssocStart.classList.add("roomAssocStart");
    roomAssocStart.innerHTML = `<strong>Inicio:</strong> ${data.inicio}`;
    roomAssocInfo.appendChild(roomAssocStart);
  }
};