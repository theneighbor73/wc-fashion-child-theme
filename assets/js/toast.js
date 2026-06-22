// refactor later
document.addEventListener("DOMContentLoaded", () => {
  let outerToast = document.querySelector(".custom-toast-container");
  if (!outerToast) {
    outerToast = document.createElement("div");
    outerToast.className = "custom-toast-container";
    document.body.appendChild(outerToast);
  }

  // Create global show toast message function
  window.showToastMessage = (message) => {
    if (message && typeof message === "string") {
      const innerToast = document.createElement("div");
      innerToast.className = "custom-toast";
      innerToast.innerText = message;

      outerToast.appendChild(innerToast);

      setTimeout(() => {
        innerToast.classList.add("show");
      }, 10);

      setTimeout(() => {
        innerToast.classList.remove("show");
        setTimeout(() => {
          innerToast.remove();
        }, 300);
      }, 3000);
    } else return;
  };
});
