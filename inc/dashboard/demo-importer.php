<div class="theme-import">
	<?php 
        // Check if the demo import has been completed
        $custom_print_shop_demo_import_completed = get_option('custom_print_shop_demo_import_completed', false);

        // If the demo import is completed, display the "View Site" button
        if ($custom_print_shop_demo_import_completed) {
        echo '<p class="notice-text">' . esc_html__('Your demo import has been completed successfully.', 'custom-print-shop') . '</p>';
        echo '<span><a href="' . esc_url(home_url()) . '"  class= "run-import view-site" target="_blank">' . esc_html__('VIEW SITE', 'custom-print-shop') . '</a></span>';
        }

		// POST and update the customizer and other related data
        if (isset($_POST['submit'])) {

            // Check if woocommerce is installed and activated
            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                // Install the plugin if it doesn't exist
                $custom_print_shop_plugin_slug = 'woocommerce';
                $custom_print_shop_plugin_file = 'woocommerce/woocommerce.php';

                // Check if plugin is installed
                $custom_print_shop_installed_plugins = get_plugins();
                if (!isset($custom_print_shop_installed_plugins[$custom_print_shop_plugin_file])) {
                    include_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
                    include_once(ABSPATH . 'wp-admin/includes/file.php');
                    include_once(ABSPATH . 'wp-admin/includes/misc.php');
                    include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

                    // Install the plugin
                    $custom_print_shop_upgrader = new Plugin_Upgrader();
                    $custom_print_shop_upgrader->install('https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip');
                }
                // Activate the plugin
                activate_plugin($custom_print_shop_plugin_file);
            }

            // Check if  GTranslate is installed and activated
            if (!is_plugin_active('gtranslate/gtranslate.php')) {
                // Install the plugin if it doesn't exist
                $custom_print_shop_plugin_slug = 'gtranslate';
                $custom_print_shop_plugin_file = 'gtranslate/gtranslate.php';

                // Check if plugin is installed
                $custom_print_shop_installed_plugins = get_plugins();
                if (!isset($custom_print_shop_installed_plugins[$custom_print_shop_plugin_file])) {
                    include_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
                    include_once(ABSPATH . 'wp-admin/includes/file.php');
                    include_once(ABSPATH . 'wp-admin/includes/misc.php');
                    include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

                    // Install the plugin
                    $custom_print_shop_upgrader = new Plugin_Upgrader();
                    $custom_print_shop_upgrader->install('https://downloads.wordpress.org/plugin/gtranslate.latest-stable.zip');
                }
                // Activate the plugin
                activate_plugin($custom_print_shop_plugin_file);
            }

            // ------- Create Nav Menu --------
            $custom_print_shop_menuname = 'Main Menus';
            $custom_print_shop_bpmenulocation = 'primary';
            $custom_print_shop_menu_exists = wp_get_nav_menu_object($custom_print_shop_menuname);

            if (!$custom_print_shop_menu_exists) {
                $custom_print_shop_menu_id = wp_create_nav_menu($custom_print_shop_menuname);

                // Create Home Page
                $custom_print_shop_home_title = 'Home';
                $custom_print_shop_home = array(
                    'post_type' => 'page',
                    'post_title' => $custom_print_shop_home_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'home'
                );
                $custom_print_shop_home_id = wp_insert_post($custom_print_shop_home);
                // Assign Home Page Template
                add_post_meta($custom_print_shop_home_id, '_wp_page_template', 'page-template/custom-frontpage.php');
                // Update options to set Home Page as the front page
                update_option('page_on_front', $custom_print_shop_home_id);
                update_option('show_on_front', 'page');
                // Add Home Page to Menu
                wp_update_nav_menu_item($custom_print_shop_menu_id, 0, array(
                    'menu-item-title' => __('Home', 'custom-print-shop'),
                    'menu-item-classes' => 'home',
                    'menu-item-url' => home_url('/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $custom_print_shop_home_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // Create Shop Page with Dummy Content
                $custom_print_shop_pages_title = 'Shop';
                $custom_print_shop_pages_content = '
                <p>Explore all the pages we have on our website. Find information about our services, company, and more.</p>

                 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br> 

                  All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
                $custom_print_shop_pages = array(
                    'post_type' => 'page',
                    'post_title' => $custom_print_shop_pages_title,
                    'post_content' => $custom_print_shop_pages_content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'pages'
                );
                $custom_print_shop_pages_id = wp_insert_post($custom_print_shop_pages);
                // Add Shop Page to Menu
                wp_update_nav_menu_item($custom_print_shop_menu_id, 0, array(
                    'menu-item-title' => __('Shop', 'custom-print-shop'),
                    'menu-item-classes' => 'pages',
                    'menu-item-url' => home_url('/pages/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $custom_print_shop_pages_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // Create Categories Page with Dummy Content
                $custom_print_shop_about_title = 'Categories';
                $custom_print_shop_about_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...<br>

                         Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br> 

                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the middle of text.<br> 

                            All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
                $custom_print_shop_about = array(
                    'post_type' => 'page',
                    'post_title' => $custom_print_shop_about_title,
                    'post_content' => $custom_print_shop_about_content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'about-us'
                );
                $custom_print_shop_about_id = wp_insert_post($custom_print_shop_about);
                // Add Categories Page to Menu
                wp_update_nav_menu_item($custom_print_shop_menu_id, 0, array(
                    'menu-item-title' => __('Categories', 'custom-print-shop'),
                    'menu-item-classes' => 'about-us',
                    'menu-item-url' => home_url('/about-us/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $custom_print_shop_about_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // ===== CREATE BLOG PAGE =====
                $custom_print_shop_blog_page = get_page_by_path('blog');
                if (!$custom_print_shop_blog_page) {
                    $custom_print_shop_blog_page_id = wp_insert_post(array(
                        'post_type'   => 'page',
                        'post_title'  => 'Blog',
                        'post_status' => 'publish',
                        'post_name'   => 'blog',
                    ));

                } else {
                    $custom_print_shop_blog_page_id = $custom_print_shop_blog_page->ID;
                }
                update_option('page_for_posts', $custom_print_shop_blog_page_id);

                wp_update_nav_menu_item($custom_print_shop_menu_id, 0, array(
                    'menu-item-title'     => __('Blog', 'custom-print-shop'),
                    'menu-item-object-id' => $custom_print_shop_blog_page_id,
                    'menu-item-object'    => 'page',
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));

                // Create Sales Page with Dummy Content
                $custom_print_shop_pages_title = 'Sales';
                $custom_print_shop_pages_content = '
                <p>Explore all the pages we have on our website. Find information about our services, company, and more.</p>

                 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br> 

                  All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
                $custom_print_shop_pages = array(
                    'post_type' => 'page',
                    'post_title' => $custom_print_shop_pages_title,
                    'post_content' => $custom_print_shop_pages_content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'pages'
                );
                $custom_print_shop_pages_id = wp_insert_post($custom_print_shop_pages);
                // Add Sales Page to Menu
                wp_update_nav_menu_item($custom_print_shop_menu_id, 0, array(
                    'menu-item-title' => __('Sales', 'custom-print-shop'),
                    'menu-item-classes' => 'pages',
                    'menu-item-url' => home_url('/pages/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $custom_print_shop_pages_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                

                // Set the menu location if it's not already set
                if (!has_nav_menu($custom_print_shop_bpmenulocation)) {
                    $custom_print_shop_locations = get_theme_mod('nav_menu_locations'); // Use 'nav_menu_locations' to get locations array
                    if (empty($custom_print_shop_locations)) {
                        $custom_print_shop_locations = array();
                    }
                    $custom_print_shop_locations[$custom_print_shop_bpmenulocation] = $custom_print_shop_menu_id;
                    set_theme_mod('nav_menu_locations', $custom_print_shop_locations);
                }
                
        }     

            //Header
            set_theme_mod( 'custom_print_shop_topbar_hide_show', true ); 
            set_theme_mod( 'custom_print_shop_topbar_faqs_text', 'FAQs' ); 
            set_theme_mod( 'custom_print_shop_topbar_faqs_url', '#' ); 
            set_theme_mod( 'custom_print_shop_topbar_contact_text', 'Contact' ); 
            set_theme_mod( 'custom_print_shop_topbar_contact_url', '#' ); 
            set_theme_mod( 'custom_print_shop_topbar_shipping_text', 'Free Shipping Above $100 Total Order' ); 
            set_theme_mod( 'custom_print_shop_wishlist_url', '#' ); 

            //Banner Section
            set_theme_mod( 'custom_print_shop_slider_hide_show', true ); 
            set_theme_mod( 'custom_print_shop_banner_image', esc_url( get_template_directory_uri().'/images/Slider-Bg.png'));
            set_theme_mod( 'custom_print_shop_designation_text', 'Customize your T-shirt with Lorem ipsum' );
            set_theme_mod( 'custom_print_shop_tagline_title', 'Turn Your Ideas Into Reality' );
            set_theme_mod( 'custom_print_shop_content_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' );
            set_theme_mod( 'custom_print_shop_banner_button_label', 'Customize Product' );
            set_theme_mod( 'custom_print_shop_top_button_url', '#' );
            set_theme_mod( 'custom_print_shop_product_banner_button_label', 'Buy Now' );
            set_theme_mod( 'custom_print_shop_product_category', 'productcategory1' );

            // Define product category names, product titles, and tags
            $custom_print_shop_category_names = array('productcategory1', 'productcategory2', 'productcategory3', 'productcategory4', 'productcategory5');
            $custom_print_shop_title_array = array(
                array("T-shirt Design", "Mug Design", "Mobile Cover Design", "Cap Design", "Mug Design 2"),
                array("T-shirt Design", "Mug Design", "Mobile Cover Design", "Cap Design", "Mug Design 2"),
                array("T-shirt Design", "Mug Design", "Mobile Cover Design", "Cap Design", "Mug Design 2"),
                array("T-shirt Design", "Mug Design", "Mobile Cover Design", "Cap Design", "Mug Design 2"),
                array("T-shirt Design", "Mug Design", "Mobile Cover Design", "Cap Design", "Mug Design 2")
            );

            foreach ($custom_print_shop_category_names as $custom_print_shop_index => $custom_print_shop_category_name) {
                // Create or retrieve the product category term ID
                $custom_print_shop_term = term_exists($custom_print_shop_category_name, 'product_cat');

                // If the term doesn't exist, create it
                if (!$custom_print_shop_term) {
                    $custom_print_shop_term = wp_insert_term($custom_print_shop_category_name, 'product_cat');
                    if (is_wp_error($custom_print_shop_term)) {
                        error_log('Error creating category: ' . $custom_print_shop_term->get_error_message());
                        continue;
                    }
                }

                // Retrieve the category ID
                $custom_print_shop_term_id = is_array($custom_print_shop_term) ? $custom_print_shop_term['term_id'] : $custom_print_shop_term;

                // Loop to create 5 products for each category
                for ($custom_print_shop_i = 0; $custom_print_shop_i < 5; $custom_print_shop_i++) {
                    // Create product content
                    $custom_print_shop_title = $custom_print_shop_title_array[$custom_print_shop_index][$custom_print_shop_i];
                    $custom_print_shop_content = 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout';

                    // Create product post object
                    $custom_print_shop_my_post = array(
                        'post_title'    => wp_strip_all_tags($custom_print_shop_title),
                        'post_content'  => $custom_print_shop_content,
                        'post_status'   => 'publish',
                        'post_type'     => 'product', // Post type set to 'product'
                    );

                    // Insert the product into the database
                    $custom_print_shop_post_id = wp_insert_post($custom_print_shop_my_post);

                    // Check for errors in product creation
                    if (is_wp_error($custom_print_shop_post_id)) {
                        error_log('Error creating product: ' . $custom_print_shop_post_id->get_error_message());
                        continue;
                    }

                    // Assign the category to the product
                    wp_set_object_terms($custom_print_shop_post_id, array($custom_print_shop_term_id), 'product_cat');

                    // Set product type as 'simple'
                    wp_set_object_terms($custom_print_shop_post_id, 'simple', 'product_type');

                    // Set product price and sale price
                    update_post_meta($custom_print_shop_post_id, '_regular_price', 15.00);
                    update_post_meta($custom_print_shop_post_id, '_sale_price', 10.00);
                    update_post_meta($custom_print_shop_post_id, '_price', 10.00);

                    // Handle the featured image
                    $custom_print_shop_image_url = get_template_directory_uri() . '/images/banner-product' . ($custom_print_shop_i + 1) . '.png';
                    $custom_print_shop_image_id = media_sideload_image($custom_print_shop_image_url, $custom_print_shop_post_id, null, 'id');

                    // Check for errors in image downloading
                    if (is_wp_error($custom_print_shop_image_id)) {
                        error_log('Error downloading image: ' . $custom_print_shop_image_id->get_error_message());
                        continue;
                    }

                    // Assign the featured image to the product
                    set_post_thumbnail($custom_print_shop_post_id, $custom_print_shop_image_id);
                }
            }
            
            //Featured Product Section
            set_theme_mod( 'custom_print_shop_featured_hide_show', true ); 
            set_theme_mod( 'custom_print_shop_section_title', 'Featured Product' ); 
            set_theme_mod( 'custom_print_shop_section_text', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.' ); 
            set_theme_mod('custom_print_shop_featured_number', '6');

            $custom_print_shop_tab_text_array = array("New", "T-Shirts", "Caps", "Mugs", "Covers", "Hoodie");
            $custom_print_shop_category_names = array("postcategory1", "postcategory2", "postcategory3", "postcategory4", "postcategory5", "postcategory6");
            $custom_print_shop_title_array = array(
                array("Mug Design", "Cap Design", "Mobile Cover Design", "T-shirt Design"),
                array("Mug Design", "Cap Design", "Mobile Cover Design", "T-shirt Design"),
                array("Mug Design", "Cap Design", "Mobile Cover Design", "T-shirt Design"),
                array("Mug Design", "Cap Design", "Mobile Cover Design", "T-shirt Design"),
                array("Mug Design", "Cap Design", "Mobile Cover Design", "T-shirt Design"),
                array("Mug Design", "Cap Design", "Mobile Cover Design", "T-shirt Design")
            );
            
            for ($custom_print_shop_tab_index = 1; $custom_print_shop_tab_index <= 6; $custom_print_shop_tab_index++) {
                $theme_mod_key = 'custom_print_shop_featured_text' . $custom_print_shop_tab_index;
                $theme_mod_value = $custom_print_shop_tab_text_array[$custom_print_shop_tab_index - 1];
                set_theme_mod($theme_mod_key, $theme_mod_value);
            
                // Set the category for this tab
                $current_category = $custom_print_shop_category_names[$custom_print_shop_tab_index - 1];
                set_theme_mod('custom_print_shop_product_category' . $custom_print_shop_tab_index, $current_category);
            
                // Create or retrieve the product category term ID
                $custom_print_shop_term = term_exists($current_category, 'product_cat');
                if ($custom_print_shop_term === 0 || $custom_print_shop_term === null || $custom_print_shop_term === false) {
                    $custom_print_shop_term = wp_insert_term($current_category, 'product_cat');
                }
            
                if (is_wp_error($custom_print_shop_term)) {
                    error_log('Error creating category: ' . $custom_print_shop_term->get_error_message());
                    continue;
                }
            
                // Get the term ID
                $custom_print_shop_term_id = is_array($custom_print_shop_term) ? $custom_print_shop_term['term_id'] : $custom_print_shop_term;
            
                for ($custom_print_shop_i = 0; $custom_print_shop_i < 4; $custom_print_shop_i++) {
                    $custom_print_shop_title = $custom_print_shop_title_array[$custom_print_shop_tab_index - 1][$custom_print_shop_i];
                    $custom_print_shop_content = 'Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.';
            
                    // Create a new WooCommerce product
                    $custom_print_shop_product = new WC_Product_Simple();
                    $custom_print_shop_product->set_name($custom_print_shop_title);
                    $custom_print_shop_product->set_description($custom_print_shop_content);
                    $custom_print_shop_product->set_status('publish');
                    $custom_print_shop_product->set_catalog_visibility('visible');
                    $custom_print_shop_product->set_regular_price(15.00); // Set the regular price
                    $custom_print_shop_product->set_sale_price(10.00);    // Set the sale price
                    $custom_print_shop_product->set_category_ids(array((int)$custom_print_shop_term_id));
            
                    // Save the product to get its ID
                    $custom_print_shop_product_id = $custom_print_shop_product->save();
            
                    if (is_wp_error($custom_print_shop_product_id)) {
                        error_log('Error creating product: ' . $custom_print_shop_product_id->get_error_message());
                        continue;
                    }
            
                    // Handle the featured image using media_sideload_image
                    $custom_print_shop_image_url = get_template_directory_uri() . '/images/product' . ($custom_print_shop_i + 1) . '.png';
                    $custom_print_shop_image_id = media_sideload_image($custom_print_shop_image_url, $custom_print_shop_product_id, null, 'id');
            
                    if (is_wp_error($custom_print_shop_image_id)) {
                        error_log('Error downloading image: ' . $custom_print_shop_image_id->get_error_message());
                        continue;
                    }
            
                    // Assign featured image to product
                    set_post_thumbnail($custom_print_shop_product_id, $custom_print_shop_image_id);
                }
            }                       
            
            //Copyright Text
            set_theme_mod( 'custom_print_shop_footer_copy', 'By ThemesCaliber' ); 

            // Set the demo import completion flag
    		update_option('custom_print_shop_demo_import_completed', true);
    		// Display success message and "View Site" button
    		echo '<p class="notice-text">' . esc_html__('Your demo import has been completed successfully.', 'custom-print-shop') . '</p>';
    		echo '<span><a href="' . esc_url(home_url()) . '" class="run-import site-btn" target="_blank">' . esc_html__('VIEW SITE', 'custom-print-shop') . '</a></span>';

        }
    ?>
  
    <p class="note"><?php esc_html_e( 'Please Note: If your website is live and already contains data, we recommend creating a backup first. Running this importer will replace your current settings with the custom values from the demo.', 'custom-print-shop' ); ?></p>
        <form action="<?php echo esc_url(home_url()); ?>/wp-admin/themes.php?page=custom_print_shop_guide" method="POST" onsubmit="return validate(this);">
        <?php if (!get_option('custom_print_shop_demo_import_completed')) : ?>
            <button type="submit" name="submit" class="run-import">
                <?php esc_html_e('Run Importer','custom-print-shop'); ?>
                <span id="spinner" style="display: none;">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/spinner.gif" alt="Loading..." style="width:34px; height:34px; vertical-align: middle;" />
                </span>
            </button>
        <?php endif; ?>
        </form>
        <script type="text/javascript">
            function validate(valid) {
                if(confirm("Do you really want to import the theme demo content?")){
                    document.getElementById('spinner').style.display = 'inline-block';
                }
                else {
                    return false;
                }
            }
        </script>
    </div>