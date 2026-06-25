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

  if (typeof wp === "undefined" || !wp.customize) {
    console.error("WP Customizer API not found.");
    return;
  }

  var api = wp.customize;
  const initialRatioConfig =
    typeof customPrintShopConfig !== "undefined"
      ? customPrintShopConfig?.logoRatios
      : {};
  const initialResize =
    typeof customPrintShopConfig !== "undefined"
      ? customPrintShopConfig?.defaultScale
      : 0;

  function getLogoConfigByKey(key) {
    return initialRatioConfig[key] ?? null;
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

  function getLogoResizeControl() {
    return (
      document.getElementById("customize-control-logo_resize") ||
      document.querySelector("[id^='customize-control-logo_resize']") ||
      document.querySelector("[id^='customize-control-logo-resize']") ||
      document.querySelector(".customize-control-logo_resize") ||
      document.querySelector(".customize-control-logo-resize")
    );
  }

  function decorateLogoResizeControl() {
    const control = getLogoResizeControl();

    if (!control) {
      console.error("Logo Resize Control not found.");
      return;
    }

    if (control.querySelector(".custom-logo-resize-footer")) {
      const footer = control.querySelector(".custom-logo-resize-footer");
      const resetButton = footer.querySelector(".logo-resize-reset");
      if (resetButton) {
        resetButton.addEventListener("click", function () {
          if (api("logo_resize")) {
            api("logo_resize").set(initialResize);
          }
        });
      }
    }
  }

  /**
   * Initial UI state.
   */

  api.bind("ready", function () {
    const currentRatio = api("logo_ratio")?.() ?? 0;
    const parsedCurrentRatio = parseInt(currentRatio, 10);
    const config = getLogoConfigByKey(parsedCurrentRatio);

    toggleLogoControl(parsedCurrentRatio);
    applyLogoCropRatio(config);
    decorateLogoResizeControl();
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
  });
})(jQuery);
