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

//Inicio UserPassRequest
const userPassEditForm = document.getElementById("userPassEditForm");
userPassEditForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(userPassEditForm);
  const jsonData = JSON.stringify(Object.fromEntries(formData));
  const res = await fetch(usuariosAPI, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: jsonData,
  });
  const data = await res.json();
  if (data.success == true) {
    userPassEditForm.reset();
    showMessage(
      "messageUserPassEdit",
      "messageOK",
      data.message,
      "userPassEdit",
      1500
    );
  } else {
    showMessage("messageUserPassEdit", "messageErr", data.message, "", 1500);
  }
});
//Fin UserPassRequest
