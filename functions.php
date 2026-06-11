<?php

/**
 * Child Theme Initialization and Asset Enqueue
 */

function custom_print_shop_child_assets()
{
	// Load the parent theme's stylesheet
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

	// Load the child theme's stylesheet right after it
	wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));
}
add_action('wp_enqueue_scripts', 'custom_print_shop_child_assets');

/**
 * Override Parent Theme Logo Customization
 * 
 * Dequeue parent theme's logo files and register child theme's custom implementations.
 * This allows the child theme to completely redesign the logo customization & render workflow.
 */

// Intercept parent theme's template loading to prevent duplicate logo files
// The parent's inc/customizer.php loads inc/logo/logo-width.php directly,
// which would cause a fatal "Cannot redeclare function" error.
// This filter redirects it to the child theme's version instead.

add_filter('wp_template_loader', function ($template) {
	if (
		strpos($template, '/inc/logo/logo-width.php') !== false &&
		strpos($template, get_template_directory()) === 0
	) {
		// Redirect parent's logo-width.php to child's logo-customize.php
		return get_stylesheet_directory() . '/inc/logo/logo-customize.php';
	}
	return $template;
}, 10, 1);

// Also intercept locate_template to handle template location lookups

add_filter('locate_template', function ($template, $template_names, $load) {
	if (is_array($template_names)) {
		foreach ($template_names as $name) {
			if (strpos($name, 'inc/logo/logo-width.php') !== false) {
				return get_stylesheet_directory() . '/inc/logo/logo-customize.php';
			}
		}
	} elseif (strpos($template_names, 'inc/logo/logo-width.php') !== false) {
		return get_stylesheet_directory() . '/inc/logo/logo-customize.php';
	}
	return $template;
}, 10, 3);

// Load the child theme's custom logo customization file
// Require child logo customization implementation (safe include above)
$child_logo_customize = get_stylesheet_directory() . '/inc/logo/logo-customize.php';
if (file_exists($child_logo_customize)) {
	require_once $child_logo_customize;
}

/**
 * After setup: remove parent logo hooks and register child implementations.
 */
function custom_print_shop_child_setup_overrides()
{
	// Remove parent's handlers if they exist
	remove_action('customize_register', 'custom_print_shop_logo_customize_register');
	remove_action('customize_controls_enqueue_scripts', 'custom_print_shop_customize_controls_js');
	remove_filter('get_custom_logo', 'custom_print_shop_customize_logo_resize');
	remove_action('customize_controls_enqueue_scripts', 'custom_print_shop_customize_css');
	remove_action('wp_loaded', 'custom_print_shop_remove_theme_mod');

	// Add child implementations (they were added by the required file using child_* names)
	if (function_exists('child_custom_print_shop_logo_customize_register')) {
		add_action('customize_register', 'child_custom_print_shop_logo_customize_register');
	}
	if (function_exists('child_custom_print_shop_customize_controls_js')) {
		add_action('customize_controls_enqueue_scripts', 'child_custom_print_shop_customize_controls_js');
	}
}
add_action('after_setup_theme', 'custom_print_shop_child_setup_overrides', 20);

// Dequeue parent theme's logo width script

function custom_print_shop_child_dequeue_parent_logo_assets()
{
	// Remove the parent theme's customize controls script that was enqueued
	// This must be done at the right priority to ensure it runs after parent enqueues
	wp_dequeue_script('custom-print-shop-customizer-controls');
}
add_action('wp_enqueue_scripts', 'custom_print_shop_child_dequeue_parent_logo_assets', 99);
add_action('customize_controls_enqueue_scripts', 'custom_print_shop_child_dequeue_parent_logo_assets', 99);

// Override parent theme's customize_register hook with child theme version
// Remove parent's logo registration and use child theme's instead

function custom_print_shop_child_remove_parent_logo_customize($wp_customize)
{
	// The parent theme registers via customize_register hook with default priority 10
	// We need to remove that hook and use our child version instead
	remove_action('customize_register', 'custom_print_shop_logo_customize_register', 10);
}
add_action('customize_register', 'custom_print_shop_child_remove_parent_logo_customize', 5);
