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
if (document.querySelector(".sidenavSearch")) {
  const body = document.querySelector("body"),
    sidenav = body.querySelector(".sidenav"),
    header = body.querySelector(".header"),
    toggle = body.querySelector(".toggle"),
    searchBtn = body.querySelector(".sidenavSearch");
  toggle.addEventListener("click", () => {
    sidenav.classList.toggle("close");
    header.classList.toggle("close");
    if (localStorage.getItem("navOpen") == "true") {
      localStorage.setItem("navOpen", "false");
    } else {
      localStorage.setItem("navOpen", "true");
    }
  });
  searchBtn.addEventListener("click", () => {
    sidenav.classList.remove("close");
    header.classList.remove("close");
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

//Inicio DropEndUser
if (document.querySelector(".userImage")) {
  const profileBtn = document.querySelector(".userImage");
  const userItems = document.querySelector(".userItems");
  profileBtn.addEventListener("click", () => {
    userItems.classList.toggle("showDropend");
  });
  document.addEventListener("click", (event) => {
    if (!profileBtn.contains(event.target) && !userItems.contains(event.target)) {
      userItems.classList.remove("showDropend");
    }
  });
  userItems.addEventListener("click", (event) => {
    event.stopPropagation();
  });
  userItems.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", (event) => {
      event.stopPropagation();
      userItems.classList.remove("showDropend");
    });
  }); 
}
//Fin DropEndUser

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