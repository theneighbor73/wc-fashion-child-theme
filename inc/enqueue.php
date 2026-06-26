<?php

/**
 * Load parent and child styles.
 */

function cpsc_initial_styling()
{
    /*
	|--------------------------------------------------------------------------
	| Initial styling
	|--------------------------------------------------------------------------
	*/

    if (wp_style_is(PARENT_STYLE_HANDLE, 'registered')) {
        // Deregister then re-register parent theme to rewrite into memory safely
        wp_deregister_style(PARENT_STYLE_HANDLE);
    }

    wp_register_style(
        PARENT_STYLE_HANDLE,
        esc_url(get_template_directory_uri()) . '/style.css',
        array()
    );

    wp_enqueue_style(PARENT_STYLE_HANDLE);

    // Load child style
    wp_enqueue_style(
        CHILD_STYLE_HANDLE,
        get_stylesheet_uri(),
        array(PARENT_STYLE_HANDLE)
    );
}

add_action(
    'wp_enqueue_scripts',
    'cpsc_initial_styling',
    20
);

/**
 * Global assets
 */

function cpsc_enqueue_global_assets()
{
    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    */

    cpsc_enqueue_style('cpsc_header_css', 'assets/css/header.css');

    cpsc_enqueue_style('cpsc_toast_css', 'assets/css/toast.css');

    cpsc_enqueue_style('cpsc_wc_base_css', '/woocommerce/css/base.css');

    if (is_checkout()) {
        cpsc_enqueue_style('cpsc_checkout_css', '/woocommerce/css/pages/checkout.css');
    }
    if (is_cart()) {
        cpsc_enqueue_style('cpsc_checkout_css', '/woocommerce/css/pages/cart.css');
    }

    /*
    |--------------------------------------------------------------------------
    | Scripts
    |--------------------------------------------------------------------------
    */

    cpsc_enqueue_script('global_toast_js', 'assets/js/toast.js');
}

add_action(
    'wp_enqueue_scripts',
    'cpsc_enqueue_global_assets'
);
