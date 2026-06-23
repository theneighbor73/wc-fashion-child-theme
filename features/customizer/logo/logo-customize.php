<?php

/**

 * Add Customizer registration for the logo ratio selector and logo width setting.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

function cpsc_logo_customize_register($wp_customize)
{

    $logo_control = $wp_customize->get_control('custom_logo');

    if ($logo_control) {
        // Add a clear disclaimer description
        $logo_control->description = esc_html__('Please upload only JPG or PNG images. SVG files will not resize correctly.', 'custom-print-shop');
    }

    $logo_setting = $wp_customize->get_setting('custom_logo');
    if ($logo_setting) {
        $logo_setting->transport = 'refresh'; // Fixes the removal freeze!
    }

    /*
	|--------------------------------------------------------------------------
	| Section: Logo ratio selector
	|--------------------------------------------------------------------------
    */

    $wp_customize->add_setting('logo_ratio', array(
        'default'              => 0,
        'type'                 => 'theme_mod',
        'theme_supports'       => 'custom-logo',
        'transport'            => 'postMessage', // Use refresh if render on server side, postMessage if render on client side. 
        'sanitize_callback'    => function ($input) {
            // Force input to an integer for strict type checking
            $int_input = intval($input);

            // Check if the integer matches any key inside CPSC_LOGO_RATIOS
            if (array_key_exists($int_input, CPSC_LOGO_RATIOS)) {
                return $int_input;
            } else return 0;
        },
    ));

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
            CPSC_LOGO_RATIOS
        ),
    ));

    /*
	|--------------------------------------------------------------------------
	| Section: Logo resize slider
	|--------------------------------------------------------------------------
    */

    $wp_customize->add_setting(
        'logo_resize',
        [
            'default' =>
            CPSC_DEFAULT_LOGO_RESIZE,

            'type' =>
            'theme_mod',

            'transport' =>
            'postMessage',

            'sanitize_callback' =>
            function ($value) {
                return max(
                    -100,
                    min(100, intval($value))
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
                '-100% to +100%. Preview on the right only shows desktop view. For mobile view, please check on mobile.',
                'custom-print-shop'
            ),

            'section' =>
            'title_tagline',

            'active_callback' => 'has_custom_logo',

            'priority' =>
            10,

            'type' =>
            'range',
            'settings'    => 'logo_resize',
            'input_attrs' => [
                'min' => -100,
                'max' => 100,
                'step' => 5,
            ],
        ]
    );
}

function cpsc_customize_logo_resize($html)
{
    if (empty($html)) {
        return $html;
    }

    $scale = get_theme_mod('logo_resize', CPSC_DEFAULT_LOGO_RESIZE);

    $custom_logo_id = get_theme_mod('custom_logo');

    // don't use empty() because we can still use a 0
    if (is_numeric($scale) && is_numeric($custom_logo_id)) {

        // we're looking for $img['width'] and $img['height'] of original image
        $logo = wp_get_attachment_metadata($custom_logo_id);
        if (! $logo) return $html;

        if ($logo && isset($logo['width'], $logo['height'])) {

            // Calculate the actual new dimensions instead of using CSS transform
            $multiplier = (100 + $scale) / 100;
            $new_width  = round($logo['width'] * $multiplier);
            $new_height = round($logo['height'] * $multiplier);

            // It affects the desktop display
            $css = '<style>
                    .site-logo {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        width: 100%;
                    }
                    .custom-logo-link {
                        display: inline-flex;
                        justify-content: center;
                        align-items: center;
                        margin-left: auto;
                        margin-right: auto;
                    }
                    .custom-logo {
                        height: ' . $new_height . 'px;
                        width: ' . $new_width . 'px;
                        max-width: 100%;
                        display: block;
                        margin: 0 auto;
                    }
                </style>';


            $html .= $css;
        }
    }

    return $html;
}
add_filter('get_custom_logo', 'cpsc_customize_logo_resize');
