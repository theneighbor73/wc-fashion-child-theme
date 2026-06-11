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
  const initialConfigs = customPrintShopConfig?.[0] || {};

  console.log("Custom Print Shop Config:", customPrintShopConfig?.[0]);

  console.log("Initial Configs:", initialConfigs);

  function getLogoConfigByKey(key) {
    // 1. Fallback to an empty object if the global config doesn't exist
    // const configSource = customPrintShopConfig || {};

    // // 2. Extract the actual configuration object from index 0
    // const ratiosData = configSource[0];

    // 3. Safety check: make sure the data object and the requested key exist
    if (initialConfigs && initialConfigs[key]) {
      return initialConfigs[key];
    }

    // Return null if a non-existent key (like 0) is passed
    return null;
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

    if (!logoControl) {
      return;
    }

    if (Object.prototype.hasOwnProperty.call(initialConfigs, ratio)) {
      logoControl.activate();
    } else {
      logoControl.deactivate();
    }
  }

  /**
   * Update crop metadata.
   */
  function applyLogoCropRatio(config) {
    // const config = ratios[ratio];

    if (!config) {
      return;
    }

    const logoControl = api.control("custom_logo");

    console.log("logoControl:", logoControl);

    console.log("chosen config for ratio:", config);

    if (logoControl && logoControl.params) {
      logoControl.params.width = config.width;

      logoControl.params.height = config.height;

      // logoControl.params.flex_width = false;

      // logoControl.params.flex_height = false;
    }

    console.log("Crop updated:", config);
  }

  /**
   * Initial UI state.
   */

  api.bind("ready", function () {
    $(window).on("load", function () {
      const currentRatio = parseInt(api("logo_ratio")(), 10);
      console.log("Current Ratio:", currentRatio);
      const config = getLogoConfigByKey(currentRatio);
      console.log("Current Config:", config);
      toggleLogoControl(currentRatio);
      applyLogoCropRatio(config);

      // uncomment it in the future if you want to allow users to control logo width. width slider or box input
      // if (!api.control("custom_logo").setting()) {
      //   $("#customize-control-logo_size").hide();
      // }
    });
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
