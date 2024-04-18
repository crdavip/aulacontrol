  const formContainer = document.getElementById("usersCreate");
  const editFormContainer = document.getElementById("usersEdit");
  const editPassFormContainer = document.getElementById("usersPassEdit");
  const deleteFormContainer = document.getElementById("usersDelete");
  const filterDesign = document.getElementById("filterPg");
  const row = document.querySelector(".row");
  const urlAPI = "./controller/user.php";

  // Mostrar usuarios
  function getUsers() {
    const filterValue = filterDesign.value;
    fetch(urlAPI + "?filter=" + filterValue)
      .then((response) => response.json())
      .then((usersData) => {
        row.innerHTML = "";
        usersData.forEach((user) => {
          const cardUser = document.createElement("div");
          cardUser.className = "cardUser";
          const cardUserTop = document.createElement("div");
          cardUserTop.className = "cardUserTop";
          const cardUserButtons = document.createElement("div");
          cardUserButtons.classList.add("cardUserButtons");
          cardUserTop.appendChild(cardUserButtons);
          const editBtn = document.createElement("a");
          editBtn.className = "btnEdit";
          editBtn.innerHTML = '<i class="bi bi-pencil"></i>';
          editBtn.addEventListener("click", () => editUserData(user));
          cardUserButtons.appendChild(editBtn);
          const deleteBtn = document.createElement("a");
          deleteBtn.className = "btnDelete";
          deleteBtn.innerHTML = '<i class="bi bi-trash3"></i>';
          deleteBtn.addEventListener("click", () => deleteUserData(user));
          cardUserButtons.appendChild(deleteBtn);
          const cardUserBody = document.createElement("div");
          cardUserBody.classList.add("cardUserBody");
          const cardUserPic = document.createElement("div");
          cardUserPic.classList.add("cardUserPic");
          const img = document.createElement("img");
          img.src = user.IMAGEN;
          img.alt = "profile";
          cardUserPic.appendChild(img);
          const cardUserTxt = document.createElement("div");
          cardUserTxt.classList.add("cardUserTxt");
          const cardUserTxtRol = document.createElement("p");
          const cardUserTxtName = document.createElement("h3");
          const cardUserTxtCargo = document.createElement("span");
          cardUserTxtRol.textContent = user.ROL;
          cardUserTxtName.textContent = user.NOMBRE;
          cardUserTxtCargo.textContent = user.DOCUMENTO;
          cardUserTxt.appendChild(cardUserTxtRol);
          cardUserTxt.appendChild(cardUserTxtName);
          cardUserTxt.appendChild(cardUserTxtCargo);
          cardUserBody.appendChild(cardUserPic);
          cardUserBody.appendChild(cardUserTxt);
          cardUser.appendChild(cardUserTop);
          cardUser.appendChild(cardUserBody);
          row.appendChild(cardUser);
        });
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }

  filterDesign.onchange = getUsers;

  getUsers();

  // Agregar usuario
  formContainer.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(formContainer.querySelector("form"));
    const jsonData = JSON.stringify(Object.fromEntries(formData));
    fetch(urlAPI, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: jsonData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.userCreate == true) {
          document.getElementById("messageCreate").textContent = data.message;
          document.getElementById("messageCreate").classList.add("messageShow");
          getUsers();
          formContainer.querySelector("form").reset();
          setTimeout(() => {
            document.getElementById("messageCreate").textContent = "";
            document
              .getElementById("messageCreate")
              .classList.remove("messageShow");
            closeModal("usersCreate");
          }, 1500);
        } else {
          document.getElementById("messageCreate").textContent = data.message;
          document.getElementById("messageCreate").classList.add("messageShow");
          setTimeout(() => {
            document.getElementById("messageCreate").textContent = "";
            document
              .getElementById("messageCreate")
              .classList.remove("messageShow");
          }, 1500);
        }
      });
  });

  // Cargar datos en editar usuario
  function editUserData(user) {
    openModal("usersEdit");
    const btnPassEdit = document.getElementById("btnPassEdit");
    btnPassEdit.addEventListener("click", () => editUserPassData(user));
    document.getElementById("namesEdit").value = user.NOMBRE;
    document.getElementById("docEdit").value = user.DOCUMENTO;
    document.getElementById("mailEdit").value = user.EMAIL;
    document.getElementById("roleEdit").value = user.ROL_ID;
    document.getElementById("userId").value = user.USUARIO_ID;
  }

  // Editar usuario
  editFormContainer.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(editFormContainer.querySelector("form"));
    const jsonData = JSON.stringify(Object.fromEntries(formData));
    fetch(urlAPI, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: jsonData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.userUpdate == true) {
          document.getElementById("messageEdit").textContent = data.message;
          document.getElementById("messageEdit").classList.add("messageShow");
          setTimeout(() => {
            closeModal("usersEdit");
            getUsers();
            document.getElementById("messageEdit").textContent = "";
            document
              .getElementById("messageEdit")
              .classList.remove("messageShow");
          }, 1500);
        } else {
          document.getElementById("messageEdit").textContent = data.message;
          document.getElementById("messageEdit").classList.add("messageShow");
          setTimeout(() => {
            document.getElementById("messageEdit").textContent = "";
            document
              .getElementById("messageEdit")
              .classList.remove("messageShow");
          }, 1500);
        }
      });
  });

  // Cargar datos en editar contraseña
  function editUserPassData(user) {
    openModal("usersPassEdit");
    closeModal("usersEdit");
    document.getElementById("userPassId").value = user.USUARIO_ID;
    document.getElementById("namesPassEdit").textContent = user.NOMBRE;
  }

  // Editar contraseña
  editPassFormContainer.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(editPassFormContainer.querySelector("form"));
    const jsonData = JSON.stringify(Object.fromEntries(formData));
    fetch(urlAPI, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: jsonData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.passUpdate == true) {
          document.getElementById("messagePassEdit").textContent = data.message;
          document
            .getElementById("messagePassEdit")
            .classList.add("messageShow");
          setTimeout(() => {
            closeModal("usersPassEdit");
            getUsers();
            document.getElementById("messagePassEdit").textContent = "";
            document
              .getElementById("messagePassEdit")
              .classList.remove("messagePassEdit");
          }, 1500);
        } else {
          document.getElementById("messagePassEdit").textContent = data.message;
          document
            .getElementById("messagePassEdit")
            .classList.add("messageShow");
          setTimeout(() => {
            document.getElementById("messagePassEdit").textContent = "";
            document
              .getElementById("messagePassEdit")
              .classList.remove("messagePassEdit");
          }, 1500);
        }
      });
  });

  // Cargar datos en eliminar usuario
  function deleteUserData(user) {
    openModal("usersDelete");
    deleteFormContainer.querySelector("#userIdDelete").value = user.USUARIO_ID;
  }

  // Eliminar usuario
  deleteFormContainer.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(deleteFormContainer.querySelector("form"));
    const jsonData = JSON.stringify(Object.fromEntries(formData));
    fetch(urlAPI, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
      body: jsonData,
    })
      .then((response) => response.json())
      .then((data) => {
        document.getElementById("messageDelete").textContent = data.message;
        document.getElementById("messageDelete").classList.add("messageShow");
        getUsers();
        setTimeout(() => {
          closeModal("usersDelete");
          document.getElementById("messageDelete").textContent = "";
          document
            .getElementById("messageDelete")
            .classList.remove("messageShow");
        }, 1500);
      });
  });