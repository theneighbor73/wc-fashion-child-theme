<?php
/**
 * The template part for displaying slider
 *
 * @package Custom Print Shop
 * @subpackage custom_print_shop
 * @since Custom Print Shop 1.0
 */
?>
<?php 
  $custom_print_shop_grid_columns = get_theme_mod('custom_print_shop_grid_columns', '3');
  if ($custom_print_shop_grid_columns == '3') {
    $custom_print_shop_column_class = 'col-lg-4 col-md-4';
  } elseif ($custom_print_shop_grid_columns == '4') {
    $custom_print_shop_column_class = 'col-lg-3 col-md-6';
  } 
?>
<div class="<?php echo esc_attr($custom_print_shop_column_class); ?>">
  <article id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>
    <div class="services-box mb-5"> 
      <div class="grid-post-box">   
        <?php if(has_post_thumbnail() && get_theme_mod( 'custom_print_shop_grid_post_image_hide',true) != '') { ?>
          <div class="service-image">
            <a href="<?php echo esc_url( get_permalink() ); ?>">
              <?php  the_post_thumbnail(); ?>
              <span class="screen-reader-text"><?php the_title(); ?></span>
            </a>
          </div>
        <?php }?>
        <div class="lower-box">
          <h2 class="pt-0"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h2>          
          <?php if( get_theme_mod( 'custom_print_shop_grid_post_date_hide', true) != '' || get_theme_mod( 'custom_print_shop_grid_post_comment_hide', true) != '' || get_theme_mod( 'custom_print_shop_grid_post_author_hide', true) != '' || get_theme_mod( 'custom_print_shop_grid_post_time_hide', true) != '') { ?>
            <div class="metabox py-1 px-2 mb-3">
              <?php if( get_theme_mod( 'custom_print_shop_grid_post_date_hide', true) != '') { ?>
                <span class="entry-date me-2"><i class="<?php echo esc_attr(get_theme_mod('custom_print_shop_grid_post_date_icon', 'far fa-calendar-alt me-1')); ?>"></i><?php echo esc_html( get_the_date() ); ?></span>
              <?php } ?>

              <?php if( get_theme_mod( 'custom_print_shop_grid_post_author_hide', true) != '') { ?>
                <span class="entry-author me-2"><i class="<?php echo esc_attr(get_theme_mod('custom_print_shop_grid_post_author_icon', 'fas fa-user me-1')); ?>"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?><span class="screen-reader-text"><?php the_author(); ?></span></a></span>
              <?php } ?>

              <?php if( get_theme_mod( 'custom_print_shop_grid_post_comment_hide', true) != '') { ?>
                <span class="entry-comments me-2"><i class="<?php echo esc_attr(get_theme_mod('custom_print_shop_grid_post_comment_icon', 'fas fa-comments me-1')); ?>"></i> <?php comments_number( __('0 Comments','custom-print-shop'), __('1 Comment','custom-print-shop'), __('% Comments','custom-print-shop') ); ?></span>
              <?php } ?>

              <?php if( get_theme_mod( 'custom_print_shop_grid_post_time_hide', true) != '') { ?>
                <span class="entry-time"><i class="<?php echo esc_attr(get_theme_mod('custom_print_shop_grid_post_time_icon', 'fas fa-clock me-1')); ?>"></i> <?php echo esc_html( get_the_time() ); ?></span>
              <?php } ?>
            </div>
          <?php } ?> 
          <?php if(get_theme_mod('custom_print_shop_grid_post_content') == 'Full Content'){ ?>
            <?php the_content(); ?>
          <?php }  
          if(get_theme_mod('custom_print_shop_grid_post_content', 'Excerpt Content') == 'Excerpt Content'){ ?>        
            <?php if(get_the_excerpt()) { ?>
              <p><?php $custom_print_shop_excerpt = get_the_excerpt(); echo esc_html( custom_print_shop_string_limit_words( $custom_print_shop_excerpt, esc_attr(get_theme_mod('custom_print_shop_grid_post_excerpt_length','20')))); ?><?php echo esc_html( get_theme_mod('custom_print_shop_grid_post_button_excerpt_suffix','[...]') ); ?></p>
            <?php } ?>    
          <?php } ?>    
          <?php if ( get_theme_mod('custom_print_shop_post_button_text','Read More') != '' ) {?>
            <div class="read-btn mt-4">
              <a href="<?php echo esc_url( get_permalink() );?>" class="blogbutton-small" ><?php echo esc_html( get_theme_mod('custom_print_shop_post_button_text',__( 'Read More','custom-print-shop' )) ); ?><i class="fa-solid fa-angle-right ms-2"></i><span class="screen-reader-text"><?php echo esc_html( get_theme_mod('custom_print_shop_post_button_text',__( 'Read More','custom-print-shop' )) ); ?></span>
              </a>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </article>
</div>