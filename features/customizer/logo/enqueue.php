<?php

/**
 * JS handlers for Customizer Controls
 */
function cpsc_customizer_logo_controls()
{
    cpsc_enqueue_script('cpsc_customizer_logo_controls_js', '/features/customizer/logo/customizer-controls.js', ['jquery', 'customize-preview']);

    wp_localize_script(
        'cpsc_customizer_logo_controls_js',
        'customPrintShopConfig',
        [
            'logoRatios' => CPSC_LOGO_RATIOS,
            'defaultScale' => CPSC_DEFAULT_LOGO_RESIZE,
        ]
    );

    cpsc_enqueue_style('cpsc_customizer_logo_controls_css', '/features/customizer/logo/customizer-style.css');
};

add_action(
    'customize_controls_enqueue_scripts',
    'cpsc_customizer_logo_controls'
);

/**
 * Enqueue Customizer preview frame script for logo customization.
 */
function cpsc_customize_logo_preview()
{
    cpsc_enqueue_script('cpsc_customizer_logo_preview_js', '/features/customizer/logo/customizer-preview.js', array('customize-preview'));
}

add_action('customize_preview_init', 'cpsc_customize_logo_preview');
