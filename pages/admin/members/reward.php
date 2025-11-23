<?php
/**
 * Tool for rewarding members
 * Should be improved.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🌟 Belöna medlemmar</h1>
<hr>
<p class="small">💡 Verktyg för att plocka ut e-postadresser till medlemmar.</p>

<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email_list'])) {
    // Get the email list from the form
    $email_list = sanitize_textarea_field($_POST['email_list']);

    // Convert the email list into an array
    $emails = array_filter(array_map('trim', explode("\n", $email_list)));

    // Get reward data from the form
    $reward_date = sanitize_text_field($_POST['reward_date']);
    $reward_description = sanitize_text_field($_POST['reward_description']);
    $received_stars = sanitize_text_field($_POST['received_stars']);
    $reward_reason = sanitize_text_field($_POST['reward_reason']);

    // Function to add rewards to users
    function add_rewards_to_users($emails, $reward_date, $reward_description, $received_stars, $reward_reason) {
        global $wpdb;

        foreach ($emails as $email) {
            // Get the user by email
            $user = get_user_by('email', $email);

            if ($user) {
                $user_id = $user->ID;
                $username = $user->user_login; // Get the username

                // Get the existing rewards from the usermeta table
                $existing_rewards = get_user_meta($user_id, 'wpum_rewards', true);

                // Unserialize the existing rewards if they are serialized
                if (!empty($existing_rewards) && is_serialized($existing_rewards)) {
                    $existing_rewards = unserialize($existing_rewards);
                }

                // If no rewards exist, initialize an empty array
                if (!is_array($existing_rewards)) {
                    $existing_rewards = [];
                }

                // Add the new reward to the array
                $new_reward = [
                    'value' => '_', // Placeholder
                    'wpum_reward_date' => [
                        [
                            'value' => $reward_date,
                        ],
                    ],
                    'wpum_reward_description' => [
                        [
                            'value' => $reward_description,
                        ],
                    ],
                    'wpum_received_stars' => [
                        [
                            'value' => $received_stars,
                        ],
                    ],
                    'wpum_reward_reason' => [
                        [
                            'value' => $reward_reason,
                        ],
                    ],
                ];

                // Append the new reward to the existing rewards
                $existing_rewards[] = $new_reward;

                // Update the usermeta with the raw array (WordPress will handle serialization)
                update_user_meta($user_id, 'wpum_rewards', $existing_rewards);

                echo "<p style='color: green;'>Reward added for user: {$email} (Username: {$username})</p>";
            } else {
                echo "<p style='color: red;'>User not found for email: {$email}</p>";
            }
        }
    }

    // Call the function to add rewards
    add_rewards_to_users($emails, $reward_date, $reward_description, $received_stars, $reward_reason);
}
?>

<!-- HTML Form -->
<form method="post" style="margin-top: 20px;">
    <label for="email_list" style="font-size: 16px; font-weight: bold;">Enter Email Addresses (one per line):</label><br>
    <textarea name="email_list" id="email_list" rows="10" cols="50" style="width: 100%; margin-top: 10px;" placeholder="user1@example.com&#10;user2@example.com&#10;user3@example.com"></textarea><br>

    <label for="reward_date" style="font-size: 16px; font-weight: bold; margin-top: 10px;">Reward Date:</label><br>
    <input type="date" name="reward_date" id="reward_date" style="width: 100%; margin-top: 10px;" required><br>

    <label for="reward_description" style="font-size: 16px; font-weight: bold; margin-top: 10px;">Reward Description:</label><br>
    <input type="text" name="reward_description" id="reward_description" style="width: 100%; margin-top: 10px;" placeholder="e.g., 2024"><br>

    <label for="received_stars" style="font-size: 16px; font-weight: bold; margin-top: 10px;">Received Stars:</label><br>
    <input type="number" name="received_stars" id="received_stars" style="width: 100%; margin-top: 10px;" placeholder="e.g., 1" required><br>

    <label for="reward_reason" style="font-size: 16px; font-weight: bold; margin-top: 10px;">Reward Reason:</label><br>
    <input type="text" name="reward_reason" id="reward_reason" style="width: 100%; margin-top: 10px;" placeholder="e.g., survey" required><br>

    <button type="submit" style="margin-top: 20px; padding: 10px 20px; font-size: 16px; background-color: #0073aa; color: white; border: none; cursor: pointer;">Add Rewards</button>
</form>