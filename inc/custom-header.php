<?php
/**
 * @package Custom Print Shop
 * @subpackage custom-print-shop
 * @since custom-print-shop 1.0
 * Setup the WordPress core custom header feature.
 *
 * @uses custom_print_shop_header_style()
*/

function custom_print_shop_custom_header_setup() {

	add_theme_support( 'custom-header', apply_filters( 'custom_print_shop_custom_header_args', array(
		'default-text-color' => 'fff',
		'header-text' 	     =>	false,
		'width'              => 1400,
		'height'             => 70,
		'flex-height'        => true,
	    'flex-width'         => true,
		'wp-head-callback'   => 'custom_print_shop_header_style',
	) ) );

}

add_action( 'after_setup_theme', 'custom_print_shop_custom_header_setup' );

if ( ! function_exists( 'custom_print_shop_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see custom_print_shop_custom_header_setup().
 */
add_action( 'wp_enqueue_scripts', 'custom_print_shop_header_style' );
function custom_print_shop_header_style() {
	//Check if user has defined any header image.
	if ( get_header_image() ) :
	$custom_print_shop_custom_css = "
        #header{
			background-image:url('".esc_url(get_header_image())."');
			background-position: center top;
			background-size: cover;
		}		
		";
	   	wp_add_inline_style( 'custom-print-shop-basic-style', $custom_print_shop_custom_css );
	endif;
}
endif; // custom_print_shop_header_style
