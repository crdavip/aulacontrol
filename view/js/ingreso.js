const loginForm = document.getElementById("login");
const userImgPic = document.getElementById("userImgPic");
const inputUser = document.getElementById("doc");
const messageUser = document.getElementById("labelDoc");
const messageUserOrigin = messageUser.innerHTML;
const inputPass = document.getElementById("pass");
const messagePass = document.getElementById("labelPass");
const messagePassOrigin = messagePass.innerHTML;
const remember = document.getElementById("remember");
const ingresoAPI = "./controller/ingreso.php";

inputUser.value = localStorage.getItem("acDoc");
remember.checked = localStorage.getItem("acKey") === "true";
if (localStorage.getItem("acImg") != null) {
  userImgPic.src = localStorage.getItem("acImg");
}

loginForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const formData = new FormData(loginForm);
  const jsonData = JSON.stringify(Object.fromEntries(formData));
  fetch(ingresoAPI, {
    method: "POST",
    headers: {
      "Content-type": "aplication/json",
    },
    body: jsonData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.successUser == true) {
        if (remember.checked) {
          localStorage.setItem("acDoc", inputUser.value);
          localStorage.setItem("acKey", "true");
          localStorage.setItem("acImg", data.image);
        } else {
          localStorage.setItem("acDoc", "");
          localStorage.setItem("acKey", "");
          localStorage.setItem("acImg", "./view/img/users/default.jpg");
        }
        window.location.href = "./";
      } else if (data.successPass === false) {
        showError(inputPass, messagePass, data.message);
      } else {
        showError(inputUser, messageUser, data.message);
      }
    })
    .catch((err) => {
      console.log(err);
    });
});

const lostPassForm = document.getElementById("lostPassForm");
const inputPassDoc = document.getElementById("lostPassDoc");
const messagePassUser = document.getElementById("labelLostPassDoc");
const messagePassUserOrigin = messagePassUser.innerHTML;
const messageLostPass = document.querySelector(".inputGroup p");
const messageLostPassOrigin = messageLostPass.textContent;

lostPassForm.addEventListener("submit", (event) => {
  event.preventDefault();
  messageLostPass.innerHTML = "Enviando enlace al correo...";
  const formData = new FormData(lostPassForm);
  const jsonData = JSON.stringify(Object.fromEntries(formData));
  fetch(ingresoAPI, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: jsonData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.successUser == true) {
        messageLostPass.innerHTML = data.message;
        setTimeout(() => {
          messageLostPass.textContent = messageLostPassOrigin;
          lostPassForm.reset();
          closeModal("lostPass");
        }, 3500);
      } else {
        messageLostPass.innerHTML = messageLostPassOrigin;
        showError(inputPassDoc, messagePassUser, data.message);
      }
    })
    .catch((err) => {
      console.log(err);
    });
});

function showError(input, label, message) {
  label.textContent = message;
  label.classList.add("labelError");
  input.classList.add("inputError");
  setTimeout(() => {
    label.innerHTML = messageUserOrigin;
    label.classList.remove("labelError");
    input.classList.remove("inputError");
  }, 1500);
}