WooCommerce Cart API Plugin
A lightweight and developer-friendly plugin that adds a custom REST API for managing the WooCommerce cart.

🎯 Purpose
It provides full cart management capabilities.
It's based on clean code practices and designed for real-world usage.

🚀 Features
Fetch current cart contents

Add products to the cart

Update product quantities

Remove items from the cart

Returns cart HTML and item count for dynamic UIs

Easy to extend and integrate with JavaScript frontends

Includes a shortcode for displaying a cart icon with item count

📡 API Endpoints
All endpoints are accessible under the base route: /wp-json/wc-cart-api/v1/cart

GET /cart
Returns the current cart contents and totals.

POST /cart
Adds a product to the cart.
Body:
{
"product_id": 123,
"quantity": 1
}

PATCH /cart
Updates quantity for an existing cart item.
Body:
{
"cart_key": "abc123",
"quantity": 2
}

DELETE /cart
Removes a product from the cart.
Body:
{
"cart_key": "abc123"
}

🧩 Shortcode
You can display a cart icon with the number of items directly in your site header using the following shortcode:
[wc_cart_icon]

This is useful for theme integration or adding cart visibility to any part of your template.
⚙️ Requirements
WordPress 5.0+

WooCommerce 4.0+

PHP 7.4+ (PHP 8.x compatible)

🛠️ Installation
Download or clone this repository

Copy the folder to wp-content/plugins/woocommerce-cart-api-plugin

Activate the plugin via the WordPress admin panel