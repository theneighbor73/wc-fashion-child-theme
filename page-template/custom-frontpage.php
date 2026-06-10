<?php
/**
 * The template for displaying home page.
 *
 * Template Name: Custom Home Page
 *
 * @package Custom Print Shop
 */
get_header(); ?>

<main id="main" role="main">
  
  <?php if( get_theme_mod( 'custom_print_shop_slider_hide_show', false ) != '' || get_theme_mod( 'custom_print_shop_slider_responsive', false ) != '' ) {?>
    <div id="banner">
      <div class="banner-section">
        <div class="container">
          <div class="row">
            <div class="col-lg-5 col-md-5 text-center text-md-start wow zoomIn">
              <div class="inner_carousel">
                <?php if(get_theme_mod('custom_print_shop_designation_text') != '') {?>
                  <p class="small-title"><?php echo esc_html(get_theme_mod('custom_print_shop_designation_text')) ?></p>
                <?php }?>
                <?php if ( get_theme_mod( 'custom_print_shop_slider_title', true ) ) : ?>
                  <h1><?php echo esc_html(get_theme_mod('custom_print_shop_tagline_title')) ?></h1>
                <?php endif; ?>
                <?php if ( get_theme_mod( 'custom_print_shop_slider_content', true ) ) : ?>
                  <p><?php echo esc_html(get_theme_mod('custom_print_shop_content_text')) ?></p>
                <?php endif; ?>
                <?php if ( get_theme_mod('custom_print_shop_banner_button_label') != '' && get_theme_mod('custom_print_shop_top_button_url') != '' ) {?>
                  <div class ="slider-btn mt-md-4 mt-0">
                    <a href="<?php echo esc_url(get_theme_mod('custom_print_shop_top_button_url',false));?>"><?php echo esc_html(get_theme_mod('custom_print_shop_banner_button_label'));?><span class="screen-reader-text"></span><i class="fas fa-chevron-right ml-3"></i></a>
                  </div>
                <?php }?>
              </div>
            </div>
            <div class="col-lg-7 col-md-7">
              <!-- product for cat -->
              <?php if( get_theme_mod( 'custom_print_shop_show_hide_product',true) == 1) { ?>
                <div class="banner-next">
                  <div class="slider-for">
                    <?php if ( class_exists( 'WooCommerce' ) ) {
                    $args = array( 
                      'post_type' => 'product',
                      'product_cat' => get_theme_mod('custom_print_shop_product_category'),
                      'order' => 'ASC'
                    );
                    $loop = new WP_Query( $args );
                    while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
                        <div class="slider-nav-box-inner-sec pt-md-0 pt-4 d-flex justify-content-center">
                          <div class="row">
                            <div class="col-lg-6 col-md-6 text-center">
                              <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.esc_url(woocommerce_placeholder_img_src()).'" />'; ?>
                            </div>
                            <div class="col-lg-6 col-md-6 align-self-center">
                              <div class="slider-price">
                                <h2><a href="<?php echo esc_url(get_permalink( $loop->post->ID )); ?>"><?php the_title(); ?></a></h2>
                                <div class="slider-price-inner-content">
                                  <p class="product-rating<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></p>
                                  <?php if ( get_theme_mod('custom_print_shop_product_banner_button_label') ) {?>
                                    <div class ="slider-btn1 mt-md-4 mt-0">
                                      <a href="<?php the_permalink(); ?>"><?php echo esc_html(get_theme_mod('custom_print_shop_product_banner_button_label',__('Buy Now','custom-print-shop')));?><span class="screen-reader-text"><?php echo esc_html('Buy Now','custom-print-shop');?></span><i class="fas fa-shopping-basket ml-3"></i></a>
                                    </div>
                                  <?php }?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                  <?php } ?>
                  </div> 
                </div>
              <?php }?>
              <!-- end -->
              <!-- product cat nav-->
              <?php if( get_theme_mod( 'custom_print_shop_show_hide_product',true) == 1) { ?>
                <div class="slider-nav d-flex pt-4" >
                  <?php if ( class_exists( 'WooCommerce' ) ) {
                    $args = array(
                      'post_type' => 'product',
                      'product_cat' => get_theme_mod('custom_print_shop_product_category'),
                      'order' => 'ASC'
                    );
                    $loop = new WP_Query( $args );
                    while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
                      <div class="slider-nav-box-inner d-flex align-items-center">
                       <div class="slider-nav-image">
                          <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.esc_url(woocommerce_placeholder_img_src()).'" />'; ?>
                        </div>
                      </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                  <?php } ?>
                </div>
              <?php }?>
              <!-- end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php }?>

  <?php do_action( 'custom_print_shop_before_featured_section' ); ?>

<!-- featured product section -->
<?php if( get_theme_mod( 'custom_print_shop_featured_hide_show', false ) != '' ) {?>
  <section id="featured-section" class="pb-5">
    <div class="container">
      <div class="featured-box-section pt-4">
        <div class="top-text pt-3 px-lg-0 px-md-0 px-2">
          <?php if (get_theme_mod('custom_print_shop_section_title') != '') { ?>
            <h2 class="text-center wow bounceInUp"><?php echo esc_html(get_theme_mod('custom_print_shop_section_title')); ?></h2>
          <?php } ?>
          <?php if (get_theme_mod('custom_print_shop_section_text') != '') { ?>
            <p class="featured-para text-center"><?php echo esc_html(get_theme_mod('custom_print_shop_section_text')); ?></p>
          <?php } ?>
        </div>
        <div class="tab">
          <?php
            $custom_print_shop_category_post = get_theme_mod('custom_print_shop_featured_number', '');
            for ( $custom_print_shop_j = 1; $custom_print_shop_j <= $custom_print_shop_category_post; $custom_print_shop_j++ ){ ?>
            <button class="tablinks" onclick="custom_print_shop_project_tab(event, '<?php $main_id = get_theme_mod('custom_print_shop_featured_text'.$custom_print_shop_j); $tab_id = str_replace(' ', '-', $main_id); echo $tab_id; ?> ')">
            <?php echo esc_html(get_theme_mod('custom_print_shop_featured_text'.$custom_print_shop_j)); ?></button>
          <?php }?>
        </div>
        <?php for ( $custom_print_shop_j = 1; $custom_print_shop_j <= $custom_print_shop_category_post; $custom_print_shop_j++ ){ ?>
          <div id="<?php $main_id = get_theme_mod('custom_print_shop_featured_text'.$custom_print_shop_j); $tab_id = str_replace(' ', '-', $main_id); echo $tab_id; ?>"class="tabcontent mt-3">
            <div class="row">
              <?php if ( class_exists( 'WooCommerce' ) ) {
                $args = array( 
                  'post_type' => 'product',
                  'product_cat' => get_theme_mod('custom_print_shop_product_category'.$custom_print_shop_j),
                  'order' => 'ASC',
                );
                $loop = new WP_Query( $args );
                 while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
                  <div class="col-lg-3 col-md-6 product-image position-relative wow zoomIn">
                    <div class="product-box mb-4">
                      <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.esc_url(woocommerce_placeholder_img_src()).'" />'; ?>
                       <?php if( $product->is_type( 'simple' ) ){ woocommerce_template_loop_rating( $loop->post, $product ); } ?>
                       <h3><a href="<?php echo esc_url(get_permalink( $loop->post->ID )); ?>"><?php the_title(); ?></a></h3>
                       <p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></p>
                    </div>
                    <div class="pro-icons">
                      <div class="d-flex align-items-center justify-content-center gap-3">
                        <?php if(defined('YITH_WCWL')){ ?>
                          <a class="wishlist_view" href="<?php echo YITH_WCWL()->get_wishlist_url(); ?>" title="<?php esc_attr_e('Wishlist','custom-print-shop'); ?>"><i class="far fa-heart"></i>
                          </a>
                        <?php }?> 
                        <?php if( $product->is_type( 'simple' ) ){ woocommerce_template_loop_add_to_cart( $loop->post, $product ); } ?>
                      </div>
                    </div> 
                  </div> 
                <?php endwhile;
                  wp_reset_postdata();
                ?>
              <?php }?>
              </div>
          </div>
        <?php }?>
      </div>
    </div>
  </section>

  <?php do_action( 'custom_print_shop_after_featured_section' ); ?>

  <div id="content-ma" class="py-5">
  	<div class="container">
    	<?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; // end of the loop. ?>
  	</div>
  </div>

<?php }?>
</main>

<?php get_footer(); ?>