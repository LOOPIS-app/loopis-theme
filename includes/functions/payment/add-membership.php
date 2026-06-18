<?php
/**
 * Add membership entitlements after successful Stripe payment.
 *
 * Sets member role, stores payment entry, and sends welcome email.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function add_membership($user_id = null) {

    // Get user ID (either passed parameter or current logged-in user).
    if ($user_id === null) {
        $user_id = get_current_user_id();
    }

    // Get user data.
    $user = get_userdata($user_id);
    if (!$user) {
        error_log("LOOPIS: add_membership failed - User {$user_id} not found");
        return false;
    }

    // Upgrade role on the correct site context (main site in multisite).
    if (is_multisite()) {
        $main_site_id = function_exists('get_main_site_id') ? (int) get_main_site_id() : 1;
        if ($main_site_id <= 0) {
            $main_site_id = 1;
        }

        if (!is_user_member_of_blog((int) $user_id, $main_site_id)) {
            $added = add_user_to_blog($main_site_id, (int) $user_id, 'member');
            if (is_wp_error($added)) {
                error_log("LOOPIS: add_membership failed adding user {$user_id} to main site {$main_site_id}: " . $added->get_error_message());
                return false;
            }
        }

        switch_to_blog($main_site_id);
        $site_user = new WP_User((int) $user_id);
        if (!$site_user || 0 === (int) $site_user->ID) {
            restore_current_blog();
            error_log("LOOPIS: add_membership failed loading user {$user_id} on main site {$main_site_id}");
            return false;
        }

        $site_user->set_role('member');

        $updated_site_user = get_userdata((int) $user_id);
        if (!$updated_site_user || !in_array('member', (array) $updated_site_user->roles, true)) {
            restore_current_blog();
            error_log("LOOPIS: add_membership failed assigning role 'member' for user {$user_id} on main site {$main_site_id}");
            return false;
        }

        restore_current_blog();

        // Upgrade role on subsite (ID 2).
        $subsite_id = 2;
        if (!is_user_member_of_blog((int) $user_id, $subsite_id)) {
            add_user_to_blog($subsite_id, (int) $user_id, 'member');
        } else {
            switch_to_blog($subsite_id);
            $subsite_user = new WP_User((int) $user_id);
            if ($subsite_user && 0 !== (int) $subsite_user->ID) {
                $subsite_user->set_role('member');
            }
            restore_current_blog();
        }
    } else {
        $updated_user = wp_update_user(array(
            'ID' => $user_id,
            'role' => 'member',
        ));

        if (is_wp_error($updated_user)) {
            error_log("LOOPIS: add_membership failed updating role for user {$user_id}: " . $updated_user->get_error_message());
            return false;
        }

        $updated_user_data = get_userdata((int) $user_id);
        if (!$updated_user_data || !in_array('member', (array) $updated_user_data->roles, true)) {
            error_log("LOOPIS: add_membership failed assigning role 'member' for user {$user_id}");
            return false;
        }
    }

    // Add payment.
    $current_payments = get_user_meta($user_id, 'wpum_payments', true);
    if (!is_array($current_payments)) {
        $current_payments = array();
    }

    $current_payments[] = array(
        'wpum_payment_date' => array(array('value' => date('Y-m-d'))),
        'wpum_payment_type' => array(array('value' => 'Medlemskap')),
        'wpum_payment_method' => array(array('value' => 'stripe')),
        'wpum_payment_amount' => array(array('value' => '50')),
        'wpum_received_coins' => array(array('value' => '5')),
    );

    update_user_meta($user_id, 'wpum_payments', $current_payments);
    loopis_ledger_add_payment($user_id, ['type' => 'medlemskap']);
    // Get the email templates from the options.
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

    // Replace [user_first_name] with the actual first name.
    $email_content = str_replace('[user_first_name]', $user->first_name, $email_content);

    // Send the welcome email.
    $to = $user->user_email;
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($to, $subject, $email_content, $headers);

    return true;
}
