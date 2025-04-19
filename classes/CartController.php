<?php

namespace CartApi\classes;

class CartController extends \WP_REST_Controller
{
    protected $cart;
    public $count;
    public $total;
    private int $status = 200;

    public function __construct()
    {
        if(null === WC()->session){
            WC()->initialize_session();
        }
        wc_load_cart();
        $this->namespace = 'wc-cart-api/v1';
        $this->rest_base = 'cart';
        $this->cart = WC()->cart;
    }

    /**
     * Route registration
     * @return void
     */
    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->rest_base, [
            [
                'methods' => 'GET',
                'callback' => [$this, 'show'],
                'permission_callback' => '__return_true'
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'add'],
                'permission_callback' => '__return_true'
            ],
            [
                'methods' => 'DELETE',
                'callback' => [$this, 'delete'],
                'permission_callback' => '__return_true'
            ],
            [
                'methods' => 'PATCH',
                'callback' => [$this, 'update'],
                'permission_callback' => '__return_true'
            ]
        ]);
    }

    /**
     * Getting the basket array
     * @return array
     */
    public function get_cart(): array
    {
            return [
                'items' => $this->getItems(),
                'count' => $this->cart->get_cart_contents_count(),
                'total' => $this->cart->get_cart_total(),
                'html' => $this->html()
            ];
    }

    /**
     * Getting an array of cart items
     * @return array
     */
    public function getItems(): array
    {
        $items = [];
        $cart = $this->cart->get_cart();
        if($cart) {
            foreach ($cart as $cart_item) {
                $product = $cart_item['data'];
                $items[$cart_item['key']] = [
                    'key' => $cart_item['key'],
                    'product_id' => $product->get_id(),
                    'title' => $product->get_name(),
                    'price' => $product->get_price(),
                    'total' => $cart_item['line_total'],
                    'currency' => get_woocommerce_currency_symbol(),
                    'quantity' => $cart_item['quantity'],
                    'image' => $product->get_image(),
                    'slug' => $product->get_slug(),
                ];
            }
        }

        return $items;
    }

    /**
     * Show cart items
     * @return \WP_REST_Response
     */
    public function show(): \WP_REST_Response
    {
        return new \WP_REST_Response(['status' => 'success', 'cart' => $this->get_cart()], $this->status);
    }

    /**
     * Add to cart
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function add(\WP_REST_Request $request): \WP_REST_Response
    {
        $product_id = (int)$request->get_param('product_id');
        $quantity = (int)$request->get_param('quantity');

        if(!$product_id){
            return new \WP_REST_Response(['status' => 'error', 'message' => 'Invalid product ID'], 400);
        }

        if(!$quantity){
            return new \WP_REST_Response(['status' => 'error', 'message' => 'Invalid product quantity'], 400);
        }

        $this->cart->add_to_cart($product_id, $quantity);

        return new \WP_REST_Response(['status' => 'success', 'cart' => $this->get_cart()], $this->status);
    }

    /**
     * Updating the cart
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function update(\WP_REST_Request $request): \WP_REST_Response
    {
        $cart_key = $request->get_param('cart_key');
        $quantity =  (int)$request->get_param('quantity');

        if(!$cart_key){
            return new \WP_REST_Response(['status' => 'error', 'message' => 'Invalid cart key'], 400);
        }

        if(!$quantity){
            return new \WP_REST_Response(['status' => 'error', 'message' => 'Invalid product quantity'], 400);
        }

        WC()->cart->set_quantity($cart_key, $quantity);

        return new \WP_REST_Response(['status' => 'success', 'cart' => $this->get_cart()], $this->status);
    }

    /**
     * Removing an item from the cart
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function delete(\WP_REST_Request $request): \WP_REST_Response
    {
        $cart_key = $request->get_param('cart_key');

        if(!$cart_key){
            return new \WP_REST_Response(['status' => 'error', 'message' => 'Invalid cart key'], 400);
        }

        $this->cart->remove_cart_item($cart_key);

        return new \WP_REST_Response(['status' => 'success', 'cart' => $this->get_cart()], $this->status);
    }

    /**
     * Getting a cart view
     * @return false|string
     */
    public function html(): string
    {
        $items = $this->getItems();
        $total = $this->cart->get_cart_total();
        ob_start();
        require WC_CART_API_PATH . 'templates/cart-content.php';
        $content = ob_get_contents();
        ob_get_clean();

        return $content;
    }

    /**
     * Getting the Cart Icon View
     * @return false|string
     */
    public function getCartIcon(): string
    {
        $count = $this->cart->get_cart_contents_count();
        ob_start();
        require WC_CART_API_PATH . 'templates/cart-icon.php';
        $content = ob_get_contents();
        ob_get_clean();

        return $content;
    }

    /**
     * @param $request
     * @return true|\WP_Error
     */
    public function get_items_permissions_check($request)
    {
        if (!current_user_can( 'read' ))
            return new \WP_Error( 'rest_forbidden', esc_html__( 'You do not have access to view shopping cart details!' ), [ 'status' => $this->error_status_code() ] );

        return true;
    }

    /**
     * @return int|void
     */
    protected function error_status_code()
    {
        if(!is_user_logged_in()) {
            return 401;
        }

        if(!current_user_can( 'edit' )) {
            return 403;
        }
        return 200;
    }
}