/**
 * File customize-controls.js.
 *
 * Brings logo resizing technology to the Customizer.
 *
 * Contains handlers to change Customizer controls.
 * Added a listener to the existing logo control to show/hide the logo upload after user select logo ratio on the client-side.
 */
(function ($) {
  "use strict";

  var api = wp.customize;
  const initialRatioConfig = customPrintShopConfig?.logoRatios || {};
  // const initialResize = customPrintShopConfig?.defaultScale ?? 0;

  // console.log("Initial Configs:", initialRatioConfig);

  function getLogoConfigByKey(key) {
    return initialRatioConfig[key] || null;
  }

  // const config = getLogoConfigByKey(20);

  // if (config) {
  //   console.log(config.width); // Output: 200
  //   console.log(config.height); // Output: 100
  //   console.log(config.css); // Output: "2 : 1"
  // }

  //on browser becomes
  //   <script id="custom-print-shop-customizer-controls-js-extra">
  // var customPrintShopConfig = {"logoRatios":{"20":"2 : 1","30":"3 : 1","40":"4 : 1"}};
  // //# sourceURL=custom-print-shop-customizer-controls-js-extra
  // </script>

  /**
   * Toggle logo uploader visibility helper function.
   *
   * @param {number} ratio
   */

  function toggleLogoControl(ratio) {
    const logoControl = api.control("custom_logo");
    if (!logoControl) return;

    // Direct object property access is faster than hasOwnProperty.call
    if (initialRatioConfig[ratio] !== undefined) {
      logoControl.activate();
    } else {
      logoControl.deactivate();
    }
  }

  /**
   * Update crop metadata.
   */
  // function applyLogoCropRatio(config) {
  //   // const config = ratios[ratio];

  //   // console.log("Config for ratio:", config);

  //   if (!config) {
  //     return;
  //   }

  //   const logoControl = api.control("custom_logo");

  //   // console.log("logoControl:", logoControl);

  //   // console.log("chosen config for ratio:", config);

  //   if (logoControl && logoControl.params) {
  //     logoControl.params.width = config.width;

  //     logoControl.params.height = config.height;

  //     // logoControl.params.flex_width = false;

  //     // logoControl.params.flex_height = false;
  //   }

  //   // console.log("Crop updated:", config);
  // }

  function applyLogoCropRatio(config) {
    if (!config) return;

    const logoControl = api.control("custom_logo");
    if (!logoControl) return;

    // Update the control parameters container metadata
    if (logoControl.params) {
      logoControl.params.width = config.width;
      logoControl.params.height = config.height;
    }

    // Without this, the cropping modal will use old cached data from memory when clicked!
    // if (logoControl.uploader && logoControl.uploader.uploader) {
    //   console.log("uploader: ", logoControl.uploader.uploader);
    //   logoControl.uploader.uploader.param("crop_width", config.width);
    //   logoControl.uploader.uploader.param("crop_height", config.height);
    // }
  }

  /**
   * Apply visual scale.
   *
   * @param {number} scale
   */
  function applyLogoScale(scale) {
    scale = Math.max(-100, Math.min(100, scale));

    const multiplier = (100 + scale) / 100;

    $(".custom-logo").css({
      transform: `scale(${multiplier})`,

      transformOrigin: "left center",
    });
  }

  function getLogoResizeControl() {
    return (
      document.getElementById("customize-control-logo_resize") ||
      document.querySelector("[id^='customize-control-logo_resize']") ||
      document.querySelector("[id^='customize-control-logo-resize']") ||
      document.querySelector(".customize-control-logo_resize") ||
      document.querySelector(".customize-control-logo-resize")
    );
  }

  function decorateLogoResizeControl(attempts) {
    attempts = typeof attempts === "number" ? attempts : 0;
    const control = getLogoResizeControl();

    if (!control) {
      if (attempts < 4) {
        window.setTimeout(function () {
          decorateLogoResizeControl(attempts + 1);
        }, 120);
      }
      return;
    }

    if (control.querySelector(".custom-logo-resize-footer")) {
      return;
    }

    const footer = document.createElement("div");
    footer.className = "custom-logo-resize-footer";
    footer.innerHTML =
      '<div class="logo-resize-markers"><span>-100</span><span>0</span><span>+100</span></div>' +
      '<button type="button" class="button logo-resize-reset">Reset</button>';

    control.appendChild(footer);

    const resetButton = footer.querySelector(".logo-resize-reset");
    if (resetButton) {
      resetButton.addEventListener("click", function () {
        if (api("logo_resize")) {
          api("logo_resize").set(0);
        }
      });
    }

    // Styling moved to css/editor-style.css and enqueued from PHP; no inline styles injected here.
  }

  /**
   * Initial UI state.
   */

  api.bind("ready", function () {
    // OPTIMIZATION: Do not wrap this in $(window).on('load').
    // api.bind('ready') guarantees the DOM controls are already built in memory.
    const currentRatio = parseInt(api("logo_ratio")(), 10);
    const config = getLogoConfigByKey(currentRatio);
    const currentScale = parseInt(api("logo_resize")(), 10);

    toggleLogoControl(currentRatio);
    applyLogoCropRatio(config);
    decorateLogoResizeControl();

    applyLogoScale(currentScale);
    // uncomment it in the future if you want to allow users to control logo width. width slider or box input
    // if (!api.control("custom_logo").setting()) {
    //   $("#customize-control-logo_size").hide();
    // }
  });

  /**
   * Logo ratio listener: listen to the change of logo ratio.
   */

  api("logo_ratio", function (value) {
    value.bind(function (newRatio) {
      newRatio = parseInt(newRatio, 10);

      const config = getLogoConfigByKey(newRatio);

      toggleLogoControl(newRatio);
      applyLogoCropRatio(config);
    });

    api(
      "logo_resize",

      function (value) {
        value.bind(function (newScale) {
          newScale = parseInt(newScale, 10);

          applyLogoScale(newScale);
        });
      },
    );

    /**
     * Existing logo listener: it works with the $("#customize-control-logo_size") to show/hide the logo width control when user upload/remove logo.
     * But we already don't have this feature no more.
     */

    // function toggleLogoWidthControl(logoId) {
    //   const widthControl = api.control("logo_width");

    //   if (!widthControl) {
    //     return;
    //   }

    //   if (!logoId) {
    //     $("#customize-control-logo_size").hide();

    //     widthControl.deactivate();

    //     return;
    //   }

    //   $("#customize-control-logo_size").show();

    //   widthControl.activate();

    //   widthControl.setting(25);

    //   widthControl.setting.preview();
    // }

    // api("custom_logo", function (value) {
    //   value.bind(function (newLogo) {
    //     toggleLogoWidthControl(newLogo);
    //   });
    // });
  });
})(jQuery);
