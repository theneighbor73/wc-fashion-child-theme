(function () {
  "use strict";

  if (typeof wp === "undefined" || !wp.customize) {
    return;
  }

  const initialResize = customPrintShopConfig?.defaultScale ?? 0;

  function applyLogoScaleToPreview(scale) {
    scale = Math.max(-99, Math.min(100, parseInt(scale, 10) || initialResize));
    var multiplier = (100 + scale) / 100;
    var logo = document.querySelector(".custom-logo");

    if (!logo) {
      console.error("Logo not found.");
      return;
    }

    var logoWrapper = logo.parentElement;

    if (!logoWrapper) {
      console.error("Logo wrapper not found.");
      return;
    }

    // Grab the parent container (.site-logo) to fix the layout root alignment
    var siteLogoContainer = logoWrapper.parentElement;

    // Fallback chain: try natural size -> try attribute size -> try bounding rect -> default to a standard 150px
    var baseWidth = logo.naturalWidth || logo.width;
    var baseHeight = logo.naturalHeight || logo.height;

    // Calculate the new layout sizes just like the PHP side does
    var newWidth = Math.round(baseWidth * multiplier);
    var newHeight = Math.round(baseHeight * multiplier);

    // 1. Force the parent block .site-logo container to behave as a centered flexbox
    if (siteLogoContainer) {
      siteLogoContainer.style.setProperty("display", "flex");
      siteLogoContainer.style.setProperty("justify-content", "center");
      siteLogoContainer.style.setProperty("align-items", "center");
      siteLogoContainer.style.setProperty("width", "100%");
    }

    // 2. Apply rules to the <img> element
    logo.style.setProperty("width", newWidth + "px");
    logo.style.setProperty("height", newHeight + "px");
    logo.style.setProperty("max-width", "100%");
    logo.style.setProperty("display", "block");
    logo.style.setProperty("margin", "0 auto");

    // 3. Apply rules to the <a> wrapper
    logoWrapper.style.setProperty("display", "inline-flex");
    logoWrapper.style.setProperty("justify-content", "center");
    logoWrapper.style.setProperty("align-items", "center");
    logoWrapper.style.setProperty("margin", "0 auto");
    logoWrapper.style.setProperty("width", newWidth + "px");
    logoWrapper.style.setProperty("height", newHeight + "px");
    logoWrapper.style.setProperty("max-width", "100%");

    // Smooth transition effect
    logo.style.transition = "width 0.15s ease-out, height 0.15s ease-out";
  }

  wp.customize("logo_resize", function (value) {
    value.bind(function (newScale) {
      applyLogoScaleToPreview(newScale);
    });
  });

  wp.customize.bind("preview-ready", function () {
    var current = wp.customize("logo_resize")?.() ?? initialResize;
    applyLogoScaleToPreview(current);
  });
})();
