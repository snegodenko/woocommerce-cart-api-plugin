<?php

/**
 * Plugin name: Woocommerce cart API
 * Description: Plugin for working with a shopping cart on Woocommerce
 * Author: Sergey
 */

define('WC_CART_API_PATH', plugin_dir_path(__FILE__));

require WC_CART_API_PATH . 'classes/CartController.php';

require WC_CART_API_PATH . 'includes/hooks.php';
require WC_CART_API_PATH . 'includes/shortcodes.php';