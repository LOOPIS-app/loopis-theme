<?php
/**
 * Stripe Checkout Session creation for membership signups
 *
 * This endpoint creates a Stripe Checkout Session with user metadata
 * for reliable user mapping when the webhook is processed.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define Stripe API key
if (!defined('LOOPIS_STRIPE_SECRET_KEY')) {
    $key = getenv('LOOPIS_STRIPE_SECRET_KEY') ?: getenv('STRIPE_SECRET_KEY') ?: '';
    define('LOOPIS_STRIPE_SECRET_KEY', $key);
}

// Define success/cancel URLs
if (!defined('LOOPIS_CHECKOUT_SUCCESS_URL')) {
    define('LOOPIS_CHECKOUT_SUCCESS_URL', add_query_arg(array('option' => 'membership-stripe', 'checkout' => 'success'), home_url('/shop/')));
}

if (!defined('LOOPIS_CHECKOUT_CANCEL_URL')) {
    define('LOOPIS_CHECKOUT_CANCEL_URL', add_query_arg(array('option' => 'membership-stripe', 'checkout' => 'cancelled'), home_url('/shop/')));
}

if (!defined('LOOPIS_COINS_CHECKOUT_SUCCESS_URL')) {
    define('LOOPIS_COINS_CHECKOUT_SUCCESS_URL', add_query_arg(array('option' => 'coins-stripe', 'checkout' => 'success'), home_url('/shop/')));
}

if (!defined('LOOPIS_COINS_CHECKOUT_CANCEL_URL')) {
    define('LOOPIS_COINS_CHECKOUT_CANCEL_URL', add_query_arg(array('option' => 'coins-stripe', 'checkout' => 'cancelled'), home_url('/shop/')));
}

/**
 * Create a Stripe Checkout Session for membership
 *
 * @param int    $user_id   WordPress user ID
 * @param string $user_email User email address
 * @return array|WP_Error Session data with 'url' key or error
 */
function loopis_create_membership_checkout_session($user_id, $user_email) {
    if ($user_id <= 0 || empty($user_email)) {
        return new WP_Error('invalid_user', 'Invalid user ID or email');
    }

    $api_key = LOOPIS_STRIPE_SECRET_KEY;
    if (empty($api_key)) {
        error_log('LOOPIS: Stripe secret key not configured');
        return new WP_Error('no_stripe_key', 'Payment processor not configured');
    }

    $ids = loopis_get_membership_stripe_product_ids();
    $price_id = $ids['price_id'];

    if (empty($price_id)) {
        return new WP_Error('no_price_id', 'Product price not found');
    }

    $session_data = array(
        'payment_method_types' => array('card'),
        'line_items' => array(
            array(
                'price' => $price_id,
                'quantity' => 1,
            ),
        ),
        'mode' => 'payment',
        'success_url' => LOOPIS_CHECKOUT_SUCCESS_URL,
        'cancel_url' => LOOPIS_CHECKOUT_CANCEL_URL,
        'customer_email' => $user_email,
        'metadata' => array(
            'wp_user_id' => (string) $user_id,
            'wp_user_email' => $user_email,
            'loopis_checkout_type' => 'membership',
        ),
    );

    // Use Stripe PHP SDK if available, otherwise use HTTP request
    $session = loopis_call_stripe_api('checkout/sessions', $session_data, $api_key);

    if (is_wp_error($session)) {
        error_log('LOOPIS: Failed to create checkout session: ' . $session->get_error_message());
        return $session;
    }

    return $session;
}

/**
 * Create a Stripe Checkout Session for coin purchase.
 *
 * @param int    $user_id WordPress user ID
 * @param string $user_email User email address
 * @return array|WP_Error Session data with 'url' key or error
 */
function loopis_create_coins_checkout_session($user_id, $user_email) {
    if ($user_id <= 0 || empty($user_email)) {
        return new WP_Error('invalid_user', 'Invalid user ID or email');
    }

    $api_key = LOOPIS_STRIPE_SECRET_KEY;
    if (empty($api_key)) {
        error_log('LOOPIS: Stripe secret key not configured');
        return new WP_Error('no_stripe_key', 'Payment processor not configured');
    }

    $ids = loopis_get_coins_stripe_product_ids();
    $price_id = $ids['price_id'];

    if (empty($price_id)) {
        return new WP_Error('no_price_id', 'Product price not found');
    }

    $session_data = array(
        'payment_method_types' => array('card'),
        'line_items' => array(
            array(
                'price' => $price_id,
                'quantity' => 1,
            ),
        ),
        'mode' => 'payment',
        'success_url' => LOOPIS_COINS_CHECKOUT_SUCCESS_URL,
        'cancel_url' => LOOPIS_COINS_CHECKOUT_CANCEL_URL,
        'customer_email' => $user_email,
        'metadata' => array(
            'wp_user_id' => (string) $user_id,
            'wp_user_email' => $user_email,
            'loopis_checkout_type' => 'coins',
        ),
    );

    $session = loopis_call_stripe_api('checkout/sessions', $session_data, $api_key);

    if (is_wp_error($session)) {
        error_log('LOOPIS: Failed to create coins checkout session: ' . $session->get_error_message());
        return $session;
    }

    return $session;
}

/**
 * Call Stripe API via HTTP request
 *
 * @param string $endpoint API endpoint (e.g., 'checkout/sessions')
 * @param array  $data     POST data
 * @param string $api_key  Stripe secret API key
 * @return array|WP_Error Response data or error
 */
function loopis_call_stripe_api($endpoint, $data, $api_key) {
    $url = 'https://api.stripe.com/v1/' . $endpoint;

    $args = array(
        'method' => 'POST',
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ),
        'body' => http_build_query($data),
        'timeout' => 30,
    );

    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        return $response;
    }

    $status = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $decoded = json_decode($body, true);

    if ($status >= 400) {
        $error_message = isset($decoded['error']['message']) ? $decoded['error']['message'] : 'Unknown error';
        error_log("LOOPIS Stripe API Error ({$status}): {$error_message}");
        return new WP_Error('stripe_error', $error_message);
    }

    return $decoded;
}

/**
 * Register REST endpoint for creating checkout sessions
 */
function loopis_register_create_checkout_session_endpoint() {
    register_rest_route('loopis/v1', '/create-membership-checkout', array(
        'methods' => 'POST',
        'callback' => 'loopis_handle_create_checkout_session_request',
        'permission_callback' => 'loopis_check_checkout_session_permission',
    ));

    register_rest_route('loopis/v1', '/membership-status', array(
        'methods' => 'GET',
        'callback' => 'loopis_handle_membership_status_request',
        'permission_callback' => 'loopis_check_membership_status_permission',
    ));

    register_rest_route('loopis/v1', '/create-coins-checkout', array(
        'methods' => 'POST',
        'callback' => 'loopis_handle_create_coins_checkout_session_request',
        'permission_callback' => 'loopis_check_checkout_session_permission',
    ));
}
add_action('rest_api_init', 'loopis_register_create_checkout_session_endpoint');

/**
 * Permission check for checkout session creation
 *
 * Must be an authenticated user who is currently activated
 *
 * @param WP_REST_Request $request
 * @return bool
 */
function loopis_check_checkout_session_permission($request) {
    $user = wp_get_current_user();

    if (!$user || $user->ID <= 0) {
        return false;
    }

    // User must be logged in and have a role
    if (empty($user->roles)) {
        return false;
    }

    $nonce = $request->get_header('X-WP-Nonce');
    if (!$nonce || !wp_verify_nonce($nonce, 'wp_rest')) {
        return false;
    }

    return true;
}

/**
 * Permission check for membership status polling.
 *
 * @param WP_REST_Request $request
 * @return bool
 */
function loopis_check_membership_status_permission($request) {
    if (!is_user_logged_in()) {
        return false;
    }

    $nonce = $request->get_header('X-WP-Nonce');
    if (!$nonce || !wp_verify_nonce($nonce, 'wp_rest')) {
        return false;
    }

    return true;
}

/**
 * Handle POST request to create checkout session
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function loopis_handle_create_checkout_session_request($request) {
    $user = wp_get_current_user();

    if (!$user || $user->ID <= 0) {
        return new WP_REST_Response(
            array('error' => 'Not authenticated'),
            401
        );
    }

    $user_id = $user->ID;
    $user_email = $user->user_email;

    // Create the session
    $session = loopis_create_membership_checkout_session($user_id, $user_email);

    if (is_wp_error($session)) {
        return new WP_REST_Response(
            array('error' => $session->get_error_message()),
            400
        );
    }

    // Return the redirect URL
    return new WP_REST_Response(
        array(
            'url' => isset($session['url']) ? $session['url'] : '',
            'session_id' => isset($session['id']) ? $session['id'] : '',
        ),
        200
    );
}

/**
 * Return the current user's membership status for client-side polling.
 *
 * @return WP_REST_Response
 */
function loopis_handle_membership_status_request() {
    $user = wp_get_current_user();
    return new WP_REST_Response(
        array('is_member' => in_array('member', (array) $user->roles, true)),
        200
    );
}

/**
 * Handle POST request to create coins checkout session.
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function loopis_handle_create_coins_checkout_session_request($request) {
    $user = wp_get_current_user();

    if (!$user || $user->ID <= 0) {
        return new WP_REST_Response(
            array('error' => 'Not authenticated'),
            401
        );
    }

    $session = loopis_create_coins_checkout_session($user->ID, $user->user_email);

    if (is_wp_error($session)) {
        return new WP_REST_Response(
            array('error' => $session->get_error_message()),
            400
        );
    }

    return new WP_REST_Response(
        array(
            'url' => isset($session['url']) ? $session['url'] : '',
            'session_id' => isset($session['id']) ? $session['id'] : '',
        ),
        200
    );
}
