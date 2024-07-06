
let objects = [];
const loadRenderObjects = async () => {
  const data = await getData(objetosAPI);
  objects = data;
  renderObjects(objects);
}

window.addEventListener("DOMContentLoaded", loadRenderObjects);

const updateRenderObjects = async () => {
  await loadRenderObjects();
};

const createObjectCard = (objects) => {
  const fragment = document.createDocumentFragment();
  objects.forEach((object) => {
    const cardObject = document.createElement("div");
    cardObject.classList.add("card");
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
          inputs: ["objectIdEdit", "objectDescriptionEdit", "objectColorEdit"],
          inputsValue: [object.idObjeto, object.descripcion, object.color],
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
    const cardBodyTxt = document.createElement("div");
    cardBodyTxt.classList.add("cardBodyTxt");
    cardBodyTxt.innerHTML = `<p>${object.descripcion}</p>
                            <h4>${object.color}</h4>
                            <h4>${object.estado}</h4>
                            <h4>Usuario: ${object.documento}</h4>`;
    cardBody.appendChild(cardObjectNum);
    cardBody.appendChild(cardBodyTxt);
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
  "createDevice",
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