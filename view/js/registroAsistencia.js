import { getDataHistory } from "../js/fetch.js";
// import { getIdSheetAssistance } from "./fichas.js";

const numberInputFilter = document.getElementById("numberInputFilter");
const dateInputFilter = document.getElementById("dateInputFilter");
const selectPgLimit = document.getElementById("selectPgLimit");
const pgNextBtn = document.getElementById("pgNext");
const pgPrevBtn = document.getElementById("pgPrev");
// const tableBody = document.getElementById("fullContainerResults");
const tableBody = document.getElementById("tableBody");
const roomPdf = document.getElementById("selectedRoomPdf");
const roomExcel = document.getElementById("selectedRoomExcel");

const getDataAmbs = async () => {
  const dataAmbientes = await getData(ambientesAPI);
  console.log(dataAmbientes);
  let roomsList = await dataAmbientes;
  roomsList = roomsList.filter(roomItem => roomItem.centro === "CDMC");
  let contentSelectTagRooms = roomsList.map((room) => {
    return `<option value="${room.idAmbiente}">${room.numero}</option>`;
  }).join("");

  roomPdf.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
  roomExcel.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
}
// loadSelectFilters(centrosAPI, "centerSelectFilter", ["detalle"]);
const sheet = localStorage.getItem('idSheet');
console.log("sheet in regs :",sheet)
let dataHistory = await getDataHistory(`${regAsistenciaAPI}.php?sheet=${sheet}`);

let pgFrom = 0;
let pgLimit = parseInt(selectPgLimit.value);
let pages = Math.ceil(dataHistory.length / pgLimit);
let pgActive = 1;

let history = dataHistory.slice(pgFrom, pgLimit);

const getHistory = async (history) => {
  tableBody.innerHTML = "";
  history.forEach((row) => {
    const docs = row.docUsuarios.split(',');
    const names = row.nombresUsuarios.split(',');
    
    // Construir el contenido para los aprendices
    const apprenticesHtml = docs.map((doc, index) => {
      const name = names[index] || '';
      return `<p><span>Documento: </span><strong>${doc}</strong> - <span>Nombre: </span><strong>${name}</strong></p>`;
    }).join('');

    tableBody.innerHTML += `
    <tr>
      <td data-title="Instructor" class="tdCol2">
        <div><img src="${row.imagen}" width="50"></div>
        <div><strong class="historyUserName">${row.instructorNombre}</strong><br><span class="historyUserDoc">C.C. ${row.instructorDocumento}</span></div>
      </td>
      <td data-title="Ambiente">${row.ambiente}</td>
      <td data-title="Fecha">${row.fecha}</td>
      <td data-title="Ficha">${row.numeroFicha}</td>
      <td data-title="Accion" class="tdBool">
        <button><i class="fa-solid fa-pen-to-square"></i></button>
        <button class="btnShowDetailsAssist" data-id-assist="${row.idAsistencia}">
          <i class="fa-solid fa-chevron-down"></i>
        </button>
      </td>
      <div class="details-cell" data-details="${row.idAsistencia}" style="display: none;">
        <div>
          <p><span>Total de aprendices: </span><strong>${row.totalAprendices}</strong></p>    
          <p><span>Asistieron: </span><strong>${row.presentes}</strong></p>
        </div>
        <div>
          <h3>Aprendices</h3>
          ${apprenticesHtml}
        </div>
      </div>
    </tr>
    `;
  });
  loadItemsPg(pages);

  document.querySelectorAll('.btnShowDetailsAssist').forEach(button => {
    button.addEventListener('click', function() {
      const idAssist = this.getAttribute('data-id-assist');
      const detailsCell = document.querySelector(`.details-cell[data-details="${idAssist}"]`);
      const icon = this.querySelector('i');

      if (detailsCell.style.display === 'none') {
        detailsCell.style.display = 'table-cell'; // Mostrar el div
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
      } else {
        detailsCell.style.display = 'none'; // Ocultar el div
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
      }
    });
  });
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
  const center = centerSelectFilter.value;
  const number = numberInputFilter.value;
  const date = dateInputFilter.value;
  if (center !== "all") {
    dataHistory = dataHistory.filter((row) => row.centro == center);
  } else {
    dataHistory = await getDataHistory(`${regAsistenciaAPI}.php?sheet=${sheet}`);
  }
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
// centerSelectFilter.addEventListener("change", filterHistory);
numberInputFilter.addEventListener("keyup", filterHistory);
dateInputFilter.addEventListener("change", filterHistory);

selectPgLimit.addEventListener("change", () => {
  pgLimit = parseInt(selectPgLimit.value);
  pagination(dataHistory);
});

ExportFormExcel(
  "regAssistExportFormExcel",
  regAsistenciaAPI,
);

ExportFormPdf(
  "regAssistExportFormPdf",
  regAsistenciaAPI,
);
