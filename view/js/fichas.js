const numberInputFilter = document.getElementById("numberInputFilter");
const centerSelectFilter = document.getElementById("centerSelectFilter");

const searchAssistanceTraineesInput = document.getElementById("traineesAssistanceSearch");
// const inputIdSheetAssistance = document.getElementById("inputIdSheetAssistance");
const resultsAssistanceSearchDiv = document.getElementById("resultsTraineesAssistanceSearch");
let idSheetAssistance;

const searchListTraineesInput = document.getElementById("traineesListSearch");
// const inputIdSheetList = document.getElementById("inputIdSheetList");
const resultsListSearchDiv = document.getElementById("resultsTraineesListSearch");
let idSheetList;

const searchAddTraineesInput = document.getElementById("traineesAddSearch");
// const inputIdSheetAdd = document.getElementById("inputIdSheetAdd");
const resultsAddSearchDiv = document.getElementById("resultsTraineesAddSearch");
let idSheetAdd;

loadSelectFilters(centrosAPI, "centerSelectFilter", ["siglas"]);

let dataSheets = [];
const loadDataSheets = async () => {
  const data = await getData(fichasAPI);
  dataSheets = data;
  renderDataSheets(dataSheets);
};

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
        // inputIdSheetList.valule = sheet.idFicha;
        // inputIdSheetAdd.value = sheet.idFicha;
        idSheetAdd = sheet.idFicha;
        idSheetList = sheet.idFicha;
        getDataOfSheetList();
        openModal("dataSheetListTrainees");
      });
      btnAssistance.addEventListener("click", () => {
        idSheetAssistance = sheet.idFicha;
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
      `${dataSheet.ficha}`.includes(`${number}`)
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
  console.log(results);

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
              <span>${result.estado}</span>
            </div>
          </div>
          <button id="btnOpenModalRemove" onclick="openModal('dataSheetremoveTrainee')" class="btn btnAlt" type="submit"><i class="fa-regular fa-trash-can"></i></button>
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
    const response = await fetch(`${aprendicesAPI}.php?queryList=${idSheetList}&searchDoc=${doc}`);
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
            <span>${result.estado}</span>
          </div>
        </div>
        <button id="btnOpenModalRemove" onclick="openModal('dataSheetremoveTrainee')" class="btn btnAlt" type="submit"><i class="fa-regular fa-trash-can"></i></button>
          `;
      resultsListSearchDiv.appendChild(div);
    });
  } else {
    getDataOfSheetList();
  }
});

// Asistencia de aprendices
searchAssistanceTraineesInput.addEventListener('keyup', async function () {
  const doc = searchAssistanceTraineesInput.value
  if (doc.length > 0) {
    const response = await fetch(`${usuariosAPI}.php?query=${doc}`);
    const results = await response.json();
    resultsAssistanceSearchDiv.innerHTML = '';
    results.forEach(result => {
      const div = document.createElement('div');
      div.classList.add("divCardSearchTrainee")
      div.innerHTML = `
        <div>
          <img src=${result.imagen} with="50" heigh="50"  alt="">
          <div>
            <span>${result.nombre}</span>
            <span>${result.estado}</span>
          </div>
        </div>
        <input type="checkbox" class="customCheckbox">
          `;
      resultsAssistanceSearchDiv.appendChild(div);
    });
  } else {
    resultsAssistanceSearchDiv.innerHTML = '';
  }
});

// Agregar aprendices
searchAddTraineesInput.addEventListener('keyup', async function () {
  const doc = searchAddTraineesInput.value;
  if (doc.length > 0) {
    const response = await fetch(`${usuariosAPI}.php?queryAdd=${doc}`);
    const results = await response.json();
    resultsAddSearchDiv.innerHTML = '';
    results.forEach(result => {
      const div = document.createElement('div');
      div.classList.add("divCardSearchTrainee")
      div.innerHTML = `
        <div>
          <img src=${result.imagen} with="50" heigh="50"  alt="">
          <div>
            <span>${result.nombre}</span>
            <span>${result.estado}</span>
          </div>
        </div>
        <input type="checkbox" class="customCheckbox" data-id="${result.idUsuario}">
          `;
      resultsAddSearchDiv.appendChild(div);
    });
  } else {
    resultsAddSearchDiv.innerHTML = '';
  }
});

document.getElementById("saveTraineesSelected").addEventListener("click", async function () {
  const checkboxes = document.querySelectorAll('.customCheckbox:checked');
  const selectedIds = Array.from(checkboxes).map(checkbox => checkbox.getAttribute('data-id'));
  if (selectedIds.length > 0) {
    try {
      const response = await fetch(aprendicesAPI, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ ids: selectedIds, idSheet: idSheetAdd })
      });

      const result = await response.json();
      let messageType
      let messageContent;

      switch (result.insertion) {
        case 'completa':
          messageType = 'messageOK';
          messageContent = 'Todos los aprendices se guardaron correctamente.';
          break;
        case 'parcial':
          messageType = 'messageAlert';
          messageContent = `Se guardaron algunos aprendices. IDs omitidos: ${result.details.excludedIds.join(', ')}.`;
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
      showMessage("messageSheetAdd", messageType, messageContent, "dataSheetAddTrainees", 1500);
    } catch (error) {
      showMessage("messageSheetAdd", "messageErr", 'Error al procesar la solicitud.', "dataSheetAddTrainees", 1500);
    }
  } else {
    showMessage("messageSheetAdd", "messageErr", 'No se seleccionó ningún aprendiz.', "dataSheetAddTrainees", 1500);
  }
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

// Agregar aprendiz a la ficha
// sendForm(
//   "dataSheetAddTraineesForm",
//   aprendicesAPI,
//   "POST",
//   "messageSheetAdd",
//   updateDataSheets,
//   "dataSheetAddTrainees",
//   1500
// );
