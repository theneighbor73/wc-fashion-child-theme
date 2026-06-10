<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Custom Print Shop
 */
?>

<header role="banner">
	<h2 class="entry-title"><?php echo esc_html( get_theme_mod('custom_print_shop_no_result_title',__('Nothing Found', 'custom-print-shop' )) ); ?></h2>
</header>

<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
	<p><?php printf( esc_html__( 'Ready to publish your first post? Get started here.', 'custom-print-shop' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
	<?php elseif ( is_search() ) : ?>
		<p><?php echo esc_html( get_theme_mod('custom_print_shop_no_result_text',__('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'custom-print-shop' )) ); ?></p><br />
		<?php if (get_theme_mod('custom_print_shop_show_search_form',true) != '') {
			get_search_form();
		}?>
	<?php else : ?>
		<p><?php esc_html_e( 'Dont worry&hellip it happens to the best of us.', 'custom-print-shop' ); ?></p><br />
		<div class="read-moresec">
			<a href="<?php echo esc_url( home_url() ); ?>" class="button my-3 py-2 px-4"><?php esc_html_e( 'Back to Home Page', 'custom-print-shop' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Back to Home Page', 'custom-print-shop');?></span></a>
		</div>
<?php endif; ?>