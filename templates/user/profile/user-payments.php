<?php
/**
 * Output user payments.
 *
 * Used in wpum/profile/economy.php and author.php
 * $user_id has to be passed from context!
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Uses $user_id passed from wpum/profile/economy.php or from author.php
$payment_info = loopis_ledger_user_payments($user_id);

if (!empty($payment_info)) {
        foreach ($payment_info as $row) {
            $payment_date = date('Y-m-d',strtotime($row['timestamp']));
            $payment_type = loopis_ledger_type_output($row['type']);
            $payment_amount = $row['payment'];
            $payment_method = $row['description'];
            // Output
            echo '<p><span class="label grey"><i class="fas fa-receipt"></i>' . esc_html($payment_date) . ': ' . esc_html($payment_type) . ' - ' . esc_html($payment_amount) . 'kr (' . esc_html($payment_method) . ')</span></p>';
        }
    } else {
        // Output
        echo '<p>💢 Hittade inga betalningar.</p>';
    }