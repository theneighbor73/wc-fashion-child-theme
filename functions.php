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
function custom_print_shop_child_assets()
{
	wp_enqueue_style(
		'parent-style',
		get_template_directory_uri() . '/style.css'
	);

	wp_enqueue_style(
		'child-style',
		get_stylesheet_uri(),
		['parent-style']
	);

	// For Customizer controls
	wp_enqueue_style(
		'custom-print-shop-child-customizer-controls-style',
		esc_url(get_stylesheet_directory_uri()) . '/css/editor-style.css',
		[],
		'20240613'
	);
}

add_action(
	'wp_enqueue_scripts',
	'custom_print_shop_child_assets'
);


/**
 * Load child logo customization module.
 */
$child_logo_customize =
	get_stylesheet_directory()
	. '/inc/logo/logo-customize.php';

if (file_exists($child_logo_customize)) {
	require_once $child_logo_customize;
}


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
