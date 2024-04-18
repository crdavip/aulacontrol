  const formContainer = document.getElementById("contentLogin");
  const lostPassForm = document.getElementById("lostPassForm");
  const valAPI = "./controller/validation.php";
  const userImgPic = document.getElementById("userImgPic");
  const inputUser = document.getElementById("doc");
  const messageUser = document.getElementById("labelDoc");
  const messageUserOrigin = messageUser.innerHTML;
  const inputPass = document.getElementById("pass");
  const messagePass = document.getElementById("labelPass");
  const messagePassOrigin = messagePass.innerHTML;
  const remember = document.getElementById("remember");

  inputUser.value = localStorage.getItem("userDoc");
  remember.checked = localStorage.getItem("userKey") === "true";
  if (localStorage.getItem("userImg") != null) {
    userImgPic.src = localStorage.getItem("userImg"); 
  }

  formContainer.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(formContainer.querySelector("form"));
    const jsonData = JSON.stringify(Object.fromEntries(formData));
    fetch(valAPI, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: jsonData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.successUser === true) {
          if (remember.checked) {
            localStorage.setItem("userDoc", inputUser.value);
            localStorage.setItem("userKey", "true");
            localStorage.setItem("userImg", data.image);
          } else {
            localStorage.setItem("userDoc", "");
            localStorage.setItem("userKey", "");
            localStorage.setItem("userImg", "./view/img/users/default.jpg");
          }
          window.location.href = "./";
        } else if (data.successPass === false) {
          messagePass.textContent = data.message;
          messagePass.classList.add("labelError");
          inputPass.classList.add("inputError");
          setTimeout(() => {
            messagePass.innerHTML = messagePassOrigin;
            messagePass.classList.remove("labelError");
            inputPass.classList.remove("inputError");
          }, 1500);
        } else {
          messageUser.textContent = data.message;
          messageUser.classList.add("labelError");
          inputUser.classList.add("inputError");
          setTimeout(() => {
            messageUser.innerHTML = messageUserOrigin;
            messageUser.classList.remove("labelError");
            inputUser.classList.remove("inputError");
          }, 1500);
        }
      });
  });

  const inputPassDoc = document.getElementById("lostPassDoc");
  const messagePassUser = document.getElementById("labelLostPassDoc");
  const messagePassUserOrigin = messagePassUser.innerHTML;
  const messageLostPass = document.querySelector(".inputGroup p");
  const messageLostPassOrigin = messageLostPass.textContent;

  lostPassForm.addEventListener("submit", (event) => {
    event.preventDefault();
    messageLostPass.innerHTML = 'Enviando enlace al correo...';
    const formData = new FormData(lostPassForm);
    const jsonData = JSON.stringify(Object.fromEntries(formData));
    fetch(valAPI, {
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
            closeModal('lostPass');
          }, 3500);
        } else {
          messageLostPass.innerHTML = messageLostPassOrigin;
          messagePassUser.textContent = data.message;
          messagePassUser.classList.add("labelError");
          inputPassDoc.classList.add("inputError");
          setTimeout(() => {
            messagePassUser.innerHTML = messagePassUserOrigin;
            messagePassUser.classList.remove("labelError");
            inputPassDoc.classList.remove("inputError");
          }, 1500);
        }
      });
  });
