<?php

/**
 * Add Customizer registration for the logo ratio selector and logo width setting.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @todo Add a dedicated logo ratio selector with three fixed presets: 2:1, 3:1, and 4:1.
 *       After the user selects a ratio, open the media library/uploader with that ratio enforced.
 *       Keep `flex-width` and `flex-height` false so the logo must adhere to the chosen ratio.
 * @todo Preserve `logo_width` as a presentation control, but do not use it to override ratio enforcement.
 */

// custom function to see if valid ratio is being selected before showing the logo upload button
// We need this part to render the hide/unhide motion from server side but it already takes care of on client-side.

// function custom_print_shop_logo_button_appear($control)
// {
// 	$ratio_setting = $control->manager->get_setting('logo_ratio');

// 	if (!$ratio_setting) {
// 		return false;
// 	}

// 	return array_key_exists(
// 		absint($ratio_setting->value()),
// 		custom_print_shop_get_logo_ratios()
// 	);
// }


function custom_print_shop_logo_customize_register($wp_customize)
{
	// Logo Resizer additions
	// TODO: redesign requirement calls for strict custom logo ratio validation: 2:1, 3:1, or 4:1.
	//       The Customizer should let the user choose one of these ratios first, then open
	//       the media library/uploader with that ratio enforced.
	//       Keep current `logo_width` control as a presentation sizing helper, but do not
	//       use it to bypass the selected ratio constraint.
	// TODO: validation requirement from redesign: enforce the selected ratio on upload.
	// Recommendation: perform validation at upload-time in the Customizer preview using
	// `wp_handle_upload_prefilter` or by intercepting the Customizer upload flow. Inspect
	// the uploaded file with `getimagesize()` and reject uploads whose width/height
	// ratio does not match the selected preset.
	// Keep `logo_width` as a presentation control but do not use it to bypass validation.

	// We need this part to render the hide/unhide motion from server side but it already takes care of on client-side.

	// $logo_control = $wp_customize->get_control('custom_logo');

	// if ($logo_control) {
	// 	// Link it to our conditional evaluation function
	// 	$logo_control->active_callback = 'custom_print_shop_logo_button_appear';
	// }

	$wp_customize->add_setting('logo_ratio', array(
		'default'              => 0,
		'type'                 => 'theme_mod',
		'theme_supports'       => 'custom-logo',
		'transport'            => 'postMessage', // Use refresh if render on server side, postMessage if render on client side. 
		'sanitize_callback'    => 'absint',
		'sanitize_js_callback' => 'absint',
	));

	$ratios = custom_print_shop_get_logo_ratios();

	$wp_customize->add_control('logo_ratio', array(
		'label'       => esc_html__('Logo Image Ratio', 'custom-print-shop'),
		'description' => esc_html__('Horizontal logo is recommended for this theme.', 'custom-print-shop'),
		'section'     => 'title_tagline',
		'priority'    => 8,
		'type'        => 'select',
		'settings'    => 'logo_ratio',
		'choices' => [
			0 => '— Select a Ratio —',
		] + array_map(
			fn($ratio_data) => $ratio_data['css'],
			$ratios
		),
	));

	//Remove logo width control entirely.
	// $wp_customize->add_setting('logo_width', array(
	// 	'default'              => 25,
	// 	'type'                 => 'theme_mod',
	// 	'theme_supports'       => 'custom-logo',
	// 	'transport'            => 'refresh',
	// 	'sanitize_callback'    => 'absint',
	// 	'sanitize_js_callback' => 'absint',
	// ));

	// $wp_customize->add_control('logo_width', array(
	// 	'label'       => esc_html__('Logo Width', 'custom-print-shop'),
	// 	'section'     => 'title_tagline',
	// 	'priority'    => 9,
	// 	'type'        => 'number',
	// 	'settings'    => 'logo_width',
	// 	'input_attrs' => array(
	// 		'step'             => 5,
	// 		'min'              => 0,
	// 		'max'              => 100,
	// 		'aria-valuemin'    => 0,
	// 		'aria-valuemax'    => 100,
	// 		'aria-valuenow'    => 25,
	// 		'aria-orientation' => 'horizontal',
	// 	),
	// ));


	// Existing site title and tagline controls: Removed entirely from customizer
	// $wp_customize->add_setting('custom_print_shop_site_title',array(
	//    'default' => 'true',
	//    'sanitize_callback'	=> 'sanitize_text_field'
	// ));
	// $wp_customize->add_control('custom_print_shop_site_title',array(
	//    'type' => 'checkbox',
	//    'label' => __('Show / Hide Site Title','custom-print-shop'),
	//    'section' => 'title_tagline',
	// ));

	// $wp_customize->add_setting( 'custom_print_shop_site_title_color_setting', array(
	// 	'default' => '',
	// 	'sanitize_callback' => 'sanitize_hex_color'
	// ));
	// $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'custom_print_shop_site_title_color_setting', array(
	// 	'label' => __('Site Title Color Option', 'custom-print-shop'),
	// 	'section' => 'title_tagline',
	// 	'settings' => 'custom_print_shop_site_title_color_setting',
	// )));

	// $wp_customize->add_setting('custom_print_shop_site_title_fontsize',array(
	// 	'default'=> '',
	// 	'sanitize_callback'	=> 'custom_print_shop_sanitize_float'
	// ));
	// $wp_customize->add_control('custom_print_shop_site_title_fontsize',array(
	// 	'label'	=> __('Site Title Font Size','custom-print-shop'),
	// 	'input_attrs' => array(
	//         'step' => 1,
	// 		'min'  => 0,
	// 		'max'  => 100,
	//     ),
	// 	'section'=> 'title_tagline',
	// 	'type'=> 'number',
	// ));

	// $wp_customize->add_setting('custom_print_shop_site_tagline',array(
	//    'default' => 'false',
	//    'sanitize_callback'	=> 'sanitize_text_field'
	// ));
	// $wp_customize->add_control('custom_print_shop_site_tagline',array(
	//    'type' => 'checkbox',
	//    'label' => __('Show / Hide Site Description','custom-print-shop'), 
	//    'section' => 'title_tagline',
	// ));

	// $wp_customize->add_setting('custom_print_shop_site_description_fontsize',array(
	// 	'default'=> '',
	// 	'sanitize_callback'	=> 'custom_print_shop_sanitize_float'
	// ));
	// $wp_customize->add_control('custom_print_shop_site_description_fontsize',array(
	// 	'label'	=> __('Site Description Font Size','custom-print-shop'),
	// 	'input_attrs' => array(
	//         'step' => 1,
	// 		'min'  => 0,
	// 		'max'  => 100,
	//     ),
	// 	'section'=> 'title_tagline',
	// 	'type'=> 'number',
	// ));

	// $wp_customize->add_setting( 'custom_print_shop_tagline_color_setting', array(
	// 	'default' => '',
	// 	'sanitize_callback' => 'sanitize_hex_color'
	// ));
	// $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'custom_print_shop_tagline_color_setting', array(
	// 	'label' => __('Site Description Color Option', 'custom-print-shop'),
	// 	'section' => 'title_tagline',
	// 	'settings' => 'custom_print_shop_tagline_color_setting',
	// )));
}
add_action('customize_register', 'custom_print_shop_logo_customize_register');

/**
 * Add support for logo resizing by filtering `get_custom_logo`
 */
function custom_print_shop_customize_logo_resize($html)
{
	error_log('custom_print_shop_customize_logo_resize called: ' . $html);
	$size = get_theme_mod('logo_width', '25');
	$custom_logo_id = get_theme_mod('custom_logo');
	// set the short side minimum
	$min = 48;

	// don't use empty() because we can still use a 0
	if (is_numeric($size) && is_numeric($custom_logo_id)) {

		// we're looking for $img['width'] and $img['height'] of original image
		$logo = wp_get_attachment_metadata($custom_logo_id);
		if (! $logo) return $html;

		// get the logo support size
		$sizes = get_theme_support('custom-logo');
		error_log('custom-logo support sizes: ' . print_r($sizes, true));

		// Check for max height and width, default to image sizes if none set in theme
		$max['height'] = isset($sizes[0]['height']) ? $sizes[0]['height'] : $logo['height'];
		$max['width'] = isset($sizes[0]['width']) ? $sizes[0]['width'] : $logo['width'];

		// landscape or square
		if ($logo['width'] >= $logo['height']) {
			$output = custom_print_shop_min_max($logo['height'], $logo['width'], $max['height'], $max['width'], $size, $min);
			$img = array(
				'height'	=> $output['short'],
				'width'		=> $output['long']
			);
			// portrait
		} else if ($logo['width'] < $logo['height']) {
			$output = custom_print_shop_min_max($logo['width'], $logo['height'], $max['width'], $max['height'], $size, $min);
			$img = array(
				'height'	=> $output['long'],
				'width'		=> $output['short']
			);
		}

		// add the CSS
		$css = '
		<style>
		.custom-logo {
			height: ' . $img['height'] . 'px;
			max-height: ' . $max['height'] . 'px;
			max-width: ' . $max['width'] . 'px;
			width: ' . $img['width'] . 'px;
		}
		</style>';

		$html = $css . $html;
	}

	return $html;
}
add_filter('get_custom_logo', 'custom_print_shop_customize_logo_resize');

/* TODO: existing custom logo resizing logic adjusts CSS size.
   For the redesign, dimension validation should be added before the logo is accepted/uploaded,
   not only after outputting it. Consider `customize_register` or upload filter hooks.
*/

/* Helper function to determine the max size of the logo */
function custom_print_shop_min_max($short, $long, $short_max, $long_max, $percent, $min)
{
	$ratio = ($long / $short);
	$max['long'] = ($long_max >= $long) ? $long : $long_max;
	$max['short'] = ($short_max >= ($max['long'] / $ratio)) ? floor($max['long'] / $ratio) : $short_max;

	$ppp = ($max['short'] - $min) / 100;

	$size['short'] = round($min + ($percent * $ppp));
	$size['long'] = round($size['short'] / ($short / $long));

	return $size;
}

/**
 * JS handlers for Customizer Controls
 */
function custom_print_shop_customize_controls_js()
{
	wp_enqueue_script('custom-print-shop-customizer-controls', esc_url(get_template_directory_uri()) . '/inc/logo/js/customize-controls.js', array('jquery', 'customize-preview'), '201709071000', true);

	// to pass data from php to js, we can use wp_localize_script to create a global JS object with the data we need. 
	// In this case, we want to pass the allowed logo ratios to our customize-controls.js file 
	// so that we can use it to validate the user's selection and show/hide the logo upload button accordingly.

	$ratios = custom_print_shop_get_logo_ratios();

	wp_localize_script(
		'custom-print-shop-customizer-controls',
		'customPrintShopConfig',
		[
			// 'logoRatios' => array_map(
			// 	fn($ratios) => $ratios['css'],
			// 	$ratios
			// ),
			$ratios
		]
	);
}

add_action('customize_controls_enqueue_scripts', 'custom_print_shop_customize_controls_js');




/**
 * Adds CSS to the Customizer controls.
 */
function custom_print_shop_customize_css()
{
	wp_add_inline_style('customize-controls', '#customize-control-logo_size input[type=range] { width: 100%; }');
}
add_action('customize_controls_enqueue_scripts', 'custom_print_shop_customize_css');

/**
 * Testing function to remove logo_width theme mod
 */
function custom_print_shop_remove_theme_mod()
{
	if (isset($_GET['remove_logo_size']) && 'true' == $_GET['remove_logo_size']) {
		set_theme_mod('logo_width', '');
	}
}
add_action('wp_loaded', 'custom_print_shop_remove_theme_mod');
