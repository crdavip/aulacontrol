const numberInputFilter = document.getElementById("numberInputFilter");
const centerSelectFilter = document.getElementById("centerSelectFilter");

loadSelectFilters(centrosAPI, "centerSelectFilter", ["siglas"]);

let dataSheets = [];
const loadDataSheets = async () => {
    const data = await getData(fichasAPI);
    dataSheets = data;
    renderDataSheets(dataSheets);
}

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
            btnTrainees.innerHTML = '<i class="fa-solid fa-user-graduate"></i>Aprendices';
            const btnEdit = document.createElement("a");
            btnEdit.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Editar';
            const btnDelete = document.createElement("a");
            btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
            cardMenuItems.appendChild(btnTrainees);
            cardMenuItems.appendChild(btnEdit);
            cardMenuItems.appendChild(btnDelete);
            cardTop.appendChild(cardMenuItems);
            cardTop.appendChild(cardMenu);
            dropDown(cardMenu, cardMenuItems);          
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
                                <span>${sheet.aprendices} ${sheet.aprendices == 1 ? "Aprendiz" : "Aprendices"}</span>`;
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
}

const filterDataSheets = () => {
    const center = centerSelectFilter.value;
    const number = numberInputFilter.value;
    let newDataSheets = dataSheets;
    if (center !== "all") {
        newDataSheets = newDataSheets.filter((dataSheet) => dataSheet.centro == center);
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