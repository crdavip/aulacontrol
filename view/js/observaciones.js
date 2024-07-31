let objects = [];
const loadRenderObservations = async () => {
  const data = await getData(observacionesAPI);
  objects = data;
  console.log(data)
  console.log(objects);
  renderObservations(objects);
}

window.addEventListener("DOMContentLoaded", loadRenderObservations);

const updateRenderObservations = async () => {
  await loadRenderObservations();
};

const userRolView = document.getElementById("userRolView")

const createObservationCard = (observations) => {
  const fragment = document.createDocumentFragment();
  let filteredObs
  if(userRolView.value == 3 || userRolView.value == 4){
    filteredObs = observations.sort((a, b) => {
      return a.estado === b.estado ? 0 : a.estado === 0 ? -1 : 1;
    });
  } else {
    filteredObs = observations.filter((obs) => obs.estado == 0);
  }
  console.log("filteredObs ", filteredObs)
  filteredObs.forEach((obs) => {
    const cardUser = document.createElement("div");
    cardUser.className = "card";
    const cardUserTop = document.createElement("div");
    cardUserTop.className = "cardTop";
    const cardUserBody = document.createElement("div");
    cardUserBody.classList.add("cardBody");
    const cardUserPic = document.createElement("div");
    cardUserPic.classList.add("cardPic");
    const img = document.createElement("img");
    img.src = obs.imagen;
    img.alt = "profile";
    cardUserPic.appendChild(img);
    const cardUserTxt = document.createElement("div");
    cardUserTxt.classList.add("cardBodyTxt");
    const cardUserTxtRol = document.createElement("p");
    const cardUserTxtName = document.createElement("h3");
    const cardUserTxtCargo = document.createElement("span");
    cardUserTxtCargo.classList.add("docCustomCardObs");
    cardUserTxtName.className = "cardUserH3";
    cardUserTxtRol.textContent = obs.detalle;
    cardUserTxtName.textContent = obs.nombre;
    cardUserTxtCargo.textContent = `C.C. ${obs.documento}`;
    cardUserTxt.appendChild(cardUserTxtRol);
    cardUserTxt.appendChild(cardUserTxtName);
    cardUserTxt.appendChild(cardUserTxtCargo);
    const cardUserObservation = document.createElement("div");
    cardUserObservation.classList.add("cardBodyTxt");
    const cardUserObsTxtType = document.createElement("p");
    const cardUserObsTxtDesc = document.createElement("span");
    cardUserObsTxtDesc.classList.add("descCardBody");
    const cardUserObsTxtPosted = document.createElement("h5");
    cardUserObsTxtType.textContent = `Tipo de Asunto: ${obs.tipoAsunto}`;
    cardUserObsTxtDesc.textContent = `Descripción: ${obs.descripcion}`;
    cardUserObsTxtPosted.textContent = `Publicado el  ${obs.fechaPublicacion}`;
    cardUserObservation.appendChild(cardUserObsTxtType);
    cardUserObservation.appendChild(cardUserObsTxtDesc);
    cardUserObservation.appendChild(cardUserObsTxtPosted);
    // Btn Revision
    const cardUserObservationDivBtn = document.createElement("div");
    cardUserObservationDivBtn.classList.add("cardBodyTxt");
    if (userRolView.value == 3 || userRolView == 4) {
      if(obs.estado === 0){
        cardUserObservationDivBtn.innerHTML = `
          <p><i class="fa-regular fa-circle"></i>  Por revisar</p>
        `;
      } else {
        cardUserObservationDivBtn.innerHTML = `
          <p><i class="fa-regular fa-circle-check"></i>  Revisado</p>
        `;
      }
    } else {
      cardUserObservationDivBtn.innerHTML = `
        <a class="btnExitMarkObject" id="btnMarkChecked" onclick="openModal('markObsChecked')" data-id-observation="${obs.idObservacion}"><i class="fa-regular fa-circle-check"></i> Marcar como revisado</a>
      `;
    }

    cardUserBody.appendChild(cardUserPic);
    cardUserBody.appendChild(cardUserTxt);
    cardUserBody.appendChild(cardUserObservation);
    cardUserBody.appendChild(cardUserObservationDivBtn);
    cardUser.appendChild(cardUserTop);
    cardUser.appendChild(cardUserBody);
    fragment.appendChild(cardUser);
  });
  return fragment;
};

const observationId = document.getElementById("observationId");
const initializeEventListeners = () => {
  document.querySelectorAll('.btnExitMarkObject').forEach(button => {
    button.addEventListener('click', (event) => {
      const observationId = event.currentTarget.getAttribute('data-id-observation');
      document.getElementById('observationId').value = observationId;
      openModal('markObsChecked');
    });
  });
};

const row = document.querySelector(".row");
const renderObservations = async (data) => {
  if (data.length > 0) {
    const cards = createObservationCard(data);
    row.innerHTML = "";
    row.appendChild(cards);
    initializeEventListeners();
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

sendForm(
  "markObsCheckedForm",
  regObservacionesAPI,
  "PUT",
  "messageObsChecked",
  updateRenderObservations,
  "markObsChecked",
  1500
);