<?php
/**
 * Stripe Registration & Payment Activation
 * 
 * Handles automatic account activation after successful Stripe payment.
 * This file must be loaded on all requests because Stripe webhooks
 * can fire at any time via REST API callbacks.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * DEBUG: Log ALL user meta updates to find the Stripe meta key
 */
function loopis_debug_all_user_meta($meta_id, $user_id, $meta_key, $meta_value) {
    // Only log for newly registered users (member_pending role)
    $user = get_userdata($user_id);
    if ($user && in_array('member_pending', (array) $user->roles)) {
        error_log("LOOPIS DEBUG: User meta updated - Key: {$meta_key} for user {$user_id}");
        if (strpos($meta_key, 'stripe') !== false || strpos($meta_key, 'wpum') !== false) {
            error_log("LOOPIS DEBUG: Stripe/WPUM related meta value: " . print_r($meta_value, true));
        }
    }
}
add_action('updated_user_meta', 'loopis_debug_all_user_meta', 5, 4);

/**
 * Activate account after successful Stripe payment
 * 
 * This hooks into user meta updates to detect when WPUM Stripe
 * marks a payment as complete via webhook, then activates the account.
 * 
 * IMPORTANT: Must NOT activate during wpum_after_registration because
 * that would break the redirect to Stripe checkout!
 * 
 * @param int    $meta_id    ID of the metadata entry
 * @param int    $user_id    User ID
 * @param string $meta_key   Meta key being updated
 * @param mixed  $meta_value Meta value (plan data from Stripe)
 */
function loopis_activate_after_stripe_payment($meta_id, $user_id, $meta_key, $meta_value) {
    // Check for both test and live Stripe plan meta keys
    if ($meta_key !== 'wpum_stripe_plan_test' && $meta_key !== 'wpum_stripe_plan') {
        return;
    }
    
    error_log("LOOPIS DEBUG: Stripe plan meta updated for user {$user_id}");
    
    // Parse the plan data
    $plan_data = maybe_unserialize($meta_value);
    error_log("LOOPIS DEBUG: Plan data: " . print_r($plan_data, true));
    
    // Check if payment is marked as paid (can be true or 1)
    if (empty($plan_data['paid'])) {
        error_log("LOOPIS DEBUG: Payment not marked as paid yet");
        return; // Payment not completed yet
    }
    
    error_log("LOOPIS DEBUG: Payment confirmed as paid!");
    
    // Check if account is already activated (prevent duplicate processing)
    $user = get_userdata($user_id);
    if (!$user) {
        error_log("LOOPIS DEBUG: User not found!");
        return;
    }
    
    error_log("LOOPIS DEBUG: User roles: " . print_r($user->roles, true));
    
    if (in_array('member', (array) $user->roles)) {
        error_log("LOOPIS DEBUG: User already has member role");
        return; // Already activated
    }
    
    // Activate the account after successful payment
    if (function_exists('activate_account')) {
        error_log("LOOPIS DEBUG: Calling activate_account for user {$user_id}");
        activate_account($user_id);
        
        // Log success for debugging
        error_log("LOOPIS: Account activated after Stripe payment completed for user ID: {$user_id}");
    } else {
        error_log("LOOPIS DEBUG: activate_account function not found!");
    }
}
add_action('updated_user_meta', 'loopis_activate_after_stripe_payment', 10, 4);
