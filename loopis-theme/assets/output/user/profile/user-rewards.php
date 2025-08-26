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
$meta_values = get_user_meta($user_id, 'wpum_rewards', true);

    // Check if rewards exist
    if (!empty($meta_values)) {
        // Fetch the dropdown options dynamically
        global $wpdb;

        // Set the field ID for the dropdown field
        $field_id = 58;
        $dropdown_meta_key = 'dropdown_options';

        // Query the database to get the dropdown options
        $dropdown_options = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT meta_value FROM {$wpdb->prefix}wpum_fieldmeta WHERE wpum_field_id = %d AND meta_key = %s",
                $field_id,
                $dropdown_meta_key
            )
        );

        // Initialize the mapping array
        $reward_reason_labels = [];

        // If dropdown options are found, deserialize and build the mapping
        if (!empty($dropdown_options)) {
            $options = maybe_unserialize($dropdown_options);
            if (is_array($options)) {
                foreach ($options as $option) {
                    if (isset($option['value']) && isset($option['label'])) {
                        $reward_reason_labels[$option['value']] = $option['label'];
                    }
                }
            }
        }

        // Loop through the rewards and output them
foreach ($meta_values as $row) {
    // Access the stored values
    $reward_date = isset($row['wpum_reward_date'][0]['value']) ? $row['wpum_reward_date'][0]['value'] : '';
    $reward_reason_value = isset($row['wpum_reward_reason'][0]['value']) ? $row['wpum_reward_reason'][0]['value'] : '';
    $reward_description = isset($row['wpum_reward_description'][0]['value']) ? $row['wpum_reward_description'][0]['value'] : '';

    // Set $received_stars to 1 if no value exists or is empty
    $received_stars = !empty($row['wpum_received_stars'][0]['value']) 
        ? (int) $row['wpum_received_stars'][0]['value'] 
        : 1;

            // Map the reward reason value to its label
            $reward_reason_label = isset($reward_reason_labels[$reward_reason_value]) ? $reward_reason_labels[$reward_reason_value] : 'Unknown';

            // Output the reward
            echo '<p>' . esc_html($reward_reason_label) . ' ' . esc_html($reward_description) . '<span class="plus right">+' . esc_html($received_stars) . '</span></p>';
        }
    } else {
        // Output if no rewards are found
        echo '<p>☁ Inga stjärnor hittils.</p>';
    }
