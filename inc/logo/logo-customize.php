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

/*
	|--------------------------------------------------------------------------
	| Section: Logo ratio selector
	|--------------------------------------------------------------------------
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

        // Logo Resize additions

        $wp_customize->add_setting(
            'logo_resize',
            [
                'default' =>
                custom_print_shop_get_default_logo_resize(),

                'type' =>
                'theme_mod',

                'transport' =>
                'postMessage',

                'sanitize_callback' =>
                function ($value) {

                    return max(
                        -100,
                        min(
                            100,
                            intval($value)
                        )
                    );
                },
            ]
        );

        $wp_customize->add_control(
            'logo_resize',
            [
                'label' =>
                esc_html__(
                    'Logo Resize',
                    'custom-print-shop'
                ),

                'description' =>
                esc_html__(
                    '-100% to +100%',
                    'custom-print-shop'
                ),

                'section' =>
                'title_tagline',

                'priority' =>
                10,

                'type' =>
                'range',

                'input_attrs' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 5,
                ],
            ]
        );
    }
}

/**
 * JS handlers for Customizer Controls
 */
if (!function_exists('child_custom_print_shop_customize_controls_js')) {
    function child_custom_print_shop_customize_controls_js()
    {
        wp_enqueue_script('custom-print-shop-child-customizer-controls', esc_url(get_stylesheet_directory_uri()) . '/inc/logo/js/redesign-customizer-controls.js', array('jquery', 'customize-preview'), '201709071000', true);

            // Enqueue child customizer controls stylesheet so control decorations are loaded from an external file
            wp_enqueue_style(
                'custom-print-shop-child-customizer-controls-style',
                esc_url( get_stylesheet_directory_uri() ) . '/css/editor-style.css',
                [],
                '20240613'
            );

        // to pass data from php to js, we can use wp_localize_script to create a global JS object with the data we need. 
        // In this case, we want to pass the allowed logo ratios to our customize-controls.js file 
        // so that we can use it to validate the user's selection and show/hide the logo upload button accordingly.

        $ratios = custom_print_shop_get_logo_ratios();
        $cached_default_logo_resize = custom_print_shop_get_default_logo_resize();

        wp_localize_script(
            'custom-print-shop-child-customizer-controls',
            'customPrintShopConfig',
            [
                // 'logoRatios' => array_map(
                // 	fn($ratios) => $ratios['css'],
                // 	$ratios
                // ),
                'logoRatios' => $ratios,
                'defaultScale' =>
                $cached_default_logo_resize,
            ]
        );
    }
}

/**
 * Enqueue Customizer preview frame script.
 */
if (!function_exists('child_custom_print_shop_customize_preview_js')) {
    function child_custom_print_shop_customize_preview_js()
    {
        wp_enqueue_script(
            'custom-print-shop-child-customizer-preview',
            esc_url(get_stylesheet_directory_uri()) . '/inc/logo/js/redesign-customizer-preview.js',
            array('customize-preview'),
            '20240613',
            true
        );
    }
}
add_action('customize_preview_init', 'child_custom_print_shop_customize_preview_js');

function custom_print_shop_get_default_logo_resize()
{

    static $cached_default_logo_resize = null;

    if (null === $cached_default_logo_resize) {
        $cached_default_logo_resize = 0;
    }

    return $cached_default_logo_resize;
}

/**
 * Convert scale into multiplier.
 *
 * Example:
 * -30 → 0.7
 * 0 → 1
 * 50 → 1.5
 */
function custom_print_shop_logo_resize_to_multiplier(
    $scale
) {
    $scale = max(
        -100,
        min(
            100,
            absint($scale)
        )
    );

    return (
        100 + $scale
    ) / 100;
}

function child_custom_print_shop_customize_logo_resize($html)
{
    // $size = get_theme_mod('logo_width', '25');
    $scale =
        get_theme_mod(
            'logo_resize',
            0
        );

    $multiplier =
        (
            100 +
            $scale
        ) / 100;
    $custom_logo_id = get_theme_mod('custom_logo');
    // set the short side minimum

    // don't use empty() because we can still use a 0
    if (is_numeric($scale) && is_numeric($custom_logo_id)) {

        // we're looking for $img['width'] and $img['height'] of original image
        $logo = wp_get_attachment_metadata($custom_logo_id);
        if (! $logo) return $html;

        // get the logo support size
        // $sizes = get_theme_support('custom-logo');

        // // Check for max height and width, default to image sizes if none set in theme
        // $max['height'] = isset($sizes[0]['height']) ? $sizes[0]['height'] : $logo['height'];
        // $max['width'] = isset($sizes[0]['width']) ? $sizes[0]['width'] : $logo['width'];

        // // landscape or square
        // if ($logo['width'] >= $logo['height']) {
        //     $output = custom_print_shop_min_max($logo['height'], $logo['width'], $max['height'], $max['width'], $size, $min);
        //     $img = array(
        //         'height'    => $output['short'],
        //         'width'        => $output['long']
        //     );
        //     // portrait
        // } else if ($logo['width'] < $logo['height']) {
        //     $output = custom_print_shop_min_max($logo['width'], $logo['height'], $max['width'], $max['height'], $size, $min);
        //     $img = array(
        //         'height'    => $output['long'],
        //         'width'        => $output['short']
        //     );
        // }

        // add the CSS
        //max-height: ' . $max['height'] . 'px;
        //max-width: ' . $max['width'] . 'px;
        $css = '
			<style>
			.custom-logo {
				height: ' . $logo['height'] . 'px;
				width: ' . $logo['width'] . 'px;
				transform: scale(' . $multiplier . ');
				transform-origin: left center;
			}
			</style>';

        $html = $css . $html;
    }

    return $html;
}
add_filter('get_custom_logo', 'child_custom_print_shop_customize_logo_resize');
