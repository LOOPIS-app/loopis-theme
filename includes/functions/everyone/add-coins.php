<?php
/**
 * Automatic additions of coins initiated by Stripe payment.
 * 
 * Adding payment details to the user's account.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function add_coins($user_id = null) {

    // Get user ID (either passed parameter or current logged-in user)
    if ($user_id === null) {
        $user_id = get_current_user_id();
    }

    // Get user data
    $user = get_userdata($user_id);
    if (!$user) {
        error_log("LOOPIS: activate_account failed - User {$user_id} not found");
        return false;
    }

    // Retrieve the current wpum_payments field value
    $current_payments = get_user_meta($user_id, 'wpum_payments', true);
    if (!is_array($current_payments)) {
        $current_payments = array();
    }

    // Create the new payment detail array
    $new_payment = array(
    'wpum_payment_date' => array(array('value' => date('Y-m-d'))),
    'wpum_payment_type' => array(array('value' => 'Mynt')),
    'wpum_payment_amount' => array(array('value' => '50')),
    'wpum_payment_method' => array(array('value' => 'stripe')),
    'wpum_received_coins' => array(array('value' => '5'))
);

    // Add the new payment detail to the existing array
    $updated_payments = array_merge((array) $current_payments, array($new_payment));

    // Update the wpum_payments field with the modified array
    update_user_meta($user_id, 'wpum_payments', $updated_payments);
}