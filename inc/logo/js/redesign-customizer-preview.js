(function () {
  "use strict";

  if (typeof wp === "undefined" || !wp.customize) {
    return;
  }

  //   function applyLogoScaleToPreview(scale) {
  //     scale = Math.max(-100, Math.min(100, parseInt(scale, 10) || 0));
  //     var multiplier = (100 + scale) / 100;
  //     var logo = document.querySelector(".custom-logo");

  //     if (!logo) {
  //       return;
  //     }

  //     // console.log("Logo:", logo);

  //     logo.style.transform = "scale(" + multiplier + ")";
  //     logo.style.transformOrigin = "left center";
  //     logo.style.transition = "transform 0.15s ease-out";
  //   }

  function applyLogoScaleToPreview(scale) {
    scale = Math.max(-100, Math.min(100, parseInt(scale, 10) || 0));
    var multiplier = (100 + scale) / 100;
    var logo = document.querySelector(".custom-logo");

    if (!logo) {
      return;
    }

    // Fallback chain: try natural size -> try attribute size -> try bounding rect -> default to a standard 150px
    var baseWidth = logo.naturalWidth || logo.width;
    var baseHeight = logo.naturalHeight || logo.height;

    // Calculate the new layout sizes just like the PHP side does
    var newWidth = Math.round(baseWidth * multiplier);
    var newHeight = Math.round(baseHeight * multiplier);

    // Apply the exact width/height to match our PHP layout changes
    logo.style.width = newWidth + "px";
    logo.style.height = newHeight + "px";
    logo.style.maxWidth = "100%";

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
