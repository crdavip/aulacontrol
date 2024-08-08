const numberInputFilter = document.getElementById("numberInputFilter");
const envInputFilter = document.getElementById("envInputFilter");
const centerSelectFilter = document.getElementById("centerSelectFilter");
const statusSelectFilter = document.getElementById("statusSelectFilter");

loadSelectFilters(equiposAPI, "statusSelectFilter", ["estado"]);

const roomPdf = document.getElementById("selectedRoomDevicesPdf");
const roomExcel = document.getElementById("selectedRoomDevicesExcel");

let renderRoomsInExport;
let roomsList;
let centersList;
const selectListRooms = document.getElementById("idRoom");
const selectCenterDevice = document.getElementById("centerDevice");

const getAmbsForExport = async () => {
  const dataAmbientesExport = await getData(ambientesAPI);
  let roomsListExport = await dataAmbientesExport;

  let contentSelectTagRoomsExport = roomsListExport
    .map((room) => {
      return `<option value="${room.idAmbiente}">${room.numero}</option>`;
    })
    .join("");

  roomPdf.innerHTML =
    `<option value="">Seleccione un Ambiente</option>` +
    contentSelectTagRoomsExport;
  roomExcel.innerHTML =
    `<option value="">Seleccione un Ambiente</option>` +
    contentSelectTagRoomsExport;
};

const getDataAmbs = async () => {
  const dataCentros = await getData(centrosAPI);
  const dataAmbientes = await getData(ambientesAPI);
  centersList = dataCentros.filter((item) => item.siglas !== "PORT");
  roomsList = dataAmbientes.filter((item) => item.numero !== "Mesa Ayuda");

  let contentSelectTagCenters = centersList
    .map((center) => {
      return `<option value="${center.idCentro}">${center.detalle}</option>`;
    })
    .join("");
  selectCenterDevice.innerHTML =
    `<option value="">Seleccione un Centro</option>` + contentSelectTagCenters;
  updateRoomsDropdown();
};

const updateRoomsDropdown = () => {
  const selectedCenter = selectCenterDevice.value;
  const filteredRooms = roomsList.filter(
    (room) => room.idCentro == selectedCenter
  );
  let contentSelectTagRooms = filteredRooms
    .map((room) => {
      return `<option value="${room.idAmbiente}">${room.numero}</option>`;
    })
    .join("");
  renderRoomsInExport = contentSelectTagRooms;
  selectListRooms.innerHTML =
    `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
};

let devices = [];
const loadRenderDevices = async () => {
  const data = await getData(equiposAPI);
  devices = data;
  renderDevices(devices);
  getDataAmbs();
  getAmbsForExport();
};

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
    const cardTop = document.createElement("div");
    cardTop.classList.add("cardTop");
    if (userRolView == 1) {
      const cardMenu = document.createElement("a");
      cardMenu.classList.add("cardMenu");
      cardMenu.innerHTML = '<i class="fa-solid fa-ellipsis"></i>';
      const cardMenuItems = document.createElement("div");
      cardMenuItems.classList.add("cardMenuItems");
      const btnViewQr = document.createElement("a");
      btnViewQr.innerHTML = '<i class="fa-solid fa-qrcode"></i></i>Ver QR';
      const btnEdit = document.createElement("a");
      btnEdit.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Editar';
      const btnDelete = document.createElement("a");
      btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
      cardMenuItems.appendChild(btnViewQr);
      cardMenuItems.appendChild(btnEdit);
      cardMenuItems.appendChild(btnDelete);
      cardTop.appendChild(cardMenuItems);
      cardTop.appendChild(cardMenu);
      dropDown(cardMenu, cardMenuItems);
      btnViewQr.addEventListener("click", () => {
        openModal("deviceViewQr");
        const viewQrTitle = document.querySelector(".viewQrTitle");
        const deviceViewQrImg = document.getElementById("deviceViewQrImg");
        viewQrTitle.textContent = `${device.ref}`;
        deviceViewQrImg.src = device.imagenQr;
      });
      btnEdit.addEventListener("click", () => {
        const selectEditCenter = document.getElementById("centerDeviceEdit");
        const selectEditRoom = document.getElementById("deviceAmbEdit");

        let roomFiltered = roomsList.find(
          (room) => room.numero == device.ambiente
        );
        let centerFiltered = centersList.find(
          (center) => center.idCentro === roomFiltered.idCentro
        );

        let contentSelectTagCenterFiltered = centersList.filter(
          (center) => center.idCentro !== centerFiltered.idCentro
        );
        let contentSelectTagCenter = contentSelectTagCenterFiltered
          .map((center) => {
            return `<option value="${center.idCentro}">${center.detalle}</option>`;
          })
          .join("");

        selectEditCenter.innerHTML =
          `<option value="${centerFiltered.idCentro}">${centerFiltered.detalle}</option>` +
          contentSelectTagCenter;

        const selectedCenter = selectEditCenter.value;
        const filteredRooms = roomsList.filter(
          (room) =>
            room.idCentro == selectedCenter && room.numero !== device.ambiente
        );
        // const filteredRooms = roomsList.filter(room => room.idCentro == selectedCenter);
        let contentSelectTagRooms = filteredRooms
          .map((room) => {
            return `<option value="${room.idAmbiente}">${room.numero}</option>`;
          })
          .join("");
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
        const filteredRooms = roomsList.filter(
          (room) => room.idCentro == selectedCenter
        );

        let contentSelectTagRooms = filteredRooms
          .map((room) => {
            return `<option value="${room.idAmbiente}">${room.numero}</option>`;
          })
          .join("");

        selectEditRoom.innerHTML =
          `<option value="">Seleccione un Ambiente</option>` +
          contentSelectTagRooms;
      };

      document
        .getElementById("centerDeviceEdit")
        .addEventListener("change", updateRoomsDropdownEdit);
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
                                <h3>REF ${device.ref}</h3>
                                <span>Ambiente ${device.ambiente}</span>`;
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

const filterByEnv = () => {
  const env = envInputFilter.value;
  let newdevices = devices;
  if (env !== "") {
    newdevices = newdevices.filter((device) =>
      `${device.ambiente.toLowerCase()}`.includes(`${env.toLowerCase()}`)
    );
  }
  renderDevices(newdevices);
};

statusSelectFilter.addEventListener("change", filterDevices);
numberInputFilter.addEventListener("keyup", filterDevices);
envInputFilter.addEventListener("keyup", filterByEnv);

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

ExportFormExcel("deviceExportFormExcel", equiposAPI);

ExportFormPdf("deviceExportFormPdf", equiposAPI);

const deviceAssocInfo = async (idUser) => {
  const res = await fetch(usuariosAPI);
  const data = await res.json();
  const userData = data.filter((item) => item.idUsuario == idUser);
  const deviceAssocInfo = document.querySelector(".deviceAssocInfo");
  deviceAssocInfo.innerHTML = "";
  deviceAssocInfo.innerHTML += `<input type="hidden" name="userIdAssoc" id="userIdAssoc" value="${userData[0].idUsuario}">`;
  const deviceAssocTitle = document.createElement("h3");
  deviceAssocTitle.innerHTML = `${userData[0].nombre}`;
  deviceAssocInfo.appendChild(deviceAssocTitle);
  const assocInfo = document.createElement("p");
  assocInfo.innerHTML = `CC ${userData[0].documento} - ${userData[0].cargo}`;
  deviceAssocInfo.appendChild(assocInfo);
};

const assocDeviceBtn = document.getElementById("assocDeviceBtn");
const assocIdUser = assocDeviceBtn.getAttribute("data-id");

const deviceAssoc = async (idUser) => {
  openModal("deviceAssoc");
  const titleDeviceAssoc = document.getElementById("titleDeviceAssoc");
  titleDeviceAssoc.innerHTML = `Vincular Equipo`;
  deviceAssocInfo(idUser);
  renderScanQR("device");
  configScanQR(filterDevice);
};

assocDeviceBtn.addEventListener("click", () => {
  deviceAssoc(assocIdUser);
});

const deviceAssocHistory = async (method, json, deviceData) => {
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

const filterDevice = async (ref) => {
  const userIdAssoc = document.getElementById("userIdAssoc").value;
  const res = await fetch(`${equiposAPI}?deviceAssoc=${ref}`);
  const data = await res.json();
  if (data.success == true) {
    const deviceData = data.device;
    const dataAssoc = {
      idDeviceAssoc: data.device.idComputador,
      idUSer: userIdAssoc,
    };
    deviceAssocHistory("POST", dataAssoc, deviceData);
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