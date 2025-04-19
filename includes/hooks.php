<?php




/**
 * Plugin init
 */
add_action('init', 'wc_cart_loaded_function', 20);
function wc_cart_loaded_function()
{
    if (!class_exists('WooCommerce')){
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error">
                <p>For the plugin to work, WooCommerce must be installed and activated.</p>
                </div>';
        });
        return;
    }

    add_action('rest_api_init', 'wc_cart_api_function');
    add_action('wp_enqueue_scripts', 'wc_cart_api_include_function');
    add_action('wp_footer', 'wc_cart_api_footer_function', 30);
}

function wc_cart_api_function()
{
    $controller = new \CartApi\classes\CartController();
    $controller->register_routes();

}

/**
 * Assets
 */
function wc_cart_api_include_function()
{
    wp_enqueue_style('wc-cart-api-style', plugins_url( 'wc-cart-api/assets') . '/wc-cart-api.css');
    wp_enqueue_script('wc-cart-api-script', plugins_url( 'wc-cart-api/assets') . '/wc-cart-api.js', null, false, true);
}

/**
 * Include modal cart
 */
function wc_cart_api_footer_function()
{
    ob_start();
    require WC_CART_API_PATH . 'templates/modal-cart.php';
    $content = ob_get_contents();
    ob_get_clean();

    echo $content;
}


