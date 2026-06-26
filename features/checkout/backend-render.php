<?php

function cpsc_checkout_item_thumbnail($product_name, $cart_item, $cart_item_key)
{
    if (! is_checkout() || is_order_received_page()) {
        return $product_name;
    }

    $product = $cart_item['data'];
    if (! is_object($product)) {
        return $product_name;
    }

    $thumbnail = $product->get_image(array(80, 80), array('class' => 'checkout-product-thumbnail'));
    if (! $thumbnail) {
        return $product_name;
    }

    return '<div class="checkout-product-with-thumbnail">' .
        '<div class="checkout-product-thumbnail-wrapper">' . $thumbnail . '</div>' .
        '<div class="checkout-product-name-wrapper">' . $product_name . '</div>' .
        '</div>';
}
add_filter('woocommerce_cart_item_name', 'cpsc_checkout_item_thumbnail', 10, 3);

function cpsc_order_review_heading($heading)
{
    if (is_checkout() && ! is_order_received_page()) {
        return esc_html__('Order summary', 'custom-print-shop');
    }
    return $heading;
}

add_filter('woocommerce_order_review_heading', 'cpsc_order_review_heading');

// Enqueue assets

function cpsc_checkout_page_css()
{
    if (is_checkout() || is_cart()) {
        cpsc_enqueue_style('cpsc_checkout_css', '/features/checkout/checkout.css');
    }
}

add_action('wp_enqueue_scripts', 'cpsc_checkout_page_css');
