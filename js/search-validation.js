document.addEventListener("DOMContentLoaded", function () {
  console.log("Backend passes data: ", backendToastError);
  // 1. Create the Toast container wrapper dynamically in the HTML DOM body
  let toastContainer = document.querySelector(".custom-toast-container");
  if (!toastContainer) {
    toastContainer = document.createElement("div");
    toastContainer.className = "custom-toast-container";
    document.body.appendChild(toastContainer);
  }

  // 2. THE REUSABLE TOAST ENGINE FUNCTION
  window.showToast = function (message) {
    // Build the toast element bubble
    const toast = document.createElement("div");
    toast.className = "custom-toast";
    toast.innerText = message;

    // Drop it inside our container
    toastContainer.appendChild(toast);

    // Force a minor browser repaint check, then activate slide-down transition
    setTimeout(() => {
      toast.classList.add("show");
    }, 10);

    // Auto-dismiss cleanup timeline (Starts sliding away at 3000ms, removes from DOM completely at 3300ms)
    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => {
        toast.remove();
      }, 300);
    }, 3000);
  };

  // 3. LAYER 1: FRONTEND VALIDATION
  // Intercept WooCommerce product search form submittal points
  const searchForms = document.querySelectorAll(
    ".woocommerce-product-search, .search-form",
  );

  searchForms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      const searchInput = form.querySelector('input[type="search"]');

      if (searchInput) {
        const cleanValue = searchInput.value.trim();

        // Block empty submissions or short strings
        if (cleanValue === "") {
          event.preventDefault(); // Stop page reload execution track
          window.showToast("Please enter a search term!");
        } else if (cleanValue.length < 3) {
          event.preventDefault(); // Stop page reload execution track
          window.showToast("Search terms must be at least 3 characters long.");
        }
      }
    });
  });

  // 4. LAYER 2: BACKEND ERROR PASSTHROUGH
  // Check if our PHP script passed down a server validation block error notice
  if (typeof backendToastError !== "undefined" && backendToastError.message) {
    window.showToast(backendToastError.message);
  }
});
