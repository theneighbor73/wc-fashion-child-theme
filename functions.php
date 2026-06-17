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
add_action('template_redirect', 'custom_backend_validate_search');

function custom_backend_validate_search()
{
	// 1. Only run this check if the user is actively on a front-end search results page
	if (!is_admin() && is_search()) {

		// 2. Safely grab the current search parameter from the URL query track
		$raw_search_term = isset($_GET['s']) ? $_GET['s'] : '';

		// 3. Clean up leading/trailing empty spaces exactly like JavaScript's trim()
		$clean_search_term = trim($raw_search_term);
		$term_length = strlen($clean_search_term);

		// 4. THE SECURITY FILTER GATE: Reject if empty OR if length is less than 3 characters
		if ($clean_search_term === "" || $term_length < 3) {

			// Log confirmation to your error log file to verify the trigger is working
			// error_log("[Layer 2 Blocked] Term: '" . $clean_search_term . "' | Length: " . $term_length);

			// 5. Inject a secure error alert message into the active WooCommerce session queue
			// if (function_exists('wc_add_notice')) {
			// 	wc_add_notice('Search terms must be at least 3 characters long.', 'error');
			// }

			// Set a custom session flag containing our specific search error message
			// if (WC()->session) {
			// 	WC()->session->set('search_validation_error', 'Search terms must be at least 3 characters long.');
			// }

			if (WC()->session) {
				// 1. THE FIXED LAYER: Forcibly set a session cookie if the user is a guest!
				if (! WC()->session->has_session()) {
					WC()->session->set_customer_session_cookie(true);
				}

				// 2. Save your specific search error flag
				WC()->session->set('search_validation_error', 'Search terms must be at least 3 characters long.');
			}

			// 6. Forcefully pull the plug on the bad request and redirect them safely to the main shop page
			wp_safe_redirect(get_permalink(wc_get_page_id('shop')));
			exit; // Always exit after a redirect header to stop PHP execution instantly
		}
	}
}

/**
 * Enqueue Custom Validation and Toast Scripts Globally
 */
add_action('wp_enqueue_scripts', 'custom_print_shop_enqueue_scripts');

function custom_print_shop_enqueue_scripts()
{
	// wp_enqueue_script(
	// 	'print-shop-search-validation', // Can use wp_localize_script() by referencing this name later to pass data.
	// 	get_stylesheet_directory_uri() . '/js/search-validation.js',
	// 	[], // Add dependencies here if needed (e.g., array('jquery') if using jQuery)
	// 	'1.0.0', // Version
	// 	true     // Loads script in the footer so it doesn't block page rendering
	// );

	wp_enqueue_script(
		'print-shop-search-validation',
		get_stylesheet_directory_uri() . '/js/search-validation.js',
		[],
		'1.0.0',
		true
	);

	// BACKEND INTEGRATION: If there is a waiting WooCommerce error notice, 
	// pass it directly to our JavaScript toast system so it can render it!

	if (WC()->session && WC()->session->get('search_validation_error')) {
		// $notices = wc_get_notices('error');
		// // error_log('wc_notices: ' . print_r($notices, true));

		// // Grab the first error message string and strip any raw HTML tags from it
		// $error_message = strip_tags($notices[0]['notice']);


		// // Clear the notice from WooCommerce's internal template queue so it doesn't print twice
		// wc_clear_notices();

		$error_message = WC()->session->get('search_validation_error');
		error_log('new_error_message: ' . print_r($error_message, true));

		// Clear it immediately so it doesn't fire again on the next page refresh
		WC()->session->set('search_validation_error', null);

		// Send the error message string directly into JavaScript as a global variable
		wp_localize_script('print-shop-search-validation', 'backendToastError', array(
			'message' => $error_message
		));
	}
}

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
	// wp_enqueue_style(
	// 	'custom-print-shop-child-customizer-controls-style',
	// 	esc_url(get_stylesheet_directory_uri()) . '/css/editor-style.css',
	// 	[],
	// 	'20240613'
	// );
}

add_action(
	'wp_enqueue_scripts',
	'custom_print_shop_child_assets'
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
