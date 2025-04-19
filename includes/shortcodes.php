<?php


/**
 * Cart html
 */
add_shortcode('wc_cart_content', 'wc_cart_content_function');
function wc_cart_content_function()
{
    return (new \CartApi\classes\CartController())->html();
}

/**
 * Cart icon html
 */
add_shortcode('wc_cart_icon', 'wc_cart_icon_function');
function wc_cart_icon_function()
{
    return (new \CartApi\classes\CartController())->getCartIcon();
}