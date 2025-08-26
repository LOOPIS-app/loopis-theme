<?php
/**
 * Output user payments.
 *
 * Used in user-summary.php > author.php
 * Used in wpum/profiles/coins.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Uses $user_id passed from author.php
$meta_values = get_user_meta($user_id, 'wpum_payments', true);

if (!empty($meta_values)) {
            foreach ($meta_values as $row) {
            $payment_date = $row['wpum_payment_date'][0]['value'];
            $payment_type = $row['wpum_payment_type'][0]['value'];
            $payment_amount = $row['wpum_payment_amount'][0]['value'];
            $payment_method = $row['wpum_payment_method'][0]['value'];
            // Output
            echo '<p><span class="label grey"><i class="fas fa-receipt"></i>' . esc_html($payment_date) . ': ' . esc_html($payment_type) . ' - ' . esc_html($payment_amount) . 'kr (' . esc_html($payment_method) . ')</span></p>';
        }
    } else {
        // Output
        echo '<p>💢 Hittade inga betalningar.</p>';
    }