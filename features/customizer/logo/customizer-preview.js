(function () {
  "use strict";

  if (typeof wp === "undefined" || !wp.customize) {
    return;
  }

  let initialResize;
  let maxLogoHeight;

  if (typeof customPrintShopConfig !== "undefined") {
    const initialResize = customPrintShopConfig.defaultScale;
    const maxLogoHeight = customPrintShopConfig.desktopLogoMaxHeight;
  } else {
    const initialResize = 0;
    const maxLogoHeight = 46;
  }

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
    if (!siteLogoContainer) {
      console.error("Logo container not found.");
      return;
    }

    // Fallback chain: try natural size -> try attribute size -> try bounding rect -> default to a standard 150px
    var baseWidth = logo.naturalWidth ?? logo.width;
    var baseHeight = logo.naturalHeight ?? logo.height;

    var scaleWidthInProportion = maxLogoHeight / baseHeight;
    console.log("scaleWidthInProportion", scaleWidthInProportion);
    var allowedWidth = Math.round(scaleWidthInProportion * baseWidth);

    // Calculate the new layout sizes just like the PHP side does
    var newWidth = Math.round(baseWidth * multiplier);
    var newHeight = Math.round(baseHeight * multiplier);

    // 1. Force the parent block .site-logo container to behave as a centered flexbox
    siteLogoContainer.style.setProperty("display", "flex");
    siteLogoContainer.style.setProperty("justify-content", "center");
    siteLogoContainer.style.setProperty("align-items", "center");

    // 2. Apply rules to the <img> element
    logo.style.setProperty("width", newWidth + "px");
    logo.style.setProperty("height", newHeight + "px");
    logo.style.setProperty("max-width", allowedWidth + "px");
    logo.style.setProperty("max-height", maxLogoHeight + "px");
    logo.style.setProperty("margin", "0 auto");

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
