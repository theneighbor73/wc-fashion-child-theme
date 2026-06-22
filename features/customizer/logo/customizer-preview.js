(function () {
  "use strict";

  if (typeof wp === "undefined" || !wp.customize) {
    return;
  }

  function applyLogoScaleToPreview(scale) {
    scale = Math.max(-100, Math.min(100, parseInt(scale, 10) || 0));
    var multiplier = (100 + scale) / 100;
    var logo = document.querySelector(".custom-logo");

    if (!logo) {
      return;
    }

    if (logo.tagName === "A") {
      var imgInside = logo.querySelector("img");
      if (imgInside) {
        logo = imgInside;
      }
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
      siteLogoContainer.style.setProperty("display", "flex", "important");
      siteLogoContainer.style.setProperty(
        "justify-content",
        "center",
        "important",
      );
      siteLogoContainer.style.setProperty("align-items", "center", "important");
      siteLogoContainer.style.setProperty("width", "100%", "important");
    }

    // 2. Apply rules to the <img> element
    logo.style.setProperty("width", newWidth + "px", "important");
    logo.style.setProperty("height", newHeight + "px", "important");
    logo.style.setProperty("max-width", "100%", "important");
    logo.style.setProperty("display", "block", "important");
    logo.style.setProperty("margin", "0 auto", "important");

    // 3. Apply rules to the <a> wrapper
    logoWrapper.style.setProperty("display", "inline-flex", "important");
    logoWrapper.style.setProperty("justify-content", "center", "important");
    logoWrapper.style.setProperty("align-items", "center", "important");
    logoWrapper.style.setProperty("margin", "0 auto", "important");
    logoWrapper.style.setProperty("width", newWidth + "px", "important");
    logoWrapper.style.setProperty("height", newHeight + "px", "important");
    logoWrapper.style.setProperty("max-width", "100%", "important");

    // Smooth transition effect
    logo.style.transition = "width 0.15s ease-out, height 0.15s ease-out";
  }

  wp.customize("logo_resize", function (value) {
    value.bind(function (newScale) {
      applyLogoScaleToPreview(newScale);
    });
  });

  wp.customize.bind("preview-ready", function () {
    var current = wp.customize("logo_resize")();
    applyLogoScaleToPreview(current);
  });
})();

/**
 * Core Blueprint File: design-preview.js
 * Enqueued via: add_action('customize_preview_init', ...)
 */
(function () {
  // 1. Hook into the specific theme option setting ID
  wp.customize("_customize-input-logo_resize", function (value) {
    // 2. Bind a listener event to catch changes on-the-fly
    value.bind(function (newval) {
      const logoImage = document.querySelector(".site-logo img");

      // 3. Prevent null pointer crashes safely
      if (logoImage) {
        logoImage.style.width = newval + "px";
      }
    });
  });
})();
