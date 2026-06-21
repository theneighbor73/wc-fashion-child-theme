<?php

/**
 * Product Search Validation
 */

add_action('template_redirect', function () {
    if (is_search()) {
        $raw_search_term = isset($_GET['s']) ? wp_unslash($_GET['s']) : '';
        $clean_search_term = trim($raw_search_term);
        $term_length = strlen($clean_search_term);

        if ($clean_search_term === '' || $term_length < 3) {
            if (WC()->session) {
                if (! WC()->session->has_session()) {
                    WC()->session->set_customer_session_cookie(true);
                }
                WC()->session->set('product_search_validation', 'Search terms must be at least 3 characters long.');
            }

            wp_safe_redirect(get_permalink(wc_get_page_id('shop')));
            exit;
        } else if (preg_match("/[^\p{L}\p{N} ']/u", $clean_search_term)) {
            if (WC()->session) {
                if (! WC()->session->has_session()) {
                    WC()->session->set_customer_session_cookie(true);
                }
                WC()->session->set('product_search_validation', 'Search terms cannot contain special characters.');
            }

            wp_safe_redirect(get_permalink(wc_get_page_id('shop')));
            exit;
        } else if (preg_match("/([\p{L}\p{N}'])\\1{3,}/u", $clean_search_term)) {
            error_log("clean_search_term: " . $clean_search_term);
            if (WC()->session) {
                if (! WC()->session->has_session()) {
                    WC()->session->set_customer_session_cookie(true);
                }
                WC()->session->set('product_search_validation', 'Invalid search terms. Please try again.');
            }

            wp_safe_redirect(get_permalink(wc_get_page_id('shop')));
            exit;
        }
    }
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'product_search_validation_js',
        get_theme_file_uri('inc/product-search-validation/js/frontend.js'),
        [],
        '20260621',
        true
    );

    // Pass the data AFTER the redirect so that the toast can have data
    if (WC()->session && WC()->session->has_session()) {
        $error_message = WC()->session->get('product_search_validation');

        if ($error_message) {
            wp_localize_script(
                'product_search_validation_js',
                'backendToastError',
                array(
                    'message' => $error_message
                )
            );
            // Always clean up after
            WC()->session->set('product_search_validation', null);
        }
    }
});
