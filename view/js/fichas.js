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
        '<i class="fa-regular fa-address-book"></i>Lista';
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

// Lista de aprendices
searchListTraineesInput.addEventListener('keyup', async function () {
  const doc = searchListTraineesInput.value
  console.log(typeof (doc));
  if (doc.length > 0) {
    const response = await fetch(`${usuariosAPI}.php?query=${doc}`);
    const results = await response.json();

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
    resultsListSearchDiv.innerHTML = '';
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
    const response = await fetch(aprendicesAPI, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ ids: selectedIds, idSheet: idSheetAdd })
    });
    const result = await response.json();
    showMessage("messageSheetAdd", "messageOK", result.message, "dataSheetAddTrainees", 1500);
  } else {
    showMessage("messageSheetAdd", "messageErr", result.message, "dataSheetAddTrainees", 1500);
  }
})

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
