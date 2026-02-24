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
    return json_decode($payload, true);
}

/**
 * Handle successful checkout session for coin purchase
 * 
 * @param array $session Stripe checkout session object
 */
function loopis_handle_coins_checkout_completed($session) {
    // Get customer email from session
    $customer_email = isset($session['customer_email']) ? $session['customer_email'] : null;
    $customer_details = isset($session['customer_details']['email']) ? $session['customer_details']['email'] : null;
    $email = $customer_email ?: $customer_details;
    
    if (!$email) {
        error_log("LOOPIS: Coins checkout completed but no customer email found");
        return;
    }
    
    // Find user by email
    $user = get_user_by('email', $email);
    
    if (!$user) {
        error_log("LOOPIS: No user found with email: {$email}");
        return;
    } else {
        $user_ID = $user->ID;
    }
    
    // Add coins to the user's account
    if (function_exists('add_coins')) {
        add_coins($user_ID);
        $display_name = $user->display_name;
        error_log("LOOPIS: add_coins success using Stripe: {$display_name} (ID {$user_ID})");
    }
}
