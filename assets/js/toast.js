(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", () => {
    outerToast = document.querySelector(".custom-toast-container");
  });

  /**
   * INTENTIONALLY mount showToastMessage to the global window object.
   */
  window.cpsc_showToastMessage = function (message) {
    if (typeof message !== "string" || !message.trim()) {
      return;
    }

    // Ensure the container exists just in case DOMContentLoaded hasn't finished
    if (!outerToast) {
      outerToast = document.querySelector(".custom-toast-container");
      if (!outerToast) return;
    }

    const innerToast = document.createElement("div");
    innerToast.className = "custom-toast";
    innerToast.innerText = message;

    outerToast.appendChild(innerToast);

    // Trigger CSS opacity transition animation
    setTimeout(() => {
      innerToast.classList.add("show");
    }, 10);

    // Gracefully fade out and remove the element from the DOM after 3 seconds
    setTimeout(() => {
      innerToast.classList.remove("show");

      setTimeout(() => {
        innerToast.remove();
      }, 300); // Matches standard CSS fade transition timing
    }, 3000);
  };
})();
