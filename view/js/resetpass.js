  const usersPassEditForm = document.getElementById("usersPassEditForm");
  usersPassEditForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    const formData = new FormData(usersPassEditForm);
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
      setTimeout(() => {
        window.location.href = "./";
      }, 1500);
      showMessage(
        "messagePassEdit",
        "messageOK",
        data.message,
        "",
        1500
      );
    } else {
      showMessage("messagePassEdit", "messageErr", data.message, "", 1500);
    }
  });