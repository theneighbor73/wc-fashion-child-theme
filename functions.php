<?php

/**
 * Child Theme Bootstrap
 *
 * Responsibilities:
 * - Load parent + child styles
 * - Load child logo customization module
 * - Disable parent logo customization behavior
 * - Register child logo customization behavior
 */

/**
 * Load parent and child styles.
 */

// We add a filter to modify the search query before it hits the database

function custom_print_shop_child_assets()
{
	$parent_style_handle = 'custom-print-shop-basic-style';
	$parent_style_src = esc_url(get_template_directory_uri()) . '/style.css';
	$parent_style_ver = filemtime(get_template_directory() . '/style.css');

	if (wp_style_is($parent_style_handle, 'registered')) {
		wp_deregister_style($parent_style_handle);
	}

	wp_register_style(
		$parent_style_handle,
		$parent_style_src,
		array(),
		$parent_style_ver
	);
	wp_enqueue_style($parent_style_handle);

	wp_enqueue_style(
		'child-style',
		get_stylesheet_uri(),
		array($parent_style_handle)
	);

	wp_enqueue_style(
		'woocommerce-elements-style',
		get_theme_file_uri('css/woocommerce.css'),
		[],
		'20260621'
	);
}

add_action(
	'wp_enqueue_scripts',
	'custom_print_shop_child_assets',
	20
);

/**
 * Enqueue styles specifically for the WordPress Customizer controls sidebar.
 */
function child_theme_enqueue_customizer_controls_styles()
{
	wp_enqueue_style(
		'custom-print-shop-child-customizer-controls-style',
		esc_url(get_stylesheet_directory_uri()) . '/css/customizer-style.css',
		[],
		'20240613'
	);
}
// This hook fires only when the Customizer admin panel layout is being built
add_action('customize_controls_print_styles', 'child_theme_enqueue_customizer_controls_styles');

/**
 * Load customization files.
 */

$all_customization_file_directories = array(
	'inc/logo/logo-customize.php',
	'inc/product-search-validation/backend.php',
	'inc/checkout-page/backend.php'
);

foreach ($all_customization_file_directories as $load_directory) {
	$ready_directory = get_theme_file_path($load_directory);
	if (file_exists($ready_directory)) {
		require_once $ready_directory;
	}
};

/**
 * Replace parent logo customization with child implementation.
 */
function custom_print_shop_child_setup_overrides()
{
	/*
	|--------------------------------------------------------------------------
	| Disable parent logo system
	|--------------------------------------------------------------------------
	*/

	remove_action(
		'customize_register',
		'custom_print_shop_logo_customize_register'
	);

	remove_action(
		'customize_controls_enqueue_scripts',
		'custom_print_shop_customize_controls_js'
	);

	remove_filter(
		'get_custom_logo',
		'custom_print_shop_customize_logo_resize'
	);


	/*
	|--------------------------------------------------------------------------
	| Register child logo system
	|--------------------------------------------------------------------------
	*/

	if (
		function_exists(
			'child_custom_print_shop_logo_customize_register'
		)
	) {
		add_action(
			'customize_register',
			'child_custom_print_shop_logo_customize_register'
		);
	}

	if (
		function_exists(
			'child_custom_print_shop_customize_controls_js'
		)
	) {
		add_action(
			'customize_controls_enqueue_scripts',
			'child_custom_print_shop_customize_controls_js'
		);
	}
}

add_action(
	'after_setup_theme',
	'custom_print_shop_child_setup_overrides',
	20
);
