  const usersPassEditForm = document.getElementById("usersPassEditForm");
  const urlAPI = "./controller/user.php";
  usersPassEditForm.addEventListener("submit", (event) => {
    event.preventDefault();
    const formData = new FormData(usersPassEditForm);
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
            window.location.href = "./";
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