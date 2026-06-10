<?php

require get_template_directory() . '/inc/tgm/class-tgm-plugin-activation.php';
/**
 * Recommended plugins.
 */
function custom_print_shop_register_recommended_plugins()
{
	$plugins = array(
		array(
			'name'             => __('Translate WordPress with GTranslate', 'custom-print-shop'),
			'slug'             => 'gtranslate',
			'source'           => '',
			'required'         => false,
			'force_activation' => false,
		),
		array(
			'name'             => __('YITH WooCommerce Wishlist', 'custom-print-shop'),
			'slug'             => 'yith-woocommerce-wishlist',
			'source'           => '',
			'required'         => false,
			'force_activation' => false,
		),
		array(
			'name'             => __('Currency Switcher for WooCommerce', 'custom-print-shop'),
			'slug'             => 'currency-switcher-woocommerce',
			'source'           => '',
			'required'         => false,
			'force_activation' => false,
		),
		array(
			'name'             => __('Woocommerce', 'custom-print-shop'),
			'slug'             => 'woocommerce',
			'source'           => '',
			'required'         => false,
			'force_activation' => false,
		)

	);
	$config = array();
	custom_print_shop_tgmpa($plugins, $config);
}
add_action('tgmpa_register', 'custom_print_shop_register_recommended_plugins');
