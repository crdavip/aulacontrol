import { getDataHistory } from "../js/fetch.js";

const numberInputFilter = document.getElementById("numberInputFilter");
const dateInputFilter = document.getElementById("dateInputFilter");
const selectPgLimit = document.getElementById("selectPgLimit");
const pgNextBtn = document.getElementById("pgNext");
const pgPrevBtn = document.getElementById("pgPrev");
const tableBody = document.getElementById("tableBody");
const roomPdf = document.getElementById("selectedRoomPdf");
const roomExcel = document.getElementById("selectedRoomExcel");

let dataHistory = await getDataHistory(regEquiposAPI);

let pgFrom = 0;
let pgLimit = parseInt(selectPgLimit.value);
let pages = Math.ceil(dataHistory.length / pgLimit);
let pgActive = 1;

let history = dataHistory.slice(pgFrom, pgLimit);

const getDataAmbs = async () => {
  const dataAmbientes = await getData(ambientesAPI);
  let roomsList = await dataAmbientes;

  let contentSelectTagRooms = roomsList.map((room) => {
    return `<option value="${room.idAmbiente}">${room.numero}</option>`;
  }).join("");

  roomPdf.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
  roomExcel.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
}

const formatDate = (data) => {
  const date = new Date(data);
  const year = date.getFullYear();
  let month = date.toLocaleDateString("es-ES", { month: "long" });
  month = month.charAt(0).toUpperCase() + month.slice(1);
  const day = date.toLocaleDateString("es-ES", { day: "2-digit" });
  const dateFormat = `${month} ${day}, ${year}`;
  const timeFormat = date.toLocaleTimeString("es-ES", {
    hour: "numeric",
    minute: "numeric",
    hour12: true,
  });
  return { dateFormat: dateFormat, timeFormat: timeFormat };
};

const getHistory = async (history) => {
  tableBody.innerHTML = "";
  history.forEach((row) => {
    const startDate = formatDate(row.inicio);
    const endDate = formatDate(row.fin);
    tableBody.innerHTML += `
    <tr>
      <td data-title="Usuario" class="tdCol2"><div><img src="${row.imagen}" width="50"></div><div><strong class="historyUserName">${row.usuario}</strong><br><span class="historyUserDoc">C.C. ${row.documento}</span></div></td>
      <td data-title="Equipo"><div class="tdRow2"><strong class="historyRoomNum">Equipo ${row.referencia} - ${row.marca}</strong><span class="historyCenterName">${row.centro} - ${row.ambiente}</span></div></td>
      <td data-title="Inicio"><strong class="tdDate">${startDate.dateFormat}</strong><br><span class="tdTime">${startDate.timeFormat}</span></td>
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
  paginationItems.innerHTML = `Página <strong>${pgActive}</strong> de <strong>${pages}</strong>`;
  pgActive == 1 ? pgPrevBtn.disabled = true : pgPrevBtn.disabled = false;
  pgActive == pages ? pgNextBtn.disabled = true : pgNextBtn.disabled = false;
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
  let newDataHistory = dataHistory;
  if (number !== "") {
    newDataHistory = newDataHistory.filter((row) =>
      `${row.documento}`.includes(`${number}`)
    );
  }
  if (date !== "") {
    newDataHistory = newDataHistory.filter((row) => row.fecha.includes(date));
  }
  pagination(newDataHistory);
  renderHistory(newDataHistory);
};
numberInputFilter.addEventListener("keyup", filterHistory);
dateInputFilter.addEventListener("change", filterHistory);

selectPgLimit.addEventListener("change", () => {
  pgLimit = parseInt(selectPgLimit.value);
  pagination(dataHistory);
});

ExportFormExcel(
  "regDeviceExportFormExcel",
  regEquiposAPI,
);

ExportFormPdf(
  "regDeviceExportFormPdf",
  regEquiposAPI,
);