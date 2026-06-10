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
