<?php
/**
 * Tool for rewarding members
 * 
 * IMPROVEMENTS
 * - Check css styling of form
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🌟 Belöna medlemmar</h1>
<hr>
<p class="small">💡 Verktyg för att dela ut guldstjärnor.</p>

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
                loopis_ledger_add_reward($user_id, [
                    'type' => $reward_reason,
                    'description' =>  $reward_description,
                    'coins' => $received_stars,
                ]);

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
<form method="post">
    <label>E-postadresser (en per rad):</label>
    <textarea name="email_list" rows="10" cols="50" placeholder="user1@example.com&#10;user2@example.com&#10;user3@example.com"></textarea>

    <label>Datum:</label>
    <input type="date" name="reward_date" required><br>

    <label>Beskrivning:</label>
    <input type="text" name="reward_description" placeholder="2024...">

    <label>Antal stjärnor:</label>
    <input type="number" name="received_stars" placeholder="1..." required>

    <label>Orsak:</label>
    <input type="text" name="reward_reason" placeholder="survey...?" required>

    <button type="submit" class="blue">Belöna!</button>
</form>