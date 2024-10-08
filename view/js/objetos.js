const selectCenterObjcet = document.getElementById("centerObject");

let centersList;
const getDataCenters = async () => {
  const dataCentros = await getData(centrosAPI);
  centersList = dataCentros.filter((center) => center.detalle !== 'Porteria');

  let contentSelectTagCenters = centersList.map((center) => {
    return `<option value="${center.idCentro}">${center.detalle}</option>`;
  }).join("");
  selectCenterObjcet.innerHTML = `<option value="">Seleccione un Centro</option>` + contentSelectTagCenters;
}

let objects = [];
const loadRenderObjects = async () => {
  const data = await getData(objetosAPI);
  const activeObjects = data.filter((object) => object.estado === 'Activo');
  const inactiveObjects = data.filter((object) => object.estado === 'Inactivo');
  objects = activeObjects.concat(inactiveObjects);
  objects = objects.filter((object, index, self) =>
    index === self.findIndex((o) => o.idObjeto === object.idObjeto)
  );
  renderObjects(objects);
  getDataCenters();
}

window.addEventListener("DOMContentLoaded", loadRenderObjects);

const updateRenderObjects = async () => {
  await loadRenderObjects();
};

function activeCover(card) {
  card.classList.add('activeCover');
}

function inactiveCover(card) {
  card.classList.remove('activeCover');
}

const createObjectCard = (objects) => {
  const fragment = document.createDocumentFragment();
  objects.forEach((object) => {
    const cardObject = document.createElement("div");
    cardObject.classList.add("card", "relative");

    const bkDarkCoverNotClick = document.createElement("div");
    bkDarkCoverNotClick.classList.add("bkDarkCoverNotClick");
    cardObject.appendChild(bkDarkCoverNotClick);

    if (object.estado === 'Inactivo') {
      activeCover(cardObject);
    } else {
      inactiveCover(cardObject);
    }
    const cardBody = document.createElement("div");
    cardBody.classList.add("cardBody", "cardBodyRoom");
    if (userRolView == 1) {
      const cardObjectMenu = document.createElement("a");
      cardObjectMenu.classList.add("cardRoomMenu");
      cardObjectMenu.innerHTML = '<i class="fa-solid fa-ellipsis"></i>';
      const cardObjectMenuItems = document.createElement("div");
      cardObjectMenuItems.classList.add("cardRoomMenuItems");
      const btnEdit = document.createElement("a");
      btnEdit.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>Editar';
      const btnDelete = document.createElement("a");
      btnDelete.innerHTML = '<i class="fa-solid fa-trash"></i>Eliminar';
      cardObjectMenuItems.appendChild(btnEdit);
      cardObjectMenuItems.appendChild(btnDelete);
      cardBody.appendChild(cardObjectMenuItems);
      cardBody.appendChild(cardObjectMenu);
      dropDown(cardObjectMenu, cardObjectMenuItems);

      btnEdit.addEventListener("click", () => {

        loadDataForm({
          inputs: ["objectIdEdit", "objectDescriptionEdit", "objectColorEdit", "userObjectEdit"],
          inputsValue: [object.idObjeto, object.descripcion, object.color, object.documento],
          modal: "editObject",
        });
      });

      btnDelete.addEventListener("click", () => {
        loadDataForm({
          inputs: ["objectIdDelete"],
          inputsValue: [object.idObjeto],
          modal: "deleteObject",
        });
      });
    }
    const cardObjectNum = document.createElement("div");
    cardObjectNum.classList.add("cardObjectIcon");
    cardObjectNum.innerHTML = `<i class="fa-solid fa-cube"></i>`;
    const btnExitMark = document.createElement("a");
    btnExitMark.classList.add("btnExitMarkObject");
    if (object.estado === 'Inactivo') {
      btnExitMark.classList.add("btnEntranceMarkPosition");
      btnExitMark.innerHTML = `<i class="fa-solid fa-check"></i>Entrada`;
    } else {
      btnExitMark.classList.add("btnExitMarkPosition");
      btnExitMark.innerHTML = `<i class="fa-solid fa-check"></i>Salida`;
    }
    btnExitMark.addEventListener("click", () => {
      loadDataForm({
        inputs: object.estado === 'Inactivo' ? ["objectIdEntranceMark", "objectIdEntranceCenter"] : ["objectIdExitMark", "objectIdUser"],
        inputsValue: object.estado === 'Inactivo' ? [object.idObjeto, object.idUsuario] : [object.idObjeto, object.idCentro],
        modal: object.estado === 'Inactivo' ? "entranceObjectMark" : "exitObjectMark",
      });
    })
    const cardBodyTxt = document.createElement("div");
    cardBodyTxt.classList.add("cardBodyTxt");
    cardBodyTxt.innerHTML = `<p>${object.descripcion}</p>
                            <h4>${object.color}</h4>
                            <h4>${object.estado}</h4>
                            <h4>Usuario: ${object.documento}</h4>`;
    cardBody.appendChild(cardObjectNum);
    cardBody.appendChild(cardBodyTxt);
    cardBody.appendChild(btnExitMark);
    cardObject.appendChild(cardBody);
    fragment.appendChild(cardObject);
  });
  return fragment;
};

const row = document.querySelector(".row");
const renderObjects = async (data) => {
  if (data.length > 0) {
    const cards = createObjectCard(data);
    row.innerHTML = "";
    row.appendChild(cards);
  } else {
    row.innerHTML = "No hay resultados para mostrar.";
  }
};

sendForm(
  "createObjectForm",
  objetosAPI,
  "POST",
  "messageCreate",
  updateRenderObjects,
  "createObject",
  1500
);

sendForm(
  "objectEditForm",
  objetosAPI,
  "PUT",
  "messageEdit",
  updateRenderObjects,
  "editObject",
  1500
);

sendForm(
  "objectDeleteForm",
  objetosAPI,
  "DELETE",
  "messageDelete",
  updateRenderObjects,
  "deleteObject",
  1500
);

// sendForm(
//   "objectExitMark",
//   regObjetosAPI,
//   "PUT",
//   "messageExitMark",
//   updateRenderObjects,
//   "exitObjectMark",
//   1500
// );

// sendForm(
//   "objectEntranceMark",
//   regObjetosAPI,
//   "POST",
//   "messageEntranceMark",
//   updateRenderObjects,
//   "entranceObjectMark",
//   1500
// );

document.getElementById('btnExportPdfObjects').addEventListener('click', () => {
  exportToPdf(objetosAPI);
});

document.getElementById('btnExportExcelObjects').addEventListener('click', () => {
  exportToExcel(objetosAPI);
});