<?php
/**
 * Add coins to a user's account.
 * 
 * Included in XXX.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function admin_action_add_coins(int $user_id) {
    // Retrieve the current wpum_payments field value
    $current_payments = get_user_meta($user_id, 'wpum_payments', true);
    if (!is_array($current_payments)) {
        $current_payments = array();
    }

    // Create the new payment detail array
    $new_payment = array(
    'wpum_payment_amount' => array(array('value' => '50')),
    'wpum_payment_date' => array(array('value' => current_time('Y-m-d'))),
    'wpum_payment_type' => array(array('value' => 'Mynt')),
    'wpum_payment_method' => array(array('value' => 'swish')),
    'wpum_received_coins' => array(array('value' => '5'))
);

    // Add the new payment detail to the existing array
    $updated_payments = array_merge((array) $current_payments, array($new_payment));

    // Update the wpum_payments field with the modified array
    update_user_meta($user_id, 'wpum_payments', $updated_payments);
}
