<?php
/**
 * Automatic activation of new account initiated by Stripe payment.
 * 
 * Setting username, display name, role, adding payment and sending welcome email.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function activate_account($user_id = null) {

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

    // Create username
    $first_name = str_replace(' ', '-', $user->first_name);
    $last_name = str_replace(' ', '-', $user->last_name);
    $new_username = sanitize_user($first_name . '-' . $last_name);
    $new_nicename = sanitize_title($new_username);

    // Add suffix if username is taken
    $suffix = 2;
    $original_new_username = $new_username;
    while (username_exists($new_username) || get_user_by('slug', $new_nicename)) {
        $new_username = $original_new_username . '-' . $suffix;
        $new_nicename = sanitize_title($new_username);
        $suffix++;
    }

    // Update the user's details using a custom SQL query
    global $wpdb;
    $wpdb->update(
        $wpdb->users,
        ['user_login' => $new_username],
        ['ID' => $user_id]
    );

    // Update the user's role, nicename, and display_name
    $updated_user = wp_update_user([
        'ID' => $user_id,
        'user_nicename' => $new_nicename,
        'display_name' => $new_username,
        'role' => 'member',
    ]);

    // Add payment
    update_user_meta($user_id, 'wpum_payments', array(array(
        'wpum_payment_date' => array(array('value' => date('Y-m-d'))),
        'wpum_payment_type' => array(array('value' => 'Medlemskap')),
        'wpum_payment_method' => array(array('value' => 'stripe')),
        'wpum_payment_amount' => array(array('value' => '50')),
        'wpum_received_coins' => array(array('value' => '5'))
    )));

    // Get the email templates from the options
    $subject = loopis_get_setting('welcome_email_subject', 'Content missing...');
    $greeting = loopis_get_setting('welcome_email_greeting', 'Content missing...');
    $message = loopis_get_setting('welcome_email_message', 'Content missing...');
    $footer = loopis_get_setting('welcome_email_footer', 'Content missing...');

    $email_content = <<<EOT
    <!DOCTYPE html>
    <html>
    <head>
    <title>{$subject}</title>
    </head>
    <body>
    <div style="text-align: center;">
    <h1 style="font-size: 24px;">{$greeting}</h1>
    </div>
    <div style="padding: 10px;margin-bottom: 20px;text-align: center; font-size: 18px;background: #f5f5f5;border-radius: 10px">
    {$message}
    </div>
    {$footer}
    </body>
    </html>
    EOT;

    // Replace [user_first_name] with the actual first name
    $email_content = str_replace('[user_first_name]', $user->first_name, $email_content);

    // Send the activation email
    $to = $user->user_email;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($to, $subject, $email_content, $headers);

    return true;
}