(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", () => {
    // Isolated regex rules - completely safe from global collision
    const specialCharRegex = /[^\p{L}\p{N} ']/u;
    const invalidSearchRegex = /([\p{L}\p{N}'])\1{3,}/u;

    // Helper to safely call your global toast system
    const triggerToast = (msg) => {
      if (typeof window.cpsc_showToastMessage === "function") {
        window.cpsc_showToastMessage(msg);
      } else {
        console.warn("Toast system not loaded yet:", msg);
      }
    };

    // Show toast passed down from backend if any exists in global scope
    if (
      typeof window.backendToastError !== "undefined" &&
      typeof window.backendToastError.message === "string"
    ) {
      triggerToast(window.backendToastError.message);
    }

    // Process frontend form constraints
    const productSearchForms = document.querySelectorAll(
      ".woocommerce-product-search, .search-form",
    );

    productSearchForms.forEach((form) => {
      form.addEventListener("submit", (e) => {
        const searchInput = form.querySelector('input[type="search"]');

        if (searchInput) {
          const cleanInput = searchInput.value.trim();

          if (cleanInput === "") {
            e.preventDefault();
            triggerToast("Please input search term.");
            searchInput.value = "";
          } else if (cleanInput.length < 3) {
            e.preventDefault();
            triggerToast("Search terms must be at least 3 characters long.");
          } else if (specialCharRegex.test(cleanInput)) {
            e.preventDefault();
            triggerToast("Search terms cannot contain special characters.");
          } else if (invalidSearchRegex.test(cleanInput)) {
            e.preventDefault();
            triggerToast("Invalid search terms. Please try again.");
            searchInput.value = "";
          }
        }
      });
    });
  });
})();
