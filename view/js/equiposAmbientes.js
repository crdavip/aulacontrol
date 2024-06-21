import { getDataHistory } from "../js/fetch.js";

const numberInputFilter = document.getElementById("numberInputFilter");
const dateInputFilter = document.getElementById("dateInputFilter");
const selectPgLimit = document.getElementById("selectPgLimit");
const pgNextBtn = document.getElementById("pgNext");
const pgPrevBtn = document.getElementById("pgPrev");
const tableBody = document.getElementById("tableBody");

loadSelectFilters(centrosAPI, "centerSelectFilter", ["detalle"]);

let dataHistory = await getDataHistory(equiposAmbientesAPI);
console.log(dataHistory);
let pgFrom = 0;
let pgLimit = parseInt(selectPgLimit.value);
let pages = Math.ceil(dataHistory.length / pgLimit);
let pgActive = 1;

let history = dataHistory.slice(pgFrom, pgLimit);

const getHistory = async (history) => {
  tableBody.innerHTML = "";
  history.forEach((row) => {
    tableBody.innerHTML += `
    <tr>
      <td data-title="Referncia"><span>${row.ref}</span></td>
      <td data-title="Referncia"><span>${row.marca}</span></td>
      <td data-title="Referncia"><span>${row.estado}</span></td>
      <td data-title="Referncia"><span>${row.ambiente}</span></td>
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

//Filtrar Registros
const filterHistory = async () => {
  const center = centerSelectFilter.value;
  const number = numberInputFilter.value;
  const date = dateInputFilter.value;
  if (center !== "all") {
    dataHistory = dataHistory.filter((row) => row.centro == center);
  } else {
    dataHistory = await getDataHistory(regAmbientesAPI);
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
centerSelectFilter.addEventListener("change", filterHistory);
numberInputFilter.addEventListener("keyup", filterHistory);
dateInputFilter.addEventListener("change", filterHistory);

selectPgLimit.addEventListener("change", () => {
  pgLimit = parseInt(selectPgLimit.value);
  pagination(dataHistory);
});