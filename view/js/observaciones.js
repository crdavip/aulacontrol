let objects = [];
const loadRenderObservations = async () => {
  const data = await getData(observacionesAPI);
  objects = data;
  console.log(data)
  console.log(objects);
  renderObservations(objects);
  // getDataCenters();
}

window.addEventListener("DOMContentLoaded", loadRenderObservations);

const updateRenderObservations = async () => {
  await loadRenderObservations();
};

const createObservationCard = (users) => {
  const fragment = document.createDocumentFragment();
  users.forEach((user) => {
    const cardUser = document.createElement("div");
    cardUser.className = "card";
    const cardUserTop = document.createElement("div");
    cardUserTop.className = "cardTop";
    const cardUserBody = document.createElement("div");
    cardUserBody.classList.add("cardBody");
    const cardUserPic = document.createElement("div");
    cardUserPic.classList.add("cardPic");
    const img = document.createElement("img");
    img.src = user.imagen;
    img.alt = "profile";
    cardUserPic.appendChild(img);
    const cardUserTxt = document.createElement("div");
    cardUserTxt.classList.add("cardBodyTxt");
    const cardUserTxtRol = document.createElement("p");
    const cardUserTxtName = document.createElement("h3");
    const cardUserTxtCargo = document.createElement("span");
    cardUserTxtName.className = "cardUserH3";
    cardUserTxtRol.textContent = user.detalle;
    cardUserTxtName.textContent = user.nombre;
    cardUserTxtCargo.textContent = user.documento;
    cardUserTxt.appendChild(cardUserTxtRol);
    cardUserTxt.appendChild(cardUserTxtName);
    cardUserTxt.appendChild(cardUserTxtCargo);
    const cardUserObservation = document.createElement("div");
    cardUserObservation.classList.add("cardBodyTxt");
    const cardUserObsTxtType = document.createElement("p");
    const cardUserObsTxtDesc = document.createElement("span");
    const cardUserObsTxtPosted = document.createElement("h4");
    cardUserObsTxtType.textContent = `Tipo de Asunto: ${user.tipoAsunto}`;
    cardUserObsTxtDesc.textContent = `Descripción: ${user.descripcion}`;
    cardUserObsTxtPosted.textContent = `Publicado el  ${user.fechaPublicacion}`;
    cardUserObservation.appendChild(cardUserObsTxtType);
    cardUserObservation.appendChild(cardUserObsTxtDesc);
    cardUserObservation.appendChild(cardUserObsTxtPosted);
    cardUserBody.appendChild(cardUserPic);
    cardUserBody.appendChild(cardUserTxt);
    cardUserBody.appendChild(cardUserObservation);
    cardUser.appendChild(cardUserTop);
    cardUser.appendChild(cardUserBody);
    fragment.appendChild(cardUser);
  });
  return fragment;
};

const row = document.querySelector(".row");
const renderObservations = async (data) => {
  if (data.length > 0) {
    const cards = createObservationCard(data);
    row.innerHTML = "";
    row.appendChild(cards);
  } else {
    row.innerHTML = "No hay resultados para mostrar.";
  }
};

sendForm(
  "observationCreateForm",
  observacionesAPI,
  "POST",
  "messageCreate",
  updateRenderObservations,
  "observationCreate",
  1500
);