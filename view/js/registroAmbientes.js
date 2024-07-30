import { getDataHistory } from "../js/fetch.js";

const numberInputFilter = document.getElementById("numberInputFilter");
const dateInputFilter = document.getElementById("dateInputFilter");
const selectPgLimit = document.getElementById("selectPgLimit");
const pgNextBtn = document.getElementById("pgNext");
const pgPrevBtn = document.getElementById("pgPrev");
const tableBody = document.getElementById("tableBody");
const roomPdf = document.getElementById("selectedRoomPdf");
const roomExcel = document.getElementById("selectedRoomExcel");

const getDataAmbs = async () => {
  const dataAmbientes = await getData(ambientesAPI);
  let roomsList = await dataAmbientes;
  roomsList = roomsList.filter(roomItem => roomItem.centro === "CDMC");
  let contentSelectTagRooms = roomsList.map((room) => {
    return `<option value="${room.idAmbiente}">${room.numero}</option>`;
  }).join("");

  roomPdf.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
  roomExcel.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
}

let dataHistory = await getDataHistory(regAmbientesAPI);

let pgFrom = 0;
let pgLimit = parseInt(selectPgLimit.value);
let pages = Math.ceil(dataHistory.length / pgLimit);
let pgActive = 1;

let history = dataHistory.slice(pgFrom, pgLimit);

const getHistory = async (history) => {
  tableBody.innerHTML = "";
  history.forEach((row) => {
    const startDate = formatDate(row.inicio);
    const endDate = formatDate(row.fin);
    tableBody.innerHTML += `
    <tr>
      <td data-title="Instructor" class="tdCol2"><div><img src="${row.imagen}" width="50"></div><div><strong class="historyUserName">${row.nombre}</strong><br><span class="historyUserDoc">C.C. ${row.documento}</span></div></td>
      <td data-title="Ambiente"><div class="tdRow2"><strong class="historyRoomNum">Ambiente ${row.numero}</strong><span class="historyCenterName">${row.centro}</span></div></td>
      <td data-title="Inicio"><strong class="tdDate">${startDate.dateFormat}</strong><br><span class="tdTime">${startDate.timeFormat}</span></td>
      ${row.fin === null
        ? `<td data-title="Fin"><span class="tdStatus">Pendiente</span></td>`
        : `<td data-title="Fin"><strong class="tdDate">${endDate.dateFormat}</strong><br><span class="tdTime">${endDate.timeFormat}</span></td>`}
      <td data-title="Llaves" class="tdBool">${row.llaves === 1 ? `<span class="tdTrue">Si</span>` : `<span class="tdFalse">No</span>`}</td>
      <td data-title="Tv" class="tdBool">${row.controlTv === 1 ? `<span class="tdTrue">Si</span>` : `<span class="tdFalse">No</span>`}</td>
      <td data-title="Aire" class="tdBool">${row.controlAire === 1 ? `<span class="tdTrue">Si</span>` : `<span class="tdFalse">No</span>`}</td>
    </tr>
    `;
  });
  loadItemsPg(pages);
};

const renderHistory = (history) => {
  if (history.length > 0) {
    tableBody.innerHTML = "";
    getHistory(history);
    getDataAmbs();
  } else {
    tableBody.innerHTML = "No hay resultados para mostrar";
  }
};

const updateDataHistory = () => {
  history = dataHistory.slice(pgFrom, pgLimit * pgActive);
  renderHistory(history);
}

const loadItemsPg = (pages) => {
  const paginationItems = document.getElementById("paginationItems");
  paginationItems.innerHTML = "";
  for (let i = 0; i < pages; i++) {
    const paginationItem = document.createElement("button");
    paginationItem.classList.add("paginationItem");
    paginationItem.addEventListener("click", () => {
      pgShow(i);
    });
    paginationItem.innerHTML = `${i + 1}`;
    paginationItem.innerHTML == pgActive
      ? paginationItem.classList.add("paginationItemActive")
      : null;
    pgActive == 1 ? pgPrevBtn.disabled = true : pgPrevBtn.disabled = false;
    pgActive == pages ? pgNextBtn.disabled = true : pgNextBtn.disabled = false;
    paginationItems.appendChild(paginationItem);
  }
};

const pgShow = (page) => {
  pgActive = page + 1;
  pgFrom = pgLimit * page;
  if (pgFrom <= dataHistory.length) {
    updateDataHistory();
  }
};

const pgNext = () => {
  if (pgActive < pages) {
    pgFrom += pgLimit;
    pgActive++;
    updateDataHistory();
    pgPrevBtn.disabled = false;
  } else {
    pgNextBtn.disabled = true;
  }
};

const pgPrev = () => {
  if (pgFrom > 0) {
    pgActive--;
    pgFrom -= pgLimit;
    updateDataHistory();
    pgNextBtn.disabled = false;
  } else {
    pgPrevBtn.disabled = true;
  }
};

pgNextBtn.addEventListener("click", pgNext);
pgPrevBtn.addEventListener("click", pgPrev);

const pagination = (data) => {
  pgLimit = parseInt(selectPgLimit.value);
  pages = Math.ceil(data.length / pgLimit);
  pgActive = 1;

  history = data.slice(pgFrom, pgLimit);
  loadItemsPg(pages);
  pgShow(0);
  renderHistory(history);
}

pagination(dataHistory);

//Filtrar Registros
const filterHistory = async () => {
  const number = numberInputFilter.value;
  const date = dateInputFilter.value;
  if (number !== "") {
    dataHistory = dataHistory.filter((row) =>
      `${row.numero.toLowerCase()}`.includes(`${number.toLowerCase()}`)
    );
  }
  if (date !== "") {
    dataHistory = dataHistory.filter((row) => row.inicio.includes(date));
  }
  pagination(dataHistory);
};
numberInputFilter.addEventListener("keyup", filterHistory);
dateInputFilter.addEventListener("change", filterHistory);

selectPgLimit.addEventListener("change", () => {
  pgLimit = parseInt(selectPgLimit.value);
  pagination(dataHistory);
});

ExportFormExcel(
  "roomExportFormExcel",
  regAmbientesAPI,
);

ExportFormPdf(
  "roomExportFormPdf",
  regAmbientesAPI,
);