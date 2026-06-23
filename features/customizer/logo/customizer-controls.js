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
  const initialResize = customPrintShopConfig?.defaultScale ?? 0;

  function getLogoConfigByKey(key) {
    return initialRatioConfig[key] || null;
  }

  /**
   * Toggle logo uploader visibility helper function.
   *
   * @param {number} ratio
   */

  function toggleLogoControl(ratio) {
    const logoControl = api.control("custom_logo");
    if (!logoControl) {
      console.error("Logo control is not available.");
      return;
    }

    // Direct object property access is faster than hasOwnProperty.call
    if (typeof initialRatioConfig[ratio] !== "undefined") {
      logoControl.container.show();
    } else {
      logoControl.container.hide();
    }
  }

  function applyLogoCropRatio(config) {
    if (!config) return;

    const logoControl = api.control("custom_logo");
    if (!logoControl) return;

    // Update the control parameters container metadata
    if (logoControl.params) {
      logoControl.params.width = config.width;
      logoControl.params.height = config.height;
    }
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
          api("logo_resize").set(initialResize);
        }
      });
    }
  }

  /**
   * Initial UI state.
   */

  api.bind("ready", function () {
    // OPTIMIZATION: Do not wrap this in $(window).on('load').
    // api.bind('ready') guarantees the DOM controls are already built in memory.
    const currentRatio = api("logo_ratio")?.() || 0;
    const parsedCurrentRatio = parseInt(currentRatio, 10);
    const config = getLogoConfigByKey(parsedCurrentRatio);

    const currentScale = api("logo_resize")?.() || initialResize;
    const parsedCurrentScale = parseInt(currentScale, 10);

    toggleLogoControl(parsedCurrentRatio);
    applyLogoCropRatio(config);
    decorateLogoResizeControl();
    applyLogoScale(parsedCurrentScale);
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
  });
})(jQuery);
