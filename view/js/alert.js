const navAlert = document.getElementById("navAlert");
const showAlertsBtn = document.getElementById("showAlertsBtn");
const deleteAlertsBtn = document.getElementById("deleteAlertsBtn");
const alertAPI = "./controller/alert.php";

//Abrir modal de alertas
navAlert.addEventListener("click", () => {
  openModal("alertModal");
  getAlert();
});

//Contador de alertas
function countAlert() {
  fetch(`${alertAPI}?tly`)
    .then((response) => response.json())
    .then((data) => {
      if (data.alertCount > 0) {
        alertBtn = document.createElement("div");
        alertBtn.classList.add("alertBtn");
        alertBtn.innerHTML = data.alertCount;
        navAlert.appendChild(alertBtn);
      } else if (document.querySelector(".alertBtn")) {
        navAlert.removeChild(alertBtn);
      }
      // setTimeout(() => {
      //     countAlert();
      //   }, 2000);
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

countAlert();

//Ver Alertas sin leer
const alertBoxTitle = document.getElementById("alertBoxTitle");
const alertBox = document.querySelector(".alertBox");
function getAlert() {
  showAlertsBtn.disabled = false;
  deleteAlertsBtn.disabled = true;
  fetch(alertAPI)
    .then((response) => response.json())
    .then((data) => {
      alertBox.innerHTML = "";
      if (data.alertCount > 0) {
        data.alertRow.forEach((alert) => {
          alertBoxTitle.innerHTML = `Tienes ${data.alertCount} alertas nuevas`;
          const alertBoxItem = document.createElement("a");
          alertBoxItem.classList.add("alertBoxItem");
          alertBox.appendChild(alertBoxItem);
          alertBoxItem.href = `${alert.LINK}`;
          alertBoxItem.addEventListener("click", () => {readAlert(alert.ALERTA_ID)});
          const alertBoxItemTitle = document.createElement("div");
          alertBoxItemTitle.classList.add("alertBoxItemTitle");
          alertBoxItem.appendChild(alertBoxItemTitle);
          alertBoxItemTitle.innerHTML = `<i class="${alert.ICONO}"></i>`;
          alertBoxItemTitle.innerHTML += `<p><strong>${alert.NOMBRE}</strong> ${alert.DESCRIPCION}</p>`;
          const dateAlert = getDateAlert(alert.FECHA);
          const span = document.createElement("span");
          span.innerHTML = `${dateAlert}`;
          alertBoxItem.appendChild(span);
        });
      } else {
        alertBoxTitle.innerHTML = "No tienes nuevas alertas";
        alertBox.innerHTML = "Parece que no hay actividad reciente.";
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

// Calcular tiempo de alerta
function getDateAlert(date) {
  const dateNow = new Date();
  const dateAlert = new Date(date);

  const dateDif = dateNow - dateAlert;

  const min = Math.floor(dateDif / 60000);
  const hou = Math.floor(min / 60);
  const day = Math.floor(hou / 24);

  if (day > 0) {
    return `${day}d`;
  } else if (hou > 0) {
    return `${hou}h`;
  } else {
    return `${min}m`;
  }
}

//Leer alertas
function readAlert(alert) {
  const alertId = alert;
  const jsonData = JSON.stringify({alertId: alertId});
  fetch(alertAPI, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: jsonData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.alertKey == true) {
        countAlert();
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

//Ver todas las alertas
showAlertsBtn.addEventListener("click", () => {
  showAlertsBtn.disabled = true;
  fetch(`${alertAPI}?all`)
    .then((response) => response.json())
    .then((data) => {
      if (data.alertCount > 0) {
        deleteAlertsBtn.disabled = false;
        data.alertRow.forEach((alert) => {
          alertBoxTitle.innerHTML = `Tienes ${data.alertCount} alertas en total`;
          const alertBoxItem = document.createElement("a");
          alertBoxItem.classList.add("alertBoxItem");
          alertBoxItem.classList.add("alertBoxItemOK");
          alertBox.appendChild(alertBoxItem);
          alertBoxItem.href = `${alert.LINK}`;
          const alertBoxItemTitle = document.createElement("div");
          alertBoxItemTitle.classList.add("alertBoxItemTitle");
          alertBoxItem.appendChild(alertBoxItemTitle);
          alertBoxItemTitle.innerHTML = `<i class="${alert.ICONO}"></i>`;
          alertBoxItemTitle.innerHTML += `<p><strong>${alert.NOMBRE}</strong> ${alert.DESCRIPCION}</p>`;
          const dateAlert = getDateAlert(alert.FECHA);
          const span = document.createElement("span");
          span.innerHTML = `${dateAlert}`;
          alertBoxItem.appendChild(span);
        });
      } else {
        alertBoxTitle.innerHTML = "Tienes 0 alertas en total";
        alertBox.innerHTML = "No hay alertas guardadas.";
        deleteAlertsBtn.disabled = true;
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
});

//Borrar todas las alertas
deleteAlertsBtn.addEventListener("click", () => {
  deleteAlertsBtn.disabled = true;
  fetch(alertAPI, {
    method: "DELETE",
  })
    .then((response) => response.json())
    .then((data) => {})
    .catch((error) => {
      console.error("Error:", error);
    });
});
