//Inicio DataUser
const userIdView = document.getElementById("userIdView").value;
const userNameView = document.getElementById("userNameView").value;
const userRolView = document.getElementById("userRolView").value;
const userImgView = document.getElementById("userImgView").value;
const userNameTxt = document.getElementById("userName");
const userImagePic = document.getElementById("userImagePic");
userNameTxt.textContent = userNameView;
userImagePic.src = userImgView;
//Fin DataUser

//Inicio UserRequest
const userProfileForm = document.getElementById("userProfileForm");
let userAPI = "./controller/user.php";
userProfileForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const formData = new FormData(userProfileForm);
  fetch(userAPI, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.userUpdate == true) {
        document.getElementById("messageUserProfile").textContent =
          data.message;
        document
          .getElementById("messageUserProfile")
          .classList.add("messageShow");
        setTimeout(() => {
          closeModal("userProfile");
          location.reload();
          document.getElementById("messageUserProfile").textContent = "";
          document
            .getElementById("messageUserProfile")
            .classList.remove("messageShow");
        }, 1500);
      } else {
        document.getElementById("messageUserProfile").textContent =
          data.message;
        document
          .getElementById("messageUserProfile")
          .classList.add("messageShow");
        setTimeout(() => {
          document.getElementById("messageUserProfile").textContent = "";
          document
            .getElementById("messageUserProfile")
            .classList.remove("messageShow");
        }, 1500);
      }
    });
});
//Fin UserRequest

//Inicio UserPassRequest
const userPassEditForm = document.getElementById("userPassEditForm");
userAPI = "./controller/user.php";
userPassEditForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const formData = new FormData(userPassEditForm);
  const jsonData = JSON.stringify(Object.fromEntries(formData));
  fetch(userAPI, {
    method: "PUT",
    headers: {
      "Content-type": "aplication/json",
    },
    body: jsonData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.passUpdate == true) {
        document.getElementById("messageUserPassEdit").textContent =
          data.message;
        document
          .getElementById("messageUserPassEdit")
          .classList.add("messageShow");
        setTimeout(() => {
          closeModal("userPassEdit");
          getUsers();
          document.getElementById("messageUserPassEdit").textContent = "";
          document
            .getElementById("messageUserPassEdit")
            .classList.remove("messageUserPassEdit");
        }, 1500);
      } else {
        document.getElementById("messageUserPassEdit").textContent =
          data.message;
        document
          .getElementById("messageUserPassEdit")
          .classList.add("messageShow");
        setTimeout(() => {
          document.getElementById("messageUserPassEdit").textContent = "";
          document
            .getElementById("messageUserPassEdit")
            .classList.remove("messageUserPassEdit");
        }, 1500);
      }
    });
});
//Fin UserPassRequest
