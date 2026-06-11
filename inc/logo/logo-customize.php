<?php

/**
 * Add Customizer registration for the logo ratio selector and logo width setting.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @todo Add a dedicated logo ratio selector with three fixed presets: 2:1, 3:1, and 4:1.
 *       After the user selects a ratio, open the media library/uploader with that ratio enforced.
 *       Keep `flex-width` and `flex-height` false so the logo must adhere to the chosen ratio.
 * @todo Preserve `logo_width` as a presentation control, but do not use it to override ratio enforcement.
 */

// function custom_print_shop_get_logo_ratios()
// {
//     return [
//         20 => [
//             'label'  => '2:1',
//             'width'  => 200,
//             'height' => 100,
//             'css'    => '2 : 1',
//         ],

//         30 => [
//             'label'  => '3:1',
//             'width'  => 300,
//             'height' => 100,
//             'css'    => '3 : 1',
//         ],

//         40 => [
//             'label'  => '4:1',
//             'width'  => 400,
//             'height' => 100,
//             'css'    => '4 : 1',
//         ],

//         53 => [
//             'label'  => '5:3',
//             'width'  => 250,
//             'height' => 150,
//             'css'    => '5 : 3',
//         ],
//     ];
// }

function custom_print_shop_get_logo_ratios()
{
    static $cached_ratios = null;

    if (null === $cached_ratios) {
        $cached_ratios = [
            20 => [
                'label'  => '2:1',
                'width'  => 200,
                'height' => 100,
                'css'    => '2 : 1',
            ],
            30 => [
                'label'  => '3:1',
                'width'  => 300,
                'height' => 100,
                'css'    => '3 : 1',
            ],
            40 => [
                'label'  => '4:1',
                'width'  => 400,
                'height' => 100,
                'css'    => '4 : 1',
            ],
            53 => [
                'label'  => '5:3',
                'width'  => 250,
                'height' => 150,
                'css'    => '5 : 3',
            ],
        ];
    }

    return $cached_ratios;
}

// custom function to see if valid ratio is being selected before showing the logo upload button
// We need this part to render the hide/unhide motion from server side but it already takes care of on client-side.

// function custom_print_shop_logo_button_appear($control)
// {
// 	$ratio_setting = $control->manager->get_setting('logo_ratio');

// 	if (!$ratio_setting) {
// 		return false;
// 	}

// 	return array_key_exists(
// 		absint($ratio_setting->value()),
// 		custom_print_shop_get_logo_ratios()
// 	);
// }


if (!function_exists('child_custom_print_shop_logo_customize_register')) {
    function child_custom_print_shop_logo_customize_register($wp_customize)
    {
        // error_log('Running child_custom_print_shop_logo_customize_register');
        // Logo Resizer additions
        // TODO: redesign requirement calls for strict custom logo ratio validation: 2:1, 3:1, or 4:1.
        //       The Customizer should let the user choose one of these ratios first, then open
        //       the media library/uploader with that ratio enforced.
        //       Keep current `logo_width` control as a presentation sizing helper, but do not
        //       use it to bypass the selected ratio constraint.
        // TODO: validation requirement from redesign: enforce the selected ratio on upload.
        // Recommendation: perform validation at upload-time in the Customizer preview using
        // `wp_handle_upload_prefilter` or by intercepting the Customizer upload flow. Inspect
        // the uploaded file with `getimagesize()` and reject uploads whose width/height
        // ratio does not match the selected preset.
        // Keep `logo_width` as a presentation control but do not use it to bypass validation.

        // We need this part to render the hide/unhide motion from server side but it already takes care of on client-side.

        // $logo_control = $wp_customize->get_control('custom_logo');

        // if ($logo_control) {
        // 	// Link it to our conditional evaluation function
        // 	$logo_control->active_callback = 'custom_print_shop_logo_button_appear';
        // }

        $wp_customize->add_setting('logo_ratio', array(
            'default'              => 0,
            'type'                 => 'theme_mod',
            'theme_supports'       => 'custom-logo',
            'transport'            => 'postMessage', // Use refresh if render on server side, postMessage if render on client side. 
            'sanitize_callback'    => 'absint',
            'sanitize_js_callback' => 'absint',
        ));

        $ratios = custom_print_shop_get_logo_ratios();

        $wp_customize->add_control('logo_ratio', array(
            'label'       => esc_html__('Logo Image Ratio', 'custom-print-shop'),
            'description' => esc_html__('Horizontal logo is recommended for this theme. Please upload a logo that matches your selected ratio.', 'custom-print-shop'),
            'section'     => 'title_tagline',
            'priority'    => 8,
            'type'        => 'select',
            'settings'    => 'logo_ratio',
            'choices' => [
                0 => '— Select a Ratio —',
            ] + array_map(
                fn($ratio_data) => $ratio_data['css'],
                $ratios
            ),
        ));
    }
}
// if (!has_action('customize_register', 'child_custom_print_shop_logo_customize_register')) {
//     add_action('customize_register', 'child_custom_print_shop_logo_customize_register');
// }

/**
 * JS handlers for Customizer Controls
 */
if (!function_exists('child_custom_print_shop_customize_controls_js')) {
    function child_custom_print_shop_customize_controls_js()
    {
        wp_enqueue_script('custom-print-shop-child-customizer-controls', esc_url(get_stylesheet_directory_uri()) . '/inc/logo/js/redesign-customizer-controls.js', array('jquery', 'customize-preview'), '201709071000', true);

        // to pass data from php to js, we can use wp_localize_script to create a global JS object with the data we need. 
        // In this case, we want to pass the allowed logo ratios to our customize-controls.js file 
        // so that we can use it to validate the user's selection and show/hide the logo upload button accordingly.

        $ratios = custom_print_shop_get_logo_ratios();

        wp_localize_script(
            'custom-print-shop-child-customizer-controls',
            'customPrintShopConfig',
            [
                // 'logoRatios' => array_map(
                // 	fn($ratios) => $ratios['css'],
                // 	$ratios
                // ),
                $ratios
            ]
        );
    }
}
// if (!has_action('customize_controls_enqueue_scripts', 'child_custom_print_shop_customize_controls_js')) {
//     add_action('customize_controls_enqueue_scripts', 'child_custom_print_shop_customize_controls_js');
// }
