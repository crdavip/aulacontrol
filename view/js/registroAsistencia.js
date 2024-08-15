let dataHistory;
let pgFrom = 0;
const selectPgLimit = document.getElementById("selectPgLimit");
let pages;
let history;

const pagination = (data) => {
  pgLimit = parseInt(selectPgLimit.value);
  pages = Math.ceil(data.length / pgLimit);
  pgActive = 1;

  history = data.slice(pgFrom, pgLimit);
  loadItemsPg(pages);
  pgShow(0);
  renderHistory(history);
}

selectPgLimit.addEventListener("change", () => {
  pgLimit = parseInt(selectPgLimit.value);
  pagination(dataHistory);
});
pgLimit = parseInt(selectPgLimit.value);


const getDataHistory = async (API) => {
  const res = await fetch(`${API}`);
  const data = await res.json();
  return data;
}

const sheet = localStorage.getItem('idSheet');
const idSheetExport = document.getElementById("idSheetExport");
idSheetExport.value = sheet;

const initializeDataHistory = async () => {
  dataHistory = await getDataHistory(`${regAsistenciaAPI}.php?sheet=${sheet}`);
  pages = Math.ceil(dataHistory.length / pgLimit);
  history = await dataHistory.slice(pgFrom, pgLimit);

  pagination(dataHistory);
}

initializeDataHistory();

const numberInputFilter = document.getElementById("numberInputFilter");
const dateInputFilter = document.getElementById("dateInputFilter");
const pgNextBtn = document.getElementById("pgNext");
const pgPrevBtn = document.getElementById("pgPrev");
// const tableBody = document.getElementById("fullContainerResults");
const tableBody = document.getElementById("tableBody");
// const roomPdf = document.getElementById("selectedRoomPdf");
// const roomExcel = document.getElementById("selectedRoomExcel");
const roomUpdate = document.getElementById("selectedRoom");
const searchAssistanceTraineesInput = document.getElementById("traineesAssistanceSearch");
const resultsAssistanceSearchDiv = document.getElementById("resultsTraineesAssistanceSearch");
let allTraineesAssist = [];
let logOfTraineesPerSheet = [];
let filteredTraineesAssist = [];
let selectedTraineesAssist = new Set();
let idSheetAssistance;

const loadTraineesSheet = async (sheet) => {
  try {
    const responseAssist = await fetch(`${aprendicesAPI}.php?paramSheet=${sheet}`);
    const traineesAssist = await responseAssist.json();
    return traineesAssist;
  } catch (error) {
    console.error('Error al cargar los aprendices:', error);
  }
};

const getDataAmbs = async () => {
  const dataAmbientes = await getData(ambientesAPI);
  let roomsList = await dataAmbientes;
  roomsList = roomsList.filter(roomItem => roomItem.centro === "CDMC" && roomItem.numero !== "Mesa Ayuda");
  let contentSelectTagRooms = roomsList.map((room) => {
    return `<option value="${room.idAmbiente}">${room.numero}</option>`;
  }).join("");

  roomUpdate.innerHTML = `<option value="">Seleccione un Ambiente</option>` + contentSelectTagRooms;
}

const getHistory = async (history) => {
  tableBody.innerHTML = "";
  history.forEach((row) => {
    const docs = row.docUsuarios.split(',').map(doc => doc.trim());
    const names = row.nombresUsuarios.split(',');
    let missingTrainees = [];
    logOfTraineesPerSheet[0].forEach((trainee) => {
      const traineeInDocs = docs.includes(trainee.documento.toString());
      if (!traineeInDocs) {
        missingTrainees.push(trainee);
      }
    });

    const apprenticesHtml = missingTrainees.map((trainee) => {
      return `<p><span>Documento: </span><strong>${trainee.documento}</strong> - <span>Nombre: </span><strong>${trainee.nombre}</strong></p>`;
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
        <button class="btnShowDetailsAssist paginationBtn" data-id-assist="${row.idAsistencia}">
          <i class="fa-solid fa-chevron-down"></i>
        </button>
      </td>
      <div class="details-cell" data-details="${row.idAsistencia}" style="display: none;">
        <div>
          <p><span>Total de aprendices: </span><strong>${row.totalAprendices}</strong></p>    
          <p><span>Asistieron: </span><strong>${row.presentes}</strong></p>
        </div>
        ${logOfTraineesPerSheet[0].length == docs.length
        ? `<div></div>`
        : `<div>
          <h4>No asistieron a formación</h4>
          ${apprenticesHtml}
          </div>`
      }
      </div>
    </tr>
    `;
    const regAssistIdEdit = document.getElementById("regAssistIdEdit");
    const dateRegAssistEdit = document.getElementById("date");
    document.querySelectorAll('.btnEditRegAssist').forEach(button => {
      button.addEventListener('click', function () {
        idSheetAssistance = row.idFicha;
        const idAssist = this.getAttribute('data-id-assist');
        loadAllTrainees();
        openModal("regAssistEdit");
        regAssistIdEdit.value = idAssist;
        dateRegAssistEdit.value = row.fecha;
      });
    });
  });
  loadItemsPg(pages);

  document.querySelectorAll('.btnShowDetailsAssist').forEach(button => {
    button.addEventListener('click', function () {
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

const renderHistory = async (history) => {
  if (history.length > 0) {
    tableBody.innerHTML = "";
    const restoredTrainees = await loadTraineesSheet(history[0].idFicha);
    if (restoredTrainees && restoredTrainees.length > 0 && restoredTrainees[0].idFicha) {
      logOfTraineesPerSheet.push(restoredTrainees);
    } else {
      tableBody.innerHTML = "Parece que no existen aprendices en la ficha...";
    }
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

//Filtrar Registros
const filterHistory = async () => {
  const number = numberInputFilter.value;
  const date = dateInputFilter.value;
  let newDataHistory = dataHistory;
  if (number !== "") {
    newDataHistory = newDataHistory.filter((row) =>
      `${row.ambiente.toLowerCase()}`.includes(`${number.toLowerCase()}`)
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

ExportFormExcel(
  "regAssistExportFormExcel",
  regAsistenciaAPI,
);

sendForm(
  "regAssistEditForm",
  regAsistenciaAPI,
  "PUT",
  "messageEdit",
  updateDataHistory,
  "regAssistEdit",
  1500
);
