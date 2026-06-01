<?php
/**
 * Stripe coins purchase handler
 * 
 * Created by CoPilot
 * Handles automatic coin addition after successful Stripe payment via webhook.
 * This file must be loaded on all requests because Stripe webhooks can fire at any time via REST API callbacks.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get secret from .env if not defined in wp-config
if (!defined('LOOPIS_STRIPE_WEBHOOK_SECRET_COINS')) {
    define('LOOPIS_STRIPE_WEBHOOK_SECRET_COINS', getenv('LOOPIS_STRIPE_WEBHOOK_SECRET_COINS') ?: '');
}

/**
 * Coins product identifiers for Stripe Checkout Session matching.
 *
 * Uses LOOPIS_TEST to switch between test and live IDs.
 *
 * @return array{payment_link_id:string,price_id:string}
 */
function loopis_get_coins_stripe_product_ids() {
    $is_test_mode = defined('LOOPIS_TEST') && LOOPIS_TEST;

    if ($is_test_mode) {
        return array(
            'payment_link_id' => 'plink_1SfNcjDbS0ElMuPwzWno8mRl',
            'price_id' => 'price_1SfKM9DbS0ElMuPwKdOXY70r',
        );
    }

    return array(
        'payment_link_id' => 'plink_1StnoRDc5PTLJtA3d9nkdDIf',
        'price_id' => 'price_1StmWqDc5PTLJtA3s0Dlr69H',
    );
}

/**
 * Extract Stripe price IDs from a checkout session payload when available.
 *
 * @param array $session Stripe checkout session object
 * @return string[]
 */
function loopis_extract_coins_stripe_session_price_ids($session) {
    $price_ids = array();

    if (isset($session['line_items']['data']) && is_array($session['line_items']['data'])) {
        foreach ($session['line_items']['data'] as $item) {
            if (!empty($item['price']['id'])) {
                $price_ids[] = $item['price']['id'];
            } elseif (!empty($item['price'])) {
                $price_ids[] = $item['price'];
            }
        }
    }

    if (!empty($session['metadata']['price_id'])) {
        $price_ids[] = $session['metadata']['price_id'];
    }

    return array_values(array_unique(array_filter($price_ids)));
}

/**
 * Return true when session metadata explicitly marks a coins checkout.
 *
 * @param array $session Stripe checkout session object
 * @return bool
 */
function loopis_is_coins_checkout_by_metadata($session) {
    $metadata = isset($session['metadata']) && is_array($session['metadata']) ? $session['metadata'] : array();
    $type = isset($metadata['loopis_checkout_type']) ? sanitize_key((string) $metadata['loopis_checkout_type']) : '';
    return 'coins' === $type;
}

/**
 * Check if this Stripe checkout session belongs to coins product.
 *
 * @param array $session Stripe checkout session object
 * @return bool
 */
function loopis_is_coins_checkout_session($session) {
    if (loopis_is_coins_checkout_by_metadata($session)) {
        return true;
    }

    $ids = loopis_get_coins_stripe_product_ids();
    $payment_link_id = isset($session['payment_link']) ? $session['payment_link'] : '';

    if (!empty($payment_link_id) && $payment_link_id === $ids['payment_link_id']) {
        return true;
    }

    $price_ids = loopis_extract_coins_stripe_session_price_ids($session);
    return in_array($ids['price_id'], $price_ids, true);
}

/**
 * Resolve user ID from checkout session identifiers and metadata.
 *
 * Priority: metadata wp_user_id -> metadata wp_user_email -> customer email.
 *
 * @param array $session Stripe checkout session object
 * @return int
 */
function loopis_get_coins_user_id_from_session($session) {
    $metadata = isset($session['metadata']) && is_array($session['metadata']) ? $session['metadata'] : array();

    if (!empty($metadata['wp_user_id'])) {
        $user_id = absint($metadata['wp_user_id']);
        if ($user_id > 0 && get_userdata($user_id)) {
            return $user_id;
        }
    }

    if (!empty($metadata['wp_user_email'])) {
        $meta_email = sanitize_email((string) $metadata['wp_user_email']);
        if ('' !== $meta_email) {
            $user_by_meta_email = get_user_by('email', $meta_email);
            if ($user_by_meta_email) {
                return (int) $user_by_meta_email->ID;
            }
        }
    }

    $customer_email = isset($session['customer_email']) ? sanitize_email((string) $session['customer_email']) : '';
    $customer_details_email = isset($session['customer_details']['email']) ? sanitize_email((string) $session['customer_details']['email']) : '';
    $fallback_email = '' !== $customer_email ? $customer_email : $customer_details_email;

    if ('' === $fallback_email) {
        return 0;
    }

    $user = get_user_by('email', $fallback_email);
    return $user ? (int) $user->ID : 0;
}

/**
 * Prevent duplicate processing of the same Stripe checkout session.
 *
 * @param int    $user_id    WordPress user ID
 * @param string $session_id Stripe checkout session ID
 * @return bool True when already processed
 */
function loopis_is_coins_session_already_processed($user_id, $session_id) {
    if ($user_id <= 0 || '' === $session_id) {
        return false;
    }

    $processed_sessions = get_user_meta($user_id, 'loopis_stripe_coins_processed_sessions', true);
    if (!is_array($processed_sessions)) {
        return false;
    }

    return in_array($session_id, $processed_sessions, true);
}

/**
 * Persist Stripe checkout session as processed for idempotency.
 *
 * @param int    $user_id    WordPress user ID
 * @param string $session_id Stripe checkout session ID
 * @return void
 */
function loopis_mark_coins_session_processed($user_id, $session_id) {
    if ($user_id <= 0 || '' === $session_id) {
        return;
    }

    $processed_sessions = get_user_meta($user_id, 'loopis_stripe_coins_processed_sessions', true);
    if (!is_array($processed_sessions)) {
        $processed_sessions = array();
    }

    if (!in_array($session_id, $processed_sessions, true)) {
        $processed_sessions[] = $session_id;
        update_user_meta($user_id, 'loopis_stripe_coins_processed_sessions', $processed_sessions);
    }
}

/**
 * Register REST API endpoint for Stripe coin purchase webhooks
 */
function loopis_register_stripe_coins_webhook() {
    register_rest_route('loopis/v1', '/stripe-coins-webhook', array(
        'methods' => 'POST',
        'callback' => 'loopis_handle_stripe_coins_webhook',
        'permission_callback' => '__return_true', // Stripe webhooks don't use WP auth
    ));
}
add_action('rest_api_init', 'loopis_register_stripe_coins_webhook');

/**
 * Handle Stripe webhook events for coin purchases
 * 
 * Webhook URL: https://loopis.app/wp-json/loopis/v1/stripe-coins-webhook
 * 
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function loopis_handle_stripe_coins_webhook($request) {
    // Get the raw POST body
    $payload = $request->get_body();
    $sig_header = $request->get_header('stripe-signature');
    
    if (!$sig_header) {
        return new WP_REST_Response(array('error' => 'No signature'), 400);
    }
    
    // Verify webhook signature
    try {
        $event = loopis_verify_stripe_coins_webhook($payload, $sig_header);
    } catch (Exception $e) {
        error_log("LOOPIS: Coins webhook signature verification failed: " . $e->getMessage());
        return new WP_REST_Response(array('error' => 'Invalid signature'), 400);
    }
    
    // Handle the event based on type
    if ($event['type'] === 'checkout.session.completed') {
        loopis_handle_coins_checkout_completed($event['data']['object']);
    }
    
    return new WP_REST_Response(array('success' => true), 200);
}

/**
 * Verify Stripe webhook signature for coins
 * 
 * @param string $payload Raw POST body
 * @param string $sig_header Stripe signature header
 * @return array Verified event data
 * @throws Exception If signature verification fails
 */
function loopis_verify_stripe_coins_webhook($payload, $sig_header) {
    $secret = LOOPIS_STRIPE_WEBHOOK_SECRET_COINS;

    if ('' === $secret) {
        throw new Exception('Missing webhook secret');
    }
    
    // Parse signature header
    $elements = explode(',', $sig_header);
    $signatures = array();
    $timestamp = null;
    
    foreach ($elements as $element) {
        $parts = explode('=', $element, 2);
        if (count($parts) === 2) {
            if ($parts[0] === 't') {
                $timestamp = $parts[1];
            } elseif ($parts[0] === 'v1') {
                $signatures[] = $parts[1];
            }
        }
    }
    
    if (!$timestamp || empty($signatures)) {
        throw new Exception('Invalid signature header format');
    }
    
    // Verify signature
    $signed_payload = $timestamp . '.' . $payload;
    $expected_signature = hash_hmac('sha256', $signed_payload, $secret);
    
    $signature_valid = false;
    foreach ($signatures as $signature) {
        if (hash_equals($expected_signature, $signature)) {
            $signature_valid = true;
            break;
        }
    }
    
    if (!$signature_valid) {
        throw new Exception('Signature verification failed');
    }
    
    // Check timestamp (prevent replay attacks - allow 5 minute window)
    $current_time = time();
    if (abs($current_time - $timestamp) > 300) {
        throw new Exception('Timestamp outside allowed window');
    }
    
    // Decode and return event
    $event = json_decode($payload, true);

    if (!is_array($event) || empty($event['type'])) {
        throw new Exception('Invalid webhook payload');
    }

    return $event;
}

/**
 * Handle successful checkout session for coin purchase
 * 
 * @param array $session Stripe checkout session object
 */
function loopis_handle_coins_checkout_completed($session) {
    $session_id = isset($session['id']) ? (string) $session['id'] : '';

    if (!loopis_is_coins_checkout_session($session)) {
        $session_id = isset($session['id']) ? $session['id'] : 'unknown';
        return;
    }

    $user_id = loopis_get_coins_user_id_from_session($session);
    if ($user_id <= 0) {
        $session_id_log = '' !== $session_id ? $session_id : 'unknown';
        error_log("LOOPIS: Coins checkout completed but user could not be resolved (session {$session_id_log})");
        return;
    }

    if (loopis_is_coins_session_already_processed($user_id, $session_id)) {
        error_log("LOOPIS: Coins session already processed ({$session_id}) for user {$user_id}");
        return;
    }

    // Add coins to the user's account
    if (function_exists('add_coins')) {
        $added = (bool) add_coins($user_id);
        if (!$added) {
            error_log("LOOPIS: add_coins failed using Stripe (ID {$user_id})");
            return;
        }

        $user = get_userdata($user_id);
        $display_name = $user ? $user->display_name : 'unknown';
        error_log("LOOPIS: add_coins success using Stripe: {$display_name} (ID {$user_id})");
        loopis_mark_coins_session_processed($user_id, $session_id);
        return;
    }

    error_log("LOOPIS: add_coins function missing for user {$user_id}");
}
