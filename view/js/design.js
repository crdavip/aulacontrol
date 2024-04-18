const formContainer = document.getElementById("designCreate");
const editFormContainer = document.getElementById("designEdit");
const deleteFormContainer = document.getElementById("designDelete");
const filterDesign = document.getElementById("filterPg");
const row = document.querySelector(".row");
const urlAPI = "./controller/design.php";

// Mostrar diseños
function getDesigns() {
  const filterValue = filterDesign.value;
  const userRolView = document.getElementById("userRolView").value;
  fetch(`${urlAPI}?filter=${filterValue}`)
    .then((response) => response.json())
    .then((designData) => {
      row.innerHTML = "No hay diseños guardados.";
      if (designData.length > 0) {
        row.innerHTML = "";
        designData.forEach((design) => {
          const cardDesign = document.createElement("div");
          cardDesign.className = "cardDesign";
          cardDesign.id = `cardDesignId${design.DISENO_ID}`;
          const cardDesignTop = document.createElement("div");
          cardDesignTop.className = "cardDesignTop";
          const cardDesignMenu = document.createElement("a");
          cardDesignMenu.className = "cardDesignMenu";
          cardDesignMenu.innerHTML = '<i class="bi bi-three-dots-vertical"></i>';
          const cardDesignItems = document.createElement("div");
          cardDesignMenu.addEventListener("click", () => {
            cardDesignItems.classList.toggle("showDropend");
          });
          document.addEventListener("click", (event) => {
            if (
              !cardDesignMenu.contains(event.target) &&
              !cardDesignItems.contains(event.target)
            ) {
              cardDesignItems.classList.remove("showDropend");
            }
          });
          cardDesignItems.addEventListener("click", (event) => {
            event.stopPropagation();
          });
          cardDesignItems.querySelectorAll("a").forEach((link) => {
            link.addEventListener("click", (event) => {
              event.stopPropagation();
              cardDesignItems.classList.remove("showDropend");
            });
          });
          cardDesignItems.className = "cardDesignItems";
          const btnEdit = document.createElement("a");
          btnEdit.className = "edit";
          btnEdit.innerHTML = '<i class="bi bi-pencil"></i>Editar';
          btnEdit.addEventListener("click", () => editDesignData(design));
          const btnDelete = document.createElement("a");
          btnDelete.className = "delete";
          btnDelete.innerHTML = '<i class="bi bi-trash3"></i>Eliminar';
          btnDelete.addEventListener("click", () => deleteDesignData(design));
          const cardDesignImg = document.createElement("div");
          cardDesignImg.className = "cardDesignImg";
          cardDesignImg.addEventListener("click", () => viewDesign(design));
          const img = document.createElement("img");
          img.src = design.IMAGEN;
          img.alt = "design";
          const cardDesignBody = document.createElement("div");
          cardDesignBody.className = "cardDesignBody";
          const cardDesignTxtSpan = document.createElement("span");
          const cardLike = document.createElement("a");
          cardLike.className = "cardLike";
          cardLike.innerHTML = '<i class="bi bi-hand-thumbs-up"></i>';
          const cardDesignTxt = document.createElement("div");
          cardDesignTxt.className = "cardDesignTxt";
          const cardDisLike = document.createElement("a");
          cardDisLike.className = "cardDisLike";
          cardDisLike.innerHTML = '<i class="bi bi-hand-thumbs-down"></i>';
          if (userRolView == 2) {
            cardDesignTop.appendChild(cardDesignMenu);
          }
          switch (design.ESTADO) {
            case null:
              if (userRolView == 1) {
                cardDesignBody.style.justifyContent = "space-between";
                cardDesignBody.appendChild(cardLike);
                cardLike.addEventListener("click", () =>
                  designCheck(design, true)
                );
                cardDesignBody.appendChild(cardDesignTxt);
                design.ESTADO = "Sin Revisar";
                cardDesignTxtSpan.style.color = "var(--secondary)";
                cardDesignBody.appendChild(cardDisLike);
                cardDisLike.addEventListener("click", () =>
                  designCheck(design, false)
                );
              } else {
                cardDesignItems.appendChild(btnEdit);
                cardDesignBody.appendChild(cardDesignTxt);
                design.ESTADO = "Sin Revisar";
              }
  
              break;
            case 1:
              cardDesignBody.appendChild(cardDesignTxt);
              design.ESTADO = "Aprobado";
              cardDesignTxtSpan.style.color = "var(--primary)";
              break;
            case 0:
              cardDesignBody.appendChild(cardDesignTxt);
              design.ESTADO = "Rechazado";
              cardDesignTxtSpan.style.color = "var(--complement)";
              break;
          }
          const cardDesignTxtH3 = document.createElement("h3");
          cardDesignTxtH3.textContent = design.NOMBRE;
          cardDesignTxtSpan.textContent = design.ESTADO;
          cardDesignImg.appendChild(img);
          cardDesignTop.appendChild(cardDesignImg);
          cardDesignItems.appendChild(btnDelete);
          cardDesignTop.appendChild(cardDesignItems);
          cardDesignTxt.appendChild(cardDesignTxtH3);
          cardDesignTxt.appendChild(cardDesignTxtSpan);
          cardDesign.appendChild(cardDesignTop);
          cardDesign.appendChild(cardDesignBody);
          row.appendChild(cardDesign);
        });
        alertTarget();
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

filterDesign.onchange = getDesigns;

getDesigns();

//Objetivo de alerta
function alertTarget() {
  const urlParams = new URLSearchParams(window.location.search);
  const cardId = urlParams.get("cardId");

  if (cardId) {
    const targetCard = document.getElementById(`cardDesignId${cardId}`);
    if (targetCard) {
      targetCard.scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
      targetCard.classList.add("targetCard");
      setTimeout(() => {
        targetCard.classList.remove("targetCard");
      }, 3000);
    }
  }
}

// Agregar diseño
if (formContainer) {
  formContainer.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(formContainer.querySelector("form"));
    fetch(urlAPI, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.designCreate == true) {
          document.getElementById("messageCreate").textContent = data.message;
          document.getElementById("messageCreate").classList.add("messageShow");
          getDesigns();
          formContainer.querySelector("form").reset();
          setTimeout(() => {
            document.getElementById("messageCreate").textContent = "";
            document
              .getElementById("messageCreate")
              .classList.remove("messageShow");
            closeModal("designCreate");
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
}

// Ver diseño
function viewDesign(design) {
  openModal("designView");
  const titlePgH1 = document.querySelector(".containerModal .titlePg h1");
  const titlePgSpan = document.querySelector(".containerModal .titlePg span");
  const titlePgDate = document.querySelector(".containerModal .titlePg p");
  const viewDesignImg = document.getElementById("designViewImg");
  viewDesignImg.src = design.IMAGEN;
  titlePgH1.textContent = design.NOMBRE;
  switch (design.ESTADO) {
    case "Sin Revisar":
      titlePgSpan.style.color = "var(--secondary)";
      break;
    case "Aprobado":
      titlePgSpan.style.color = "var(--primary)";
      break;
    case "Rechazado":
      titlePgSpan.style.color = "var(--complement)";
      break;
  }
  titlePgSpan.textContent = design.ESTADO;
  titlePgDate.textContent = design.FECHA_CREACION;
}

// Aprobar diseño
function designCheck(design, status) {
  const designId = design.DISENO_ID;
  const designName = design.NOMBRE;
  const designStatus = status;
  const jsonData = JSON.stringify({ designId: designId, designName: designName, status: designStatus });
  fetch(urlAPI, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: jsonData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.designUpdate == true) {
        getDesigns();
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

// Cargar datos en editar diseño
function editDesignData(design) {
  openModal("designEdit");
  document.getElementById("nameDesignEdit").value = design.NOMBRE;
  document.getElementById("designIdEdit").value = design.DISENO_ID;
  const designImgUploadEdit = document.getElementById("designImgUploadEdit");
  const designImgEdit = document.getElementById("designImgEdit");
  const designImgPreviewEdit = document.getElementById("designImgPreviewEdit");
  isFileInputOpen = false;
  const openFileInput = () => {
    if (!isFileInputOpen) {
      userImgProfile.click();
    } else {
      isFileInputOpen = false;
    }
  };
  designImgUploadEdit.addEventListener("click", openFileInput);
  designImgPreviewEdit.src = design.IMAGEN;
  designImgPreviewEdit.style.opacity = 1;
  designImgPreviewEdit.addEventListener("click", openFileInput);
  designImgEdit.addEventListener("change", (e) => {
    isFileInputOpen = false;
    if (e.target.files[0]) {
      designImgPreviewEdit.style.opacity = 1;
      const readerEdit = new FileReader();
      readerEdit.onload = function (e) {
        designImgPreviewEdit.src = e.target.result;
      };
      readerEdit.readAsDataURL(e.target.files[0]);
    } else {
      designImgPreviewEdit.src = design.IMAGEN;
      designImgPreviewEdit.style.opacity = 1;
    }
  });
  userImgProfile.addEventListener("click", () => {
    isFileInputOpen = true;
  });
}

// Editar diseño
editFormContainer.addEventListener("submit", function (event) {
  event.preventDefault();
  const formData = new FormData(editFormContainer.querySelector("form"));
  fetch(urlAPI, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.designUpdate == true) {
        document.getElementById("messageEdit").textContent = data.message;
        document.getElementById("messageEdit").classList.add("messageShow");
        setTimeout(() => {
          closeModal("designEdit");
          editFormContainer.querySelector("form").reset();
          getDesigns();
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

// Cargar datos en eliminar diseño
function deleteDesignData(design) {
  openModal("designDelete");
  deleteFormContainer.querySelector("#designIdDelete").value = design.DISENO_ID;
  deleteFormContainer.querySelector("#designImgDelete").value = design.IMAGEN;
  console.log(design.IMAGEN);
}

// Eliminar diseño
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
      getDesigns();
      setTimeout(() => {
        closeModal("designDelete");
        document.getElementById("messageDelete").textContent = "";
        document
          .getElementById("messageDelete")
          .classList.remove("messageShow");
      }, 1500);
    });
});

//Inicio ImagePreview
const designImgUpload = document.getElementById("designImgUpload");
const designImg = document.getElementById("designImg");
const designImgPreview = document.getElementById("designImgPreview");
isFileInputOpen = false;
const openFileInput = () => {
  if (!isFileInputOpen) {
    designImg.click();
  } else {
    isFileInputOpen = false;
  }
};
designImgUpload.addEventListener("click", openFileInput);
designImgPreview.addEventListener("click", openFileInput);
designImg.addEventListener("change", (e) => {
  isFileInputOpen = false;
  if (e.target.files[0]) {
    designImgPreview.style.opacity = 1;
    const reader = new FileReader();
    reader.onload = function (e) {
      designImgPreview.src = e.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
  } else {
    designImgPreview.src = "";
    designImgPreview.style.opacity = 0;
  }
});
designImg.addEventListener("click", () => {
  isFileInputOpen = true;
});
formContainer.addEventListener("reset", function (event) {
  designImgPreview.src = "";
  designImgPreview.style.opacity = 0;
  designImg.value = null;
});
//Fin ImagePreview
