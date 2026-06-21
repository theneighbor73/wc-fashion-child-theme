// document.addEventListener("DOMContentLoaded", () => {
//   // Create outer container to control placement
//   let outerToast = document.querySelector(".custom-toast-container");
//   if (!outerToast) {
//     outerToast = document.createElement("div");
//     outerToast.className = "custom-toast-container";
//     document.body.appendChild(outerToast);
//   }

//   // Global show toast function
//   window.showToastMessage = (message) => {
//     if (message && typeof message === "string") {
//       const innerToast = document.createElement("div");
//       innerToast.className = "custom-toast";
//       innerToast.innerText = message;

//       outerToast.appendChild(innerToast);

//       setTimeout(() => {
//         // need timeout to work with css transition animation hook
//         innerToast.classList.add("show");
//       }, 10);

//       setTimeout(() => {
//         innerToast.classList.remove("show");

//         setTimeout(() => {
//           innerToast.remove();
//         }, 300);
//       }, 3000);
//     } else {
//       return;
//     }
//   };

//   // Render backend error related to the product search if any
//   if (typeof backendToastError !== "undefined" && backendToastError.message) {
//     showToastMessage(backendToastError.message);
//   }

//   // Frontend validation
//   const productSearchForms = document.querySelectorAll(
//     ".woocommerce-product-search, .search-form",
//   );

//   productSearchForms.forEach((form) => {
//     form.addEventListener("submit", (e) => {
//       const searchInput = form.querySelector('input[type="search"]');
//       if (searchInput) {
//         const cleanInput = searchInput.value.trim();

//         if (cleanInput === "") {
//           e.preventDefault();
//           searchInput.value = "";
//           showToastMessage("Please input search terms");
//         } else if (cleanInput.length < 3) {
//           e.preventDefault();
//           searchInput.value = "";
//           showToastMessage("Search terms must be at least 3 characters long.");
//         }
//       }
//     });
//   });
// });

document.addEventListener("DOMContentLoaded", () => {
  // Create container for the toast(s)
  const specialCharRegex = /[^a-zA-Z0-9 ]/;
  const invalidSearchRegex = /([a-zA-Z0-9])\1{4,}/;
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
