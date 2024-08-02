import { getDataHistory } from "../js/fetch.js";

// const dateInputFilter = document.getElementById("dateInputFilter");
const docInputFilter = document.getElementById("docInputFilter");
const selectPgLimit = document.getElementById("selectPgLimit");
const pgNextBtn = document.getElementById("pgNext");
const pgPrevBtn = document.getElementById("pgPrev");
const tableBody = document.getElementById("tableBody");

let dataHistory = await getDataHistory(regObservacionesAPI);
let pgFrom = 0;
let pgLimit = parseInt(selectPgLimit.value);
let pages = Math.ceil(dataHistory.length / pgLimit);
let pgActive = 1;

let history = dataHistory.slice(pgFrom, pgLimit);

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

let rowsForInput;

const getHistory = async (history) => {
  let filteredHistory = history.sort((a, b) => {
      return a.estado === b.estado ? 0 : a.estado === 0 ? -1 : 1;
    });
  tableBody.innerHTML = "";
  rowsForInput = filteredHistory;
  rowsForInput.forEach((row) => {
    const postDate = formatDate(row.fechaPublicacion);
    const checkedDate = formatDate(row.fechaRevision);
    tableBody.innerHTML += `
    <tr>
      <td data-title="Usuario" class="tdCol2"><div><img src="${row.imgUsuario}" width="50"></div><div><p class="historyUserName">${row.nombreUsuario}</p><br><span class="historyUserDoc">C.C. ${row.docuUsuario}</span></div></td>
      <td data-title="Fecha Publicacion"><strong>${postDate.dateFormat}</strong></td>
      <td data-title="Descripcion"><div class="tdRow2">
      <div>
      <span class="historyRoomNum">Asunto: </span>
      <span>${row.tipoAsunto}</span>
      </div>
      <br>
      <strong class="historyRoomNum">Descripción: </strong>
      <p>${row.descripcion}</p></div></td>
      
      ${row.fechaRevision === null
          ? `<td data-title="Fecha Revision"><span class="tdStatus">Pendiente</span></td>`
          : `<td data-title="Fecha Revision"><strong>${checkedDate.dateFormat}</strong></td>`}

      ${row.estado === 0
        ? `<td data-title="Revisado Por"><span class="tdStatus">Pendiente</span></td>`
        : `<td data-title="Revisado Por" class="tdCol2"><div><p>${row.nombreRevisor}</p><span class="historyUserDoc">C.C. ${row.docRevisor}</span></div></td>`}

      ${row.estado === 0
        ? `<td data-title="Estado"><span class="tdStatus">Pendiente</span></td>`
        : `<td data-title="Estado"><span>Revisado</span></td>`
      }
    </tr>
    `;
  });
  loadItemsPg(pages);
};

const renderHistory = (history) => {
  if (history.length > 0) {
    tableBody.innerHTML = "";
    getHistory(history);
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

const filterUsers = () => {
  const doc = docInputFilter.value;
  let newRows = rowsForInput;
  if (doc !== "") {
    newRows = newRows.filter((row) =>
      `${row.docuUsuario}`.includes(`${doc}`)
    );

    renderHistory(newRows);
  } else {
    renderHistory(history);
  }
};

docInputFilter.addEventListener("keyup", filterUsers);

selectPgLimit.addEventListener("change", () => {
  pgLimit = parseInt(selectPgLimit.value);
  pagination(dataHistory);
});