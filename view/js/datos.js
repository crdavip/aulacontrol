const userFirstTime = document.getElementById("userFirstTime");
userFirstTime.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(userFirstTime);
    const res = await fetch(datosAPI, {
        method: 'POST',
        body: formData,
    });
    const data = await res.json();
    if (data.success == true) {
        setTimeout(() => {
            window.location.href = './';
        }, 1500);
        showMessage("messageCreate", "messageOK", data.message, "", 1500);
      } else {
        showMessage("messageCreate", "messageErr", data.message, "", 1500);
      }
});