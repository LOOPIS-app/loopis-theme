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
$reward_info = loopis_ledger_user_rewards($user_id);
// Check if rewards exist
if (!empty($reward_info)) {
    // Loop through the rewards and output them
    foreach ($reward_info as $row) {
        // Access the stored values
        $reward_date = date('Y-m-d',strtotime($row['timestamp']));
        $reward_reason = loopis_ledger_type_output($row['type']);
        $reward_description = $row['description'];
        // Set $received_stars to 1 if no value exists or is empty
        $received_stars = $row['coins'] ?? 1;
        // Output the reward
        echo '<p>' . esc_html($reward_reason) . ' ' . esc_html($reward_description) . '<span class="plus right">+' . esc_html($received_stars) . '</span></p>';
    }
} else {
    // Output if no rewards are found
    echo '<p>☁ Inga stjärnor hittils.</p>';
}
