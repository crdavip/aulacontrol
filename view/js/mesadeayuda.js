const numberInputFilter = document.getElementById("numberInputFilter");
const statusSelectFilter = document.getElementById("statusSelectFilter");

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
  centersList = dataCentros.filter((item) => item.siglas !== "PORT");
  roomsList = dataAmbientes.filter((item) => item.numero == "Mesa Ayuda");

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
  const data = await getData(`${equiposAPI}?helpDevices`);
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

const createDeviceCard  = (devices) => {
  const fragment = document.createDocumentFragment();
  devices.forEach((device) => {
    const cardDevice = document.createElement("div");
    cardDevice.classList.add("card");
    const cardTop = document.createElement("div");
    cardTop.classList.add("cardTop");
    if (userRolView == 1) {
      const cardMenu = document.createElement("a");
      cardMenu.classList.add("cardMenu");
      cardMenu.innerHTML = '<i class="fa-solid fa-ellipsis"></i>';
      const cardMenuItems = document.createElement("div");
      cardMenuItems.classList.add("cardMenuItems");
      const btnAssoc = document.createElement("a");
      device.estado == "Disponible"
        ? (btnAssoc.innerHTML = '<i class="fa-solid fa-qrcode"></i>Vincular')
        : (btnAssoc.innerHTML =
          '<i class="fa-solid fa-qrcode"></i>Desvincular');
      const btnViewQr = document.createElement("a");
      btnViewQr.innerHTML = '<i class="fa-solid fa-qrcode"></i></i>Ver QR';
      const btnEdit = document.createElement("a");
      btnEdit.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Editar';
      const btnDelete = document.createElement("a");
      btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
      cardMenuItems.appendChild(btnAssoc);
      cardMenuItems.appendChild(btnViewQr);
      cardMenuItems.appendChild(btnEdit);
      cardMenuItems.appendChild(btnDelete);
      cardTop.appendChild(cardMenuItems);
      cardTop.appendChild(cardMenu);
      dropDown(cardMenu, cardMenuItems);
      btnAssoc.addEventListener("click", () => {
        deviceAssoc(device);
      });
      btnViewQr.addEventListener("click", () => {
        openModal("deviceViewQr");
        const viewQrTitle = document.querySelector(".viewQrTitle");
        const deviceViewQrImg = document.getElementById("deviceViewQrImg");
        viewQrTitle.textContent = `${device.marca} - REF ${device.ref}`;
        deviceViewQrImg.src = device.imagenQr;
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
    const cardBody = document.createElement("div");
    cardBody.classList.add("cardBody");
    const cardDataSheetNum = document.createElement("div");
    cardDataSheetNum.classList.add("cardDataSheetNum");
    cardDataSheetNum.innerHTML = `<h2>${device.marca}</h2>`;
    cardBody.appendChild(cardDataSheetNum);
    const cardBodyTxt = document.createElement("div");
    cardBodyTxt.classList.add("cardBodyTxt");
    cardBodyTxt.innerHTML = `<p>${device.estado}</p>
                                <h3>REF ${device.ref}</h3>`;
    if (device.estado == "Ocupado") {
      cardDataSheetNum.classList.add("cardRoomNumAlt");
      cardBodyTxt.classList.add("cardBodyTxtAlt");
    }
    cardBody.appendChild(cardBodyTxt);
    cardDevice.appendChild(cardTop);
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
  const status = statusSelectFilter.value;
  const number = numberInputFilter.value;
  let newdevices = devices;
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

const deviceAssocInfo = async (device) => {
  const deviceAssocInfo = document.querySelector(".deviceAssocInfo");
  deviceAssocInfo.innerHTML = "";
  deviceAssocInfo.innerHTML += `<input type="hidden" name="deviceIdAssoc" id="deviceIdAssoc" value="${device.idComputador}">`;
  deviceAssocInfo.innerHTML += `<input type="hidden" name="deviceStatusAssoc" id="deviceStatusAssoc" value="${device.estado}">`;
  const deviceAssocTitle = document.createElement("h3");
  deviceAssocTitle.innerHTML = `${device.marca} - ${device.ref}`;
  deviceAssocInfo.appendChild(deviceAssocTitle);
  if (device.estado == "Disponible") {
    const assocInfo = document.createElement("p");
    (device.ambiente == "Mesa Ayuda") ? assocInfo.innerHTML = `${device.ambiente} - ${device.centro}` : assocInfo.innerHTML = `Ambiente ${device.ambiente} - ${device.centro}`;
    deviceAssocInfo.appendChild(assocInfo);
  } else {
    const res = await fetch(`${regEquiposAPI}?idComputador=${device.idComputador}`);
    const data = await res.json();
    const roomAssocUser = document.createElement("p");
    roomAssocUser.innerHTML = `Vinculada con <strong>${data.usuario}</strong>`;
    deviceAssocInfo.appendChild(roomAssocUser);
    const roomAssocStart = document.createElement("p");
    roomAssocStart.classList.add("roomAssocStart");
    roomAssocStart.innerHTML = `<strong>Inicio:</strong> ${data.inicio}`;
    deviceAssocInfo.appendChild(roomAssocStart);
  }
};

const deviceAssoc = (device) => {
  openModal("deviceAssoc");
  const titleDeviceAssoc = document.getElementById("titleDeviceAssoc");
  device.estado == "Disponible"
    ? (titleDeviceAssoc.innerHTML = `Vincular Equipo`)
    : (titleDeviceAssoc.innerHTML = `Desvincular Equipo`);
  deviceAssocInfo(device);
  renderScanQR("user");
  configScanQR(filterDoc);
};

const deviceAssocHistory = async (method, json, userData) => {
  const jsonData = JSON.stringify(json);
  const res = await fetch(regEquiposAPI, {
    method: method,
    headers: {
      "Content-Type": "application/json",
    },
    body: jsonData,
  });
  const data = await res.json();
  if (data.success == true) {
    updateRenderDevices();
    renderSenaCard();
    loadSenaCard(
      "picSenaCard",
      "roleSenaCard",
      "nameUserSenaCard",
      "docUserSenaCard",
      "fichaSenaCard",
      userData
    );
    showMessage(
      "messageDeviceAssoc",
      "messageOK",
      data.message,
      "deviceAssoc",
      2500
    );
  } else {
    showMessage("messageDeviceAssoc", "messageErr", data.message, "", 2500);
  }
};

const filterDoc = async (doc) => {
  const deviceIdAssoc = document.getElementById("deviceIdAssoc").value;
  const deviceStatusAssoc = document.getElementById("deviceStatusAssoc").value;
  const res = await fetch(`${usuariosAPI}?docUserAssoc2=${doc}`);
  const data = await res.json();
  if (data.success == true) {
    const userData = data.user;
    if (deviceStatusAssoc == "Disponible") {
      const dataAssoc = {
        idUserAssoc: data.user.idUsuario,
        idDevice: deviceIdAssoc,
      };
      deviceAssocHistory("POST", dataAssoc, userData);
    } else {
      const dataAssoc = {
        idUserAssoc: data.user.idUsuario,
        idDevice: deviceIdAssoc,
      };
      deviceAssocHistory("PUT", dataAssoc, userData);
    }
  } else {
    showMessage("messageDeviceAssoc", "messageErr", data.message, "", 2000);
  }
};

const deviceImportForm = document.getElementById("deviceImportForm");
deviceImportForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(deviceImportForm);
  const res = await fetch(importEquiposAPI, {
    method: "POST",
    body: formData,
  });
  const data = await res.json();
  if (data.success == true) {
    updateRenderDevices();
    deviceImportForm.reset();
    showMessage("messageImport", "messageOK", data.message, "deviceImport", 1500);
  } else {
    showMessage("messageImport", "messageErr", data.message, "", 1500);
  }
});