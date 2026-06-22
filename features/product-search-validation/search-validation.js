document.addEventListener("DOMContentLoaded", () => {
  const specialCharRegex = /[^\p{L}\p{N} ']/u;
  const invalidSearchRegex = /([\p{L}\p{N}'])\1{3,}/u;

  // Show toast past down from backend if any
  if (
    typeof backendToastError !== "undefined" &&
    typeof backendToastError.message === "string"
  ) {
    showToastMessage(backendToastError.message);
  }

  // Frontend show toasts
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
          showToastMessage("Please input search term.");
          searchInput.value = "";
        } else if (cleanInput.length < 3) {
          e.preventDefault();
          showToastMessage("Search terms must be at least 3 characters long.");
        } else if (specialCharRegex.test(cleanInput)) {
          e.preventDefault();
          showToastMessage("Search terms cannot contain special characters.");
        } else if (invalidSearchRegex.test(cleanInput)) {
          e.preventDefault();
          showToastMessage("Invalid search terms. Please try again.");
          searchInput.value = "";
        }
      }
    });
  });
});
