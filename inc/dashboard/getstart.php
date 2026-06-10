<?php
//about theme info
add_action( 'admin_menu', 'custom_print_shop_gettingstarted' );
function custom_print_shop_gettingstarted() {    	
	add_theme_page( esc_html__('Theme Demo Content', 'custom-print-shop'), esc_html__('Theme Demo Content', 'custom-print-shop'), 'edit_theme_options', 'custom_print_shop_guide', 'custom_print_shop_mostrar_guide');   
}

// Add a Custom CSS file to WP Admin Area
function custom_print_shop_admin_theme_style() {
   wp_enqueue_style('custom-print-shop-custom-admin-style', esc_url(get_template_directory_uri()) . '/inc/dashboard/getstart.css');
   wp_enqueue_script('tabs', esc_url(get_template_directory_uri()) . '/inc/dashboard/js/tab.js');

	// Admin notice code START
	wp_register_script('custom-print-shop-notice', esc_url(get_template_directory_uri()) . '/inc/dashboard/js/notice.js', array('jquery'), time(), true);
	wp_enqueue_script('custom-print-shop-notice');
	// Admin notice code END


}
add_action('admin_enqueue_scripts', 'custom_print_shop_admin_theme_style');

//guidline for about theme
function custom_print_shop_mostrar_guide() { 
	//custom function about theme customizer
	$return = add_query_arg( array()) ;
	$custom_print_shop_theme = wp_get_theme( 'custom-print-shop' );
?>

<div class="wrapper-info">  
	<div id="tc-header">
		<div class="tc-container main-header">
			<a class="tc-logo">
				<img role="img" src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/dashboard/images/logo.png" alt="" />
			</a>
			<span class="tc-header-action">
			<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customize', 'custom-print-shop'); ?></a>
			<a href="<?php echo esc_url( CUSTOM_PRINT_SHOP_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'custom-print-shop' ); ?></a>
			<a href="<?php echo esc_url( 'https://www.themescaliber.com/products/print-on-demand-wordpress-theme'); ?>" target="_blank"> <?php esc_html_e( 'Get Premium', 'custom-print-shop' ); ?></a>
			<a href="<?php echo esc_url( 'https://www.themescaliber.com/products/wordpress-theme-bundle' ); ?>" class="bundle_btn" target="_blank"> <?php esc_html_e( 'Bundle of 220+ Themes at $99', 'custom-print-shop' ); ?></a>
			</span>
		</div>
	</div>
	<div class="tc-container tab-sec">
		<div class="tc-tabs">
			<ul>
				<li class="tablinks home active" onclick="custom_print_shop_openCity(event, 'tc_demo')">
					<a href="#">
						<?php esc_html_e( 'Theme Demo Import', 'custom-print-shop' ); ?>
					</a>
				</li>
				<li class="tablinks" onclick="custom_print_shop_openCity(event, 'tc_index')">
					<a href="#">
						<?php esc_html_e( 'Free Theme Information', 'custom-print-shop' ); ?>
					</a>
				</li>
				<li class="tablinks" onclick="custom_print_shop_openCity(event, 'tc_pro')">
					<a href="#">
						<?php esc_html_e( 'Premium Theme Information', 'custom-print-shop' ); ?>
					</a>
				</li>
				<li class="tablinks" onclick="custom_print_shop_openCity(event, 'tc_create')">
					<a href="#">
						<?php esc_html_e( 'Theme Support', 'custom-print-shop' ); ?>
					</a>
				</li>
			</ul>
		</div><!-- END .tc-tabs -->
	</div>

	<div class="tc-container">
		<div class="tc-section">
			<div  id="tc_demo" class="tabcontent">
			<h2><?php esc_html_e( 'Welcome to Custom Print Shop', 'custom-print-shop' ); ?> <span class="version">Version: <?php echo esc_html($custom_print_shop_theme['Version']);?></span></h2>
				<hr>
				<div class="demo">
					<h4><?php esc_html_e( 'Click the "Run Importer" button below to load demo content for Custom Print Shop', 'custom-print-shop' ); ?></h4>
					<?php /* Demo Import */ require get_parent_theme_file_path( '/inc/dashboard/demo-importer.php' );?>
				</div>
			</div><!-- END .tc-section -->
		</div>
	</div>

	<div class="tc-container">
		<div class="tc-section">
			<div  id="tc_index" class="tabcontent">
				<h2><?php esc_html_e( 'Welcome to Custom Print Shop Theme', 'custom-print-shop' ); ?> <span class="version"><?php esc_html_e( 'Version:', 	'custom-print-shop' ); ?> <?php echo esc_html($custom_print_shop_theme['Version']);?></span></h2>
				<hr>
				<div class="info-link">
					<a href="<?php echo esc_url( CUSTOM_PRINT_SHOP_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'custom-print-shop' ); ?></a>
					<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customizing', 'custom-print-shop'); ?></a>
					<a class="get-pro" href="<?php echo esc_url( CUSTOM_PRINT_SHOP_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Get Pro', 'custom-print-shop'); ?></a>
				</div>
				<div class="col-tc-6">
					<img role="img" src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/dashboard/images/screenshot.png" alt="" />
				</div>
				<div class="col-tc-6">
					<P><?php esc_html_e( 'Custom Print Shop is a dynamic, responsive, and SEO-optimized theme designed for personalized printing businesses, custom merchandise stores, and print-on-demand services, incorporating relevant search terms such as online print store, printing services, custom products, print shop templates, graphic design services, apparel printing, personalized gifts, marketing materials, promotional printing, and digital product customization. Ideal for print shops, signage printing, poster printing, digital printing, t-shirt printing, business cards, mugs, banners, flyers, photo printing, embroidery services, vinyl printing, sublimation, brochure design, logo printing, and promotional product stores, this theme provides an engaging platform to showcase and sell customized products efficiently. Its modern, vibrant layout with customizable sections allows you to highlight apparel, stationery, corporate gifts, branded merchandise, and custom design options with a professional visual flow. Built for performance and user engagement, it integrates seamlessly with WooCommerce for product management, secure payments, and order tracking, Contact Form 7 for customer communication, YITH WooCommerce Wishlist for saving favorite products, and Fancy Product Designer for interactive product personalization. The theme ensures smooth navigation, fast load times, and cross-browser compatibility with a mobile-friendly design. Supporting multiple color schemes, flexible typography, creative product galleries, and SEO best practices, Custom Print Shop helps printing businesses present their services clearly, attract targeted traffic, and build a scalable online storefront for long-term growth.', 'custom-print-shop' ); ?></P>
				</div>
			</div>.
		</div><!-- END .tc-section -->
	</div>

	<div class="tc-container">
		<div class="tc-section">
			<div id="tc_pro" class="tabcontent">
				<h3><?php esc_html_e( 'Custom Print Shop Theme Information', 'custom-print-shop' ); ?></h3>
				<hr>
				<div class="info-link-pro">
					<a href="<?php echo esc_url( CUSTOM_PRINT_SHOP_BUY_NOW ); ?>" target="_blank"> <?php esc_html_e( 'Buy Now', 'custom-print-shop' ); ?></a>
					<a href="<?php echo esc_url( CUSTOM_PRINT_SHOP_LIVE_DEMO ); ?>" target="_blank"> <?php esc_html_e( 'Live Demo', 'custom-print-shop' ); ?></a>
					<a href="<?php echo esc_url( CUSTOM_PRINT_SHOP_PRO_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Pro Documentation', 'custom-print-shop' ); ?></a>
				</div>
				<div class="pro-image">
					<img role="img" src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/dashboard/images/resize.png" alt="" />
				</div>
			<div class="col-pro-5">
				<h4><?php esc_html_e( 'Custom Print Shop Pro Theme', 'custom-print-shop' ); ?></h4>
				<p><?php esc_html_e( 'The Print On Demand WordPress Theme is a top-tier solution for those venturing into custom print services with a focus on quality and innovation. Tailored for individuals and businesses seeking a sophisticated online presence in the print-on-demand niche, this premium theme offers a comprehensive platform for showcasing and selling personalized print products. Geared towards versatility, the Print On Demand WordPress Theme boasts an intuitive interface that simplifies the creation and customization of a diverse array of print items. From custom apparel to bespoke stationery, the theme caters to a broad range of printing needs, making it an ideal choice for entrepreneurs, designers, and print shop owners committed to excellence in the print-on-demand industry.', 'custom-print-shop' ); ?></P>		
			</div>
			<div class="col-pro-6">				
				<h4><?php esc_html_e( 'Theme Features', 'custom-print-shop' ); ?></h4>
				<ul>
					<li><?php esc_html_e( 'Theme Options using Customizer API', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Responsive design', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Favicon, Logo, title, and tagline customization', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Advanced Color options', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( '100+ Font Family Options', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Background Image Option', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Simple Menu Option', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Additional section for products', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Enable-Disable options on All sections', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Home Page setting for different sections', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Advance Slider with unlimited slides', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Partner Section', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Promotional Banner Section for Products', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Separate Newsletter Section', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Text and call to action button for each slide', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Pagination option', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Custom CSS option', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Translations Ready', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Custom Backgrounds, Colors, Headers, Logo & Menu', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Customizable Home Page', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Full-Width Template', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Footer Widgets & Editor Style', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Banner & Post Type Plugin Functionality', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Testimonial Post type', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Woo Commerce Compatible', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Multiple Inner Page Templates', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Product Sliders', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Testimonial Slider', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Contact page template', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Contact Widget', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Advance Social Media Feature', 'custom-print-shop' ); ?></li>
					<li><?php esc_html_e( 'Testimonial Listing With Shortcode', 'custom-print-shop' ); ?></li>
				</ul>					
			</div>	
		</div><!-- END .tc-section -->
	</div>

	<div class="tc-container">
		<div class="tc-section">
			<div id="tc_create" class="tabcontent">
				<div class="tab-cont">
					<h4><?php esc_html_e( 'Need Support?', 'custom-print-shop' ); ?></h4>				
					<div class="info-link-support">
						<P><?php esc_html_e( 'Our team is obliged to help you in every way possible whenever you face any type of difficulties and doubts.', 'custom-print-shop' ); ?></P>
						<a href="<?php echo esc_url( CUSTOM_PRINT_SHOP_SUPPORT ); ?>" target="_blank"> <?php esc_html_e( 'Support Forum', 'custom-print-shop' ); ?></a>
					</div>
				</div>
				<div class="tab-cont">	
					<h4><?php esc_html_e('Reviews', 'custom-print-shop'); ?></h4>				
					<div class="info-link-support">
						<P><?php esc_html_e( 'It is commendable to have such a theme inculcated with amazing features and robust functionalities. I feel grateful to recommend this theme to one and all.', 'custom-print-shop' ); ?></P>
						<a href="<?php echo esc_url( CUSTOM_PRINT_SHOP_REVIEW ); ?>" target="_blank"><?php esc_html_e('Reviews', 'custom-print-shop'); ?></a>
					</div>
				</div>

				<div class="tc-section large-section">
					<h2>Let‘s customize your website</h2>
					<p>There are many changes you can make to customize your website. Explore customization options and make it unique.</p>
					<div class="tc-buttons">
						<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>" class="tc-btn primary large-button"><?php esc_html_e('Start Customizing', 'custom-print-shop'); ?></a>
					</div><!-- END .tc-buttons -->
				</div>
			</div>
		</div><!-- END .tc-section -->
	</div>
</div>
<?php } ?>