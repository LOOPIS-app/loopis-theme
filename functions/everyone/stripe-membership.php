<?php
/**
 * Stripe account activation handler
 * 
 * Created by CoPilot
 * Handles account activation after successful Stripe payment via webhook.
 * This file must be loaded on all requests because Stripe webhooks can fire at any time via REST API callbacks.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get secret from .env if not defined in wp-config
if (!defined('LOOPIS_STRIPE_WEBHOOK_SECRET_MEMBERSHIP')) {
    define('LOOPIS_STRIPE_WEBHOOK_SECRET_MEMBERSHIP', getenv('LOOPIS_STRIPE_WEBHOOK_SECRET_MEMBERSHIP') ?: '');
}

/**
 * Register REST API endpoint for Stripe membership webhooks
 */
function loopis_register_stripe_membership_webhook() {
    register_rest_route('loopis/v1', '/stripe-membership-webhook', array(
        'methods' => 'POST',
        'callback' => 'loopis_handle_stripe_membership_webhook',
        'permission_callback' => '__return_true', // Stripe webhooks don't use WP auth
    ));
}
add_action('rest_api_init', 'loopis_register_stripe_membership_webhook');

/**
 * Handle Stripe webhook events for membership
 * 
 * Webhook URL: https://loopis.app/wp-json/loopis/v1/stripe-membership-webhook
 * 
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function loopis_handle_stripe_membership_webhook($request) {
    error_log("LOOPIS: Stripe membership webhook received");
    
    // Get the raw POST body
    $payload = $request->get_body();
    $sig_header = $request->get_header('stripe-signature');
    
    if (!$sig_header) {
        error_log("LOOPIS ERROR: No Stripe signature header found");
        return new WP_REST_Response(array('error' => 'No signature'), 400);
    }
    
    // Verify webhook signature
    try {
        $event = loopis_verify_stripe_membership_webhook($payload, $sig_header);
    } catch (Exception $e) {
        error_log("LOOPIS ERROR: Membership webhook signature verification failed: " . $e->getMessage());
        return new WP_REST_Response(array('error' => 'Invalid signature'), 400);
    }
    
    // Handle the event based on type
    if ($event['type'] === 'checkout.session.completed') {
        loopis_handle_membership_checkout_completed($event['data']['object']);
    }
    
    return new WP_REST_Response(array('success' => true), 200);
}

/**
 * Verify Stripe webhook signature for membership
 * 
 * @param string $payload Raw POST body
 * @param string $sig_header Stripe signature header
 * @return array Verified event data
 * @throws Exception If signature verification fails
 */
function loopis_verify_stripe_membership_webhook($payload, $sig_header) {
    $secret = LOOPIS_STRIPE_WEBHOOK_SECRET_MEMBERSHIP;
    
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
 * Handle successful checkout session for membership
 * 
 * @param array $session Stripe checkout session object
 */
function loopis_handle_membership_checkout_completed($session) {
    // Get customer email from session
    $customer_email = isset($session['customer_email']) ? $session['customer_email'] : null;
    $customer_details = isset($session['customer_details']['email']) ? $session['customer_details']['email'] : null;
    $email = $customer_email ?: $customer_details;
    
    if (!$email) {
        error_log("LOOPIS: Membership checkout completed but no customer email found");
        return;
    }
    
    // Find user by email
    $user = get_user_by('email', $email);
    
    if (!$user) {
        error_log("LOOPIS: No user found with email: {$email}");
        return;
    }
    
    // Activate the account
    loopis_activate_membership_account($user->ID, $session);
}

/**
 * Activate user account after successful membership payment
 * 
 * @param int $user_id WordPress user ID
 * @param array $stripe_data Stripe event data (for logging)
 */
function loopis_activate_membership_account($user_id, $stripe_data = array()) {
    $user = get_userdata($user_id);
    
    if (!$user) {
        return;
    }
    
    // Check if already activated
    if (in_array('member', (array) $user->roles)) {
        return;
    }
    
    // Store payment data in user meta
    update_user_meta($user_id, 'loopis_stripe_payment_completed', current_time('mysql'));
    update_user_meta($user_id, 'loopis_stripe_payment_data', $stripe_data);
    
    // Activate the account
    if (function_exists('activate_account')) {
        activate_account($user_id);
        $display_name = $user->display_name;
        error_log("LOOPIS: activate_account success using Stripe: {$display_name} (ID {$user_id})");
    }
}