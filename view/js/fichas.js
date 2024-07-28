const numberInputFilter = document.getElementById("numberInputFilter");
const centerSelectFilter = document.getElementById("centerSelectFilter");
const room = document.getElementById("selectedRoom");
const btnNavigateRegAssist = document.getElementById("btnNavigateRegAssist");

const searchAssistanceTraineesInput = document.getElementById("traineesAssistanceSearch");
const resultsAssistanceSearchDiv = document.getElementById("resultsTraineesAssistanceSearch");
const inputDate = document.getElementById("assistDate");
const selectRoomAssist = document.getElementById("selectedRoom");
let allTraineesAssist = [];
let filteredTraineesAssist = [];
let selectedTraineesAssist = new Set();
//  ? exportar variable a registro asistencia
let idSheetAssistance;
// let idSheetForExport;
// Función para establecer el valor
// export function setIdSheetAssistance(value) {
//   idSheetForExport = value;
// }
// // Función para obtener el valor
// export function getIdSheetAssistance() {
//   return idSheetForExport;
// }
// // ? Fin exportacion


const searchListTraineesInput = document.getElementById("traineesListSearch");
const resultsListSearchDiv = document.getElementById("resultsTraineesListSearch");
let idSheetList;
let idTrainee;

const searchAddTraineesInput = document.getElementById("traineesAddSearch");
const resultsAndSelectedContainer = document.getElementById("resultsTraineesAddSearch");
let allTraineesAdd = [];
let filteredTraineesAdd = [];
let selectedTraineesAdd = new Set();
let idSheetAdd;

// For Removing Trainee
const inputRemoveIdTrainee = document.getElementById("dataSheetIdRemoveTrainee");
const inputRemoveIdSheet = document.getElementById("dataSheetIdRemoveSheet");

loadSelectFilters(centrosAPI, "centerSelectFilter", ["siglas"]);

const getDataAmbs = async () => {
  const dataAmbientes = await getData(ambientesAPI);
  let roomsList = await dataAmbientes;
  roomsList = roomsList.filter(roomItem => roomItem.centro === "CDMC");
  let contentSelectTagRooms = roomsList.map((roomItem) => {
    return `<option value="${roomItem.idAmbiente}">${roomItem.numero}</option>`;
  }).join("");
  room.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
}

let dataSheets = [];
const loadDataSheets = async () => {
  const data = await getData(fichasAPI);
  dataSheets = data;
  renderDataSheets(dataSheets);
  getDataOfSheetList();
  getDataAmbs();
};

const loadAllTrainees = async () => {
  try {
    const response = await fetch(`${usuariosAPI}.php?queryAll=true`);
    console.log("idSheetList: ", response);
    const responseAssist = await fetch(`${aprendicesAPI}.php?paramSheet=${idSheetAssistance}`);
    const trainees = await response.json();
    const traineesAssist = await responseAssist.json();
    allTraineesAdd = trainees;
    allTraineesAssist = traineesAssist;
    filteredTraineesAdd = trainees;
    console.log(filteredTraineesAdd);
    filteredTraineesAssist = traineesAssist;
    renderTrainees(resultsAndSelectedContainer, selectedTraineesAdd, filteredTraineesAdd, allTraineesAdd, "add");
    renderTrainees(resultsAssistanceSearchDiv, selectedTraineesAssist, filteredTraineesAssist, allTraineesAssist, "assist");
  } catch (error) {
    console.error('Error al cargar los aprendices:', error);
  }
};

const renderTrainees = (divOfRender, selectedTrainees, filteredTrainees, allTrainees, render) => {
  divOfRender.innerHTML = '';
  const traineesToRender = filteredTrainees.filter(trainee => !selectedTrainees.has(trainee.idUsuario));
  if (allTrainees.length === 0) {
    divOfRender.innerHTML = '<p>Ningún aprendiz asociado.</p>';
  }

  traineesToRender.forEach(trainee => {
    const div = document.createElement('div');
    div.classList.add("divCardSearchTrainee");
    div.innerHTML = `
      <div>
        <img src="${trainee.imagen}" width="50" height="50" alt="">
        <div>
          <span>${trainee.nombre}</span>
          <span>${trainee.documento}</span>
        </div>
      </div>
      <button onclick="toggleSelection(${trainee.idUsuario}, '${render}')" class="btn btnAlt">
        <i class="fa-solid ${selectedTrainees.has(trainee.idUsuario) ? 'fa-minus' : 'fa-plus'}"></i>
      </button>
    `;
    divOfRender.appendChild(div);
  });

  selectedTrainees.forEach(id => {
    const trainee = allTrainees.find(t => t.idUsuario === id);
    if (trainee) {
      const div = document.createElement('div');
      div.classList.add("divCardSearchTrainee");
      div.innerHTML = `
        <div>
          <img src="${trainee.imagen}" width="50" height="50" alt="">
          <div>
            <span>${trainee.nombre}</span>
            <span>${trainee.documento}</span>
          </div>
        </div>
        <button onclick="toggleSelection(${trainee.idUsuario}, '${render}')" class="btn btnAlt">
          <i class="fa-solid fa-minus"></i>
        </button>
      `;
      divOfRender.appendChild(div);
    }
  });
};

const filterTrainees = (filteredTrainees, allTrainees, divOfRender, selectedTrainees, render) => {
  const query = render === "add" ? searchAddTraineesInput.value : searchAssistanceTraineesInput.value;
  filteredTrainees = allTrainees.filter(trainee => {
    const documentStr = trainee.documento.toString();
    return documentStr.startsWith(query);
  }
  );
  renderTrainees(divOfRender, selectedTrainees, filteredTrainees, allTrainees, render);
};

const toggleSelection = (id, render) => {
  if (render == "add") {
    if (selectedTraineesAdd.has(id)) {
      selectedTraineesAdd.delete(id);
    } else {
      selectedTraineesAdd.add(id);
    }
    renderTrainees(resultsAndSelectedContainer, selectedTraineesAdd, filteredTraineesAdd, allTraineesAdd, render);
  } else {
    if (selectedTraineesAssist.has(id)) {
      selectedTraineesAssist.delete(id);
    } else {
      selectedTraineesAssist.add(id);
    }
    renderTrainees(resultsAssistanceSearchDiv, selectedTraineesAssist, filteredTraineesAssist, allTraineesAssist, render);
  }
};

searchAddTraineesInput.addEventListener('keyup', () => filterTrainees(filteredTraineesAdd, allTraineesAdd, resultsAndSelectedContainer, selectedTraineesAdd, "add"));
searchAssistanceTraineesInput.addEventListener('keyup', () => filterTrainees(filteredTraineesAssist, allTraineesAssist, resultsAssistanceSearchDiv, selectedTraineesAssist, "assist"));
window.addEventListener("DOMContentLoaded", loadDataSheets);

const updateDataSheets = async () => {
  await loadDataSheets();
};

const createDataSheetCard = (dataSheets) => {
  const fragment = document.createDocumentFragment();
  dataSheets.forEach((sheet) => {
    const card = document.createElement("div");
    card.classList.add("card");
    const cardTop = document.createElement("div");
    cardTop.classList.add("cardTop");
    if (userRolView == 1) {
      const cardMenu = document.createElement("a");
      cardMenu.classList.add("cardMenu");
      cardMenu.innerHTML = '<i class="fa-solid fa-ellipsis"></i>';
      const cardMenuItems = document.createElement("div");
      cardMenuItems.classList.add("cardMenuItems");
      const btnTrainees = document.createElement("a");
      btnTrainees.innerHTML =
        '<i class="fa-solid fa-user-graduate"></i>Aprendices';
      const btnAssistance = document.createElement("a");
      btnAssistance.innerHTML =
        '<i class="fa-regular fa-address-book"></i>Asistencia';
      const btnEdit = document.createElement("a");
      btnEdit.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Editar';
      const btnDelete = document.createElement("a");
      btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
      cardMenuItems.appendChild(btnTrainees);
      cardMenuItems.appendChild(btnAssistance);
      cardMenuItems.appendChild(btnEdit);
      cardMenuItems.appendChild(btnDelete);
      cardTop.appendChild(cardMenuItems);
      cardTop.appendChild(cardMenu);
      dropDown(cardMenu, cardMenuItems);
      btnTrainees.addEventListener("click", () => {
        idSheetAdd = sheet.idFicha;
        idSheetList = sheet.idFicha;
        getDataOfSheetList();
        loadAllTrainees();
        openModal("dataSheetListTrainees");
      });
      btnAssistance.addEventListener("click", () => {
        idSheetAssistance = sheet.idFicha;
        // setIdSheetAssistance(sheet.idFicha);
        localStorage.setItem('idSheet', sheet.idFicha);
        loadAllTrainees();
        openModal("dataSheetAssistanceTrainees");
      });
      btnEdit.addEventListener("click", () => {
        loadDataForm({
          inputs: [
            "dataSheetIdEdit",
            "dataSheetNumEdit",
            "dataSheetCourseEdit",
            "dataSheetCenterEdit",
          ],
          inputsValue: [sheet.idFicha, sheet.ficha, sheet.curso, sheet.idCentro],
          modal: "dataSheetEdit",
        });
      });
      btnDelete.addEventListener("click", () => {
        loadDataForm({
          inputs: ["dataSheetIdDelete"],
          inputsValue: [sheet.idFicha],
          modal: "dataSheetDelete",
        });
      });
    }
    const cardBody = document.createElement("div");
    cardBody.classList.add("cardBody");
    const cardDataSheetNum = document.createElement("div");
    cardDataSheetNum.classList.add("cardDataSheetNum");
    cardDataSheetNum.innerHTML = `<h2>${sheet.ficha}</h2>`;
    cardBody.appendChild(cardDataSheetNum);
    const cardBodyTxt = document.createElement("div");
    cardBodyTxt.classList.add("cardBodyTxt");
    cardBodyTxt.innerHTML = `<p>${sheet.curso}</p>
                                <h3>${sheet.centro}</h3>
                                <span>${sheet.aprendices} ${sheet.aprendices == 1 ? "Aprendiz" : "Aprendices"
      }</span>`;
    cardBody.appendChild(cardBodyTxt);
    card.appendChild(cardTop);
    card.appendChild(cardBody);
    fragment.appendChild(card);
  });
  return fragment;
};

// window.addEventListener('DOMContentLoaded', loadAllTrainees);

const row = document.querySelector(".row");
const renderDataSheets = (data) => {
  if (data.length > 0) {
    const cards = createDataSheetCard(data);
    row.innerHTML = "";
    row.appendChild(cards);
  } else {
    row.innerHTML = "No hay resultados para mostrar.";
  }
};

const filterDataSheets = () => {
  const center = centerSelectFilter.value;
  const number = numberInputFilter.value;
  let newDataSheets = dataSheets;
  if (center !== "all") {
    newDataSheets = newDataSheets.filter(
      (dataSheet) => dataSheet.centro == center
    );
  }
  if (number !== "") {
    newDataSheets = newDataSheets.filter((dataSheet) =>
      `${dataSheet.ficha}`.has(`${number}`)
    );
  }
  renderDataSheets(newDataSheets);
};
centerSelectFilter.addEventListener("change", filterDataSheets);
numberInputFilter.addEventListener("keyup", filterDataSheets);

loadSelectFilters(centrosAPI, "dataSheetCenter", ["idCentro", "detalle"]);
loadSelectFilters(centrosAPI, "dataSheetCenterEdit", ["idCentro", "detalle"]);

// Lista de aprendices predeterminada
const getDataOfSheetList = async () => {
  const response = await fetch(`${aprendicesAPI}.php?paramSheet=${idSheetList}`);
  const results = await response.json();

  if (results.length > 0) {
    resultsListSearchDiv.innerHTML = '';
    results.forEach(result => {
      const div = document.createElement('div');
      div.classList.add("divCardSearchTrainee")
      div.innerHTML = `
          <div>
            <img src=${result.imagen} with="50" heigh="50"  alt="">
            <div>
              <span>${result.nombre}</span>
              <span>${result.documento}</span>
            </div>
          </div>
          <button id="btnOpenModalRemove" onclick="openRemoveModal(this)" data-id-trainee="${result.idAprendices}" data-id-sheet="${result.idFicha}" class="btn btnAlt"><i class="fa-regular fa-trash-can"></i></button>
            `;
      resultsListSearchDiv.appendChild(div);
    });

  } else {
    resultsListSearchDiv.innerHTML = '<p>Esta ficha no tiene aprendices asociados.</p>';
  }
}

// Lista de aprendices Busqueda
searchListTraineesInput.addEventListener('keyup', async function () {
  const doc = searchListTraineesInput.value;
  if (doc.length > 0) {
    const response = await fetch(`${aprendicesAPI}.php?queryList=${idSheetAssistance}&searchDoc=${doc}`);
    const results = await response.json();
    resultsListSearchDiv.innerHTML = '';
    results.forEach(result => {
      const div = document.createElement('div');
      div.classList.add("divCardSearchTrainee");
      div.innerHTML = `
        <div>
          <img src=${result.imagen} with="50" heigh="50"  alt="">
          <div>
            <span>${result.nombre}</span>
            <span>${result.documento}</span>
          </div>
        </div>
        <button id="btnOpenModalRemove" onclick="openRemoveModal(this)" data-id-trainee="${result.idAprendices}" data-id-sheet="${result.idFicha}" class="btn btnAlt"><i class="fa-regular fa-trash-can"></i></button>
          `;
      resultsListSearchDiv.appendChild(div);
    });
  } else {
    getDataOfSheetList();
  }
});

const openRemoveModal = (button) => {
  const idAprendiz = button.getAttribute('data-id-trainee');
  const idSheet = button.getAttribute('data-id-sheet');
  inputRemoveIdSheet.value = idSheet;
  inputRemoveIdTrainee.value = idAprendiz;
  openModal('dataSheetRemoveTrainee');
}

const saveButtons = document.querySelectorAll("#saveTraineesSelected, #saveAssistancesSelected");
const idRoomSelected = selectRoomAssist.value;
const cleantInformatoin = (selectionType) => {

  if (selectionType == 'selectedTraineesAdd') {
    allTraineesAdd = [];
    filteredTraineesAdd = [];
    selectedTraineesAdd.clear();
  } else {
    allTraineesAssist = [];
    filteredTraineesAssist = [];
    selectedTraineesAssist.clear();
    inputDate.value = '';
  }
  updateDataSheets();
}

saveButtons.forEach(button => {
  button.addEventListener("click", async () => {
    const selectionType = button.getAttribute('data-selection');
    let bodyObject;
    let apiRequired;
    let messageId, modalUsed;
    let selectedItems = selectionType === 'selectedTraineesAdd' ? selectedTraineesAdd : selectedTraineesAssist;
    const idsToSave = Array.from(selectedItems);
    if (selectionType === 'selectedTraineesAdd') {
      apiRequired = aprendicesAPI;
      messageId = "messageSheetAdd";
      modalUsed = "dataSheetAddTrainees";
      bodyObject = {
        ids: idsToSave,
        idSheet: idSheetAdd
      }
    } else if (selectionType === 'selectedTraineesAssist') {
      apiRequired = asistenciaAPI;
      messageId = "messageSheetAssist";
      modalUsed = "dataSheetAssistanceTrainees";
      const date = inputDate.value;
      const idRoomSelected = selectRoomAssist.value;
      bodyObject = {
        items: idsToSave,
        dateAssistance: date,
        sheet: idSheetAssistance,
        envrmnt: parseInt(idRoomSelected),
      }
    }
    console.log("body: ", bodyObject);
    if (idsToSave.length > 0) {
      try {
        const response = await fetch(`${apiRequired}.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(bodyObject)
        });

        const result = await response.json();
        console.log("result", result);
        let messageType;
        let messageContent;

        switch (result.insertion) {
          case 'completa':
            messageType = 'messageOK';
            if (messageId === "messageSheetAssist") {
              messageContent = 'Se ha registrado la asistencia correctamente.';
            } else {
              messageContent = 'Todos los elementos se guardaron correctamente.';
            }
            cleantInformatoin(selectionType);
            break;
          case 'parcial':
            messageType = 'messageAlert';
            messageContent = `Se guardaron algunos elementos. IDs omitidos: ${result.details.excludedIds.join(', ')}.`;
            break;
          case 'incompleta':
            messageType = 'messageErr';
            messageContent = `Error: ${result.details.error}`;
            break;
          default:
            messageType = 'messageErr';
            messageContent = 'Ocurrió un error inesperado.';
            break;
        }
        showMessage(messageId, messageType, messageContent, modalUsed, 1500);
        updateDataSheets();
      } catch (error) {
        console.log(error);
        showMessage(messageId, "messageErr", `Error al procesar la solicitud :${error}`, modalUsed, 1500);
      }


    } else {
      showMessage(messageId, "messageErr", 'No se seleccionó ningún elemento.', modalUsed, 1500);
    }
  });
});

btnNavigateRegAssist.addEventListener('click', () => {
  console.log(idSheetAssistance);
  window.location.href = "./registro-asistencia";
});

sendForm(
  "dataSheetCreateForm",
  fichasAPI,
  "POST",
  "messageCreate",
  updateDataSheets,
  "dataSheetCreate",
  1500
);

sendForm(
  "dataSheetEditForm",
  fichasAPI,
  "PUT",
  "messageEdit",
  updateDataSheets,
  "dataSheetEdit",
  1500
);

sendForm(
  "dataSheetDeleteForm",
  fichasAPI,
  "DELETE",
  "messageDelete",
  updateDataSheets,
  "dataSheetDelete",
  1500
);

sendForm(
  "dataSheetRemoveTraineeForm",
  aprendicesAPI,
  "DELETE",
  "messageRemove",
  updateDataSheets,
  "dataSheetRemoveTrainee",
  1500
);
