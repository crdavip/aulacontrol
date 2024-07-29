//Inicio Modal
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.classList.add("showModal");
  document.querySelector("body").style.setProperty("overflow-y", "hidden");
}
function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.classList.remove("showModal");
  document.querySelector("body").style.setProperty("overflow-y", "auto");
}
const closeModals = document.querySelectorAll(".closeModal");
closeModals.forEach((closeModalButton) => {
  closeModalButton.addEventListener("click", () => {
    const modalId = closeModalButton.closest(".modal").id;
    closeModal(modalId);
  });
});
//Fin Modal

//Inicio SideNav
if (document.querySelector(".toggle")) {
  const body = document.querySelector("body"),
    sidenav = body.querySelector(".sidenav"),
    header = body.querySelector(".header"),
    toggle = body.querySelector(".toggle");
  toggle.addEventListener("click", () => {
    sidenav.classList.toggle("close");
    header.classList.toggle("close");
    if (localStorage.getItem("navOpen") == "true") {
      localStorage.setItem("navOpen", "false");
    } else {
      localStorage.setItem("navOpen", "true");
    }
  });
  if (localStorage.getItem("navOpen") == "true") {
    sidenav.classList.remove("close");
    header.classList.remove("close");
  } else {
    sidenav.classList.add("close");
    header.classList.add("close");
  }
}
//Fin SideNav

//Inicio MenuActive
if (document.querySelectorAll(".menuLinks a")) {
  document.addEventListener("DOMContentLoaded", function () {
    let currentPageURL = window.location.href;
    let menuLinks = document.querySelectorAll(".menuLinks a");
    menuLinks.forEach(function (link) {
      if (link.href === currentPageURL) {
        link.classList.add("active");
      }
    });
  });
}
//Fin MenuActive

//Inicio DarkMode
const darkmodeBtn = document.querySelector("#darkmodeBtn");
const root = document.documentElement;
const darkmodeTxt = document.querySelector("#darkmodeTxt");
function applyDarkMode() {
  if (darkmodeBtn.checked) {
    root.style.setProperty("--primario", "#5eb319");
    root.style.setProperty("--segundario", "#1983B3");
    root.style.setProperty("--terciario", "#425E2C");
    root.style.setProperty("--gris-claro", "#222222");
    root.style.setProperty("--gris-oscuro", "#f2f2f2");
    root.style.setProperty("--negro", "#f4f4f4");
    root.style.setProperty("--blanco", "#17181c");
    root.style.setProperty("--color-error", "#B33F19");
    root.style.setProperty("--modal-bg", "rgba(23, 24, 28, 0.9)");
    root.style.setProperty(
      "--blanco-svg",
      "invert(6%) sepia(8%) saturate(1186%) hue-rotate(190deg) brightness(95%) contrast(92%)"
    );
    darkmodeTxt.textContent = "Modo Oscuro";
  } else {
    root.style.setProperty("--primario", "#5eb319");
    root.style.setProperty("--segundario", "#1983B3");
    root.style.setProperty("--terciario", "#425E2C");
    root.style.setProperty("--gris-claro", "#f2f2f2");
    root.style.setProperty("--gris-oscuro", "#222222");
    root.style.setProperty("--negro", "#17181c");
    root.style.setProperty("--blanco", "#ffffff");
    root.style.setProperty("--color-error", "#B33F19");
    root.style.setProperty("--modal-bg", "rgba(255, 255, 255, 0.9)");
    root.style.setProperty(
      "--primario-svg",
      "invert(65%) sepia(15%) saturate(7356%) hue-rotate(53deg) brightness(96%) contrast(80%)"
    );
    root.style.setProperty(
      "--blanco-svg",
      "invert(100%) sepia(100%) saturate(0%) hue-rotate(288deg) brightness(102%) contrast(102%)"
    );
    darkmodeTxt.textContent = "Modo Claro";
  }
}
window.addEventListener("DOMContentLoaded", () => {
  const darkmodeEnabled = localStorage.getItem("darkmodeEnabled") === "true";
  darkmodeBtn.checked = darkmodeEnabled;
  applyDarkMode();
});
darkmodeBtn.addEventListener("change", () => {
  localStorage.setItem("darkmodeEnabled", darkmodeBtn.checked);
  applyDarkMode();
});
//Fin DarkMode

//Inicio DropDown
if (document.querySelector("#userImage")) {
  const dropBtn = document.querySelector("#userImage");
  const dropItems = document.querySelector("#userItems");
  dropDown(dropBtn, dropItems);
}

function dropDown(btn, items) {
  btn.addEventListener("click", () => {
    items.classList.toggle("showDropend");
  });
  document.addEventListener("click", (event) => {
    if (!btn.contains(event.target) && !items.contains(event.target)) {
      items.classList.remove("showDropend");
    }
  });
  items.addEventListener("click", (event) => {
    event.stopPropagation();
  });
  items.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", (event) => {
      event.stopPropagation();
      items.classList.remove("showDropend");
    });
  });
}
//Fin DropDown

//Inicio MenuResponsive
if (document.querySelector(".toggleMobile")) {
  const container_pg = document.querySelector("#containerPg");
  const open_nav = document.querySelector(".toggleMobile");
  const sidenav = document.querySelector(".sidenav");
  open_nav.addEventListener("click", () => {
    sidenav.classList.toggle("show");
    sidenav.classList.remove("close");
    container_pg.classList.add("show");
  });
}
//Fin MenuResponsive

//Inicio BtnScroll
if (document.querySelector(".scrollBtn")) {
  window.addEventListener("scroll", function () {
    const scroll_btn = document.querySelector(".scrollBtn");
    const offset = window.scrollY;
    const viewportHeight = window.innerHeight;
    scroll_btn.addEventListener("click", () => {
      window.scrollTo(0, 0);
    });
    if (offset > viewportHeight - 300) {
      scroll_btn.classList.add("scrollBtnOn");
    } else {
      scroll_btn.classList.remove("scrollBtnOn");
    }
  });
}
//Fin BtnScroll

//Inicio Saludo
if (document.getElementById("userWelcome")) {
  let hour = new Date().getHours();
  let userWelcome = document.getElementById("userWelcome");
  if (hour >= 6 && hour < 12) {
    userWelcome.textContent = "¡Buenos Días!";
  } else if (hour >= 12 && hour < 18) {
    userWelcome.textContent = "¡Buenas Tardes!";
  } else {
    userWelcome.textContent = "¡Buenas Noches!";
  }
}
//Fin Saludo

//Inicio ShowPass
function showPass(inputId, button) {
  const passInput = document.getElementById(inputId);
  if (passInput.type === "password") {
    passInput.type = "text";
    button.querySelector("i").classList.remove("fa-eye");
    button.querySelector("i").classList.add("fa-eye-slash");
  } else {
    passInput.type = "password";
    button.querySelector("i").classList.remove("fa-eye-slash");
    button.querySelector("i").classList.add("fa-eye");
  }
}
//Fin ShowPass

//Inicio UserImgPreview
if (document.getElementById("userImgUpload")) {
  const userImgUpload = document.getElementById("userImgUpload");
  const userImgProfile = document.getElementById("userImgProfile");
  const userImgPic = document.getElementById("userImgPic");
  userImgUpload.addEventListener("click", () => {
    userImgProfile.click();
  });
  userImgProfile.addEventListener("change", (e) => {
    if (e.target.files[0]) {
      const readerUser = new FileReader();
      readerUser.onload = function (e) {
        userImgPic.src = e.target.result;
      };
      readerUser.readAsDataURL(e.target.files[0]);
    } else {
      userImgPic.src = userImgView;
    }
  });
}
//Fin UserImgPreview

//Inicio ShowMessageForm
function showMessage(messageId, messageClass, message, modal, time) {
  document.getElementById(messageId).innerHTML = message;
  document.getElementById(messageId).classList.add(messageClass);
  setTimeout(() => {
    document.getElementById(messageId).innerHTML = "";
    document.getElementById(messageId).classList.remove(messageClass);
    if (modal !== "") {
      closeModal(modal);
    }
  }, time);
}
//Fin ShowMessageForm

//Inicio LoadDataForm
const loadDataForm = ({ inputs, inputsValue, modal }) => {
  inputs.forEach((input, index) => {
    const inputId = document.getElementById(input);
    inputId.value = inputsValue[index];
  });
  openModal(modal);
};
//Fin LoadDataForm

//Inicio SendForm
const sendForm = async (formId, urlAPI, type, messageId, getFunc, modal) => {
  const form = document.getElementById(formId);
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const jsonData = JSON.stringify(Object.fromEntries(formData));
    const res = await fetch(urlAPI, {
      method: type,
      headers: {
        "Content-Type": "application/json",
      },
      body: jsonData,
    });
    const data = await res.json();
    if (data.success == true) {
      getFunc();
      form.reset();
      showMessage(messageId, "messageOK", data.message, modal, 1500);
    } else {
      showMessage(messageId, "messageErr", data.message, "", 1500);
    }
  });
};
//Fin SendForm

// Inicio ExportFormPdf
const ExportFormPdf = async (formId, urlAPI) => {
  const form = document.getElementById(formId);
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const params = new URLSearchParams(Object.fromEntries(formData)).toString();
    const url = `${urlAPI}?${params}`;
    window.open(url, '_blank');
  });
};
// Fin ExportFormPdf

// Inicio ExportFormExcel
const ExportFormExcel = async (formId, urlAPI) => {
  const form = document.getElementById(formId);
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    formData.append('format', 'excel');
    const params = new URLSearchParams(Object.fromEntries(formData)).toString();
    const url = `${urlAPI}?${params}`;

    const response = await fetch(url);
    const blob = await response.blob();

    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = 'reporte.xlsx';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });
};
// Fin ExportFormExcel

// Logica de Exportación
  // Función para exportar a PDF
  const exportToPdf = async (urlAPI) => {
    const params = new URLSearchParams({ format: 'pdf' }).toString();
    const url = `${urlAPI}?${params}`;
    window.open(url, '_blank');
  };
  
  // Función para exportar a Excel
  const exportToExcel = async (urlAPI) => {
    const params = new URLSearchParams({ format: 'excel' }).toString();
    const url = `${urlAPI}?${params}`;
  
    const response = await fetch(url);
    const blob = await response.blob();
  
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = 'reporte.xlsx';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

//Inicio APIList
const ingresoAPI = `./controller/ingreso`;
const ambientesAPI = `./controller/ambientes`;
const regAmbientesAPI = `./controller/registroAmbientes`;
const centrosAPI = `./controller/centros`;
const cargosAPI = `./controller/cargos`;
const usuariosAPI = `./controller/usuarios`;
const importUsuariosAPI = `./controller/importarUsuarios`;
const fichasAPI = `./controller/fichas`;
const equiposAPI = `./controller/equipos`;
const regEquiposAPI = `./controller/registroEquipos`;
const objetosAPI = `./controller/objetos`;
const regObjetosAPI = `./controller/registroObjetos`;
const datosAPI = `./controller/datos`
//Fin APIList

//Inicio LoadDataFilters
const loadSelectFilters = async (API, selectId, columns) => {
  const res = await fetch(`${API}?columns=${JSON.stringify(columns)}`);
  const data = await res.json();
  const select = document.getElementById(selectId);
  data.forEach((option) => {
    if (columns.length === 1) {
      const value = option[columns[0]];
      select.innerHTML += `<option value="${value}">${value}</option>`;
    } else {
      const value = option[columns[0]];
      const text = option[columns[1]];
      select.innerHTML += `<option value="${value}">${text}</option>`;
    }
  });
};
//Fin LoadDataFilters

//Inicio GetData
const getData = async (API) => {
  const res = await fetch(API);
  const data = await res.json();
  return data;
};
//Fin GetData

//Inicio RenderScanQR
const renderScanQR = (type) => {
  const containerQr = document.querySelector(".containerQr");
  containerQr.innerHTML = "";
  const wrapperQr = document.createElement("div");
  wrapperQr.classList.add("wrapperQr");
  containerQr.appendChild(wrapperQr);
  const wrapperScanQr = document.createElement("div");
  wrapperScanQr.classList.add("wrapperScanQr");
  wrapperQr.appendChild(wrapperScanQr);
  const titleScanQr = document.createElement("p");
  titleScanQr.classList.add("titleScanQr");
  (type == "user") ? titleScanQr.innerHTML = "Escanear Código QR del Usuario" : titleScanQr.innerHTML = "Escanear Código QR del Equipo";
  wrapperScanQr.appendChild(titleScanQr);
  const scanQrGif = document.createElement("img");
  scanQrGif.id = "scanQrGif";
  scanQrGif.classList.add("scanQrGif");
  scanQrGif.src =
    "https://assets-v2.lottiefiles.com/a/ec4394a2-1151-11ee-ab60-b3dd36237565/gx2MOoN1Ep.gif";
  wrapperScanQr.appendChild(scanQrGif);
  const canvasQR = document.createElement("canvas");
  canvasQR.id = "canvasQR";
  canvasQR.classList.add("canvasQR");
  canvasQR.hidden = true;
  wrapperScanQr.appendChild(canvasQR);
  const buttonGroup = document.createElement("div");
  buttonGroup.classList.add("buttonGroup");
  wrapperScanQr.appendChild(buttonGroup);
  const scanBtnOn = document.createElement("button");
  scanBtnOn.id = "scanBtnOn";
  scanBtnOn.classList.add("btn");
  scanBtnOn.innerHTML = `<i class="fa-solid fa-power-off"></i> Encender`;
  buttonGroup.appendChild(scanBtnOn);
  const scanBtnOff = document.createElement("button");
  scanBtnOff.id = "scanBtnOff";
  scanBtnOff.classList.add("btn", "btnAlt");
  scanBtnOff.innerHTML = `<i class="fa-solid fa-power-off"></i> Apagar`;
  scanBtnOff.hidden = true;
  buttonGroup.appendChild(scanBtnOff);
};
//Fin RenderScanQR

//Incio RenderSenaCard
const renderSenaCard = () => {
  const containerQr = document.querySelector(".containerQr");
  containerQr.innerHTML = "";
  const wrapperQr = document.createElement("div");
  wrapperQr.classList.add("wrapperQr");
  containerQr.appendChild(wrapperQr);
  const wrapperSenaCard = document.createElement("div");
  wrapperSenaCard.classList.add("wrapperSenaCard");
  wrapperQr.appendChild(wrapperSenaCard);
  const headerSenaCard = document.createElement("div");
  headerSenaCard.classList.add("headerSenaCard");
  wrapperSenaCard.appendChild(headerSenaCard);
  const logoSenaCard = document.createElement("img");
  logoSenaCard.classList.add("logoSenaCard");
  logoSenaCard.src = "./view/img/logoSena.png";
  headerSenaCard.appendChild(logoSenaCard);
  const picSenaCard = document.createElement("img");
  picSenaCard.classList.add("picSenaCard");
  picSenaCard.id = "picSenaCard";
  picSenaCard.src = "./view/img/users/default.jpg";
  headerSenaCard.appendChild(picSenaCard);
  const roleSenaCard = document.createElement("span");
  roleSenaCard.classList.add("roleSenaCard");
  roleSenaCard.id = "roleSenaCard";
  headerSenaCard.appendChild(roleSenaCard);
  const bodySenaCard = document.createElement("div");
  bodySenaCard.classList.add("bodySenaCard");
  wrapperSenaCard.appendChild(bodySenaCard);
  const nameSenaCard = document.createElement("div");
  nameSenaCard.classList.add("nameSenaCard");
  nameSenaCard.innerHTML = `<h3 id="nameUserSenaCard"></h3>`;
  bodySenaCard.appendChild(nameSenaCard);
  const dataSenaCard = document.createElement("div");
  dataSenaCard.classList.add("dataSenaCard");
  dataSenaCard.innerHTML = `<p>C.C. <span id="docUserSenaCard"></span></p>`;
  bodySenaCard.appendChild(dataSenaCard);
};
//Fin RenderInstructorSenaCard

//Inicio loadSenaCard
const loadSenaCard = (pic, role, name, doc, ficha, data) => {
  document.getElementById(pic).src = data.imagen;
  document.getElementById(role).innerHTML = data.cargo;
  document.getElementById(name).innerHTML = data.nombre;
  document.getElementById(doc).innerHTML = data.documento;
  if (document.getElementById(ficha)) {
    document.getElementById(ficha).innerHTML = data.ficha;
  }
};
//Fin loadSenaCard

//Inicio configScanQR
const configScanQR = async (func) => {
  //Configuracion QR
  const scanBtnOn = document.getElementById("scanBtnOn");
  const scanBtnOff = document.getElementById("scanBtnOff");
  const scanQrGif = document.getElementById("scanQrGif");
  const video = document.createElement("video");
  const canvasElement = document.getElementById("canvasQR");
  const canvas = canvasElement.getContext("2d");

  let scanning = false;
  //Encender camara
  const cameraOn = () => {
    navigator.mediaDevices
      .getUserMedia({ video: { facingMode: "environment" } })
      .then(function (stream) {
        scanning = true;
        scanQrGif.hidden = true;
        canvasElement.hidden = false;
        video.setAttribute("playsinline", true);
        video.srcObject = stream;
        video.play();
        tick();
        scan();
      });
  };
  //Levantar funciones de encendido de la camara
  function tick() {
    canvasElement.height = video.videoHeight;
    canvasElement.width = video.videoWidth;
    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

    scanning && requestAnimationFrame(tick);
  }
  function scan() {
    try {
      qrcode.decode();
    } catch (e) {
      setTimeout(scan, 300);
    }
  }
  //Apagar camara
  const cameraOff = () => {
    video.srcObject.getTracks().forEach((track) => {
      track.stop();
    });
    canvasElement.hidden = true;
    scanQrGif.hidden = false;
    scanBtnOn.hidden = false;
    scanBtnOff.hidden = true;
  };
  //Activar sonido
  const soundOn = () => {
    var audio = document.getElementById("beepQr");
    audio.play();
  };
  //Botones encendido y apagado de camara
  scanBtnOn.addEventListener("click", () => {
    cameraOn();
    scanBtnOff.hidden = false;
    scanBtnOn.hidden = true;
  });
  scanBtnOff.addEventListener("click", () => {
    cameraOff();
    scanBtnOff.hidden = true;
    scanBtnOn.hidden = false;
  });
  //Callback al leer el codigo QR
  qrcode.callback = (res) => {
    if (res) {
      func(res);
      soundOn();
      cameraOff();
    }
  };
};
//Fin configScanQR