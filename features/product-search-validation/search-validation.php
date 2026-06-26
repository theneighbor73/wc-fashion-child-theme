<?php

/*
|--------------------------------------------------------------------------
| Logic
|--------------------------------------------------------------------------
*/

function cpsc_product_search_validation()
{
    if (is_search()) {
        $raw_search_term = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
        $clean_search_term = trim($raw_search_term);
        $term_length = strlen($clean_search_term);

        if ($clean_search_term === '' || $term_length < 3) {
            if (WC()->session) {
                if (! WC()->session->has_session()) {
                    WC()->session->set_customer_session_cookie(true);
                }
                WC()->session->set('cpsc_product_search_validation', 'Search terms must be at least 3 characters long.');
            }

            wp_safe_redirect(get_permalink(wc_get_page_id('shop')));
            exit;
        } else if (preg_match("/[^\p{L}\p{N} ']/u", $clean_search_term)) {
            if (WC()->session) {
                if (! WC()->session->has_session()) {
                    WC()->session->set_customer_session_cookie(true);
                }
                WC()->session->set('cpsc_product_search_validation', 'Search terms cannot contain special characters.');
            }

            wp_safe_redirect(get_permalink(wc_get_page_id('shop')));
            exit;
        } else if (preg_match("/([\p{L}\p{N}'])\\1{3,}/u", $clean_search_term)) {
            if (WC()->session) {
                if (! WC()->session->has_session()) {
                    WC()->session->set_customer_session_cookie(true);
                }
                WC()->session->set('cpsc_product_search_validation', 'Invalid search terms. Please try again.');
            }

            wp_safe_redirect(get_permalink(wc_get_page_id('shop')));
            exit;
        }
    }
};
add_action('template_redirect', 'cpsc_product_search_validation');

/*
|--------------------------------------------------------------------------
| Asset Loading
|--------------------------------------------------------------------------
*/

function cpsc_product_search_validation_assets()
{

    cpsc_enqueue_script('cpsc_product_search_validation_js', 'features/product-search-validation/search-validation.js', ['global_toast_js']);

    // Pass the data AFTER the redirect so that the toast can have data
    if (WC()->session && WC()->session->has_session()) {
        $error_message = WC()->session->get('cpsc_product_search_validation');

        if ($error_message) {
            wp_localize_script(
                'cpsc_product_search_validation_js',
                'backendToastError',
                array(
                    'message' => $error_message
                )
            );

            // Always clean up after
            WC()->session->set('cpsc_product_search_validation', null);
        }
    }
}
add_action('wp_enqueue_scripts', 'cpsc_product_search_validation_assets');
