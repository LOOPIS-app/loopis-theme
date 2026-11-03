<?php
/**
 * Notification function for user/admin.
 *
 * Included for user in functions.php
 * Included for cron in all cron jobs
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

loopis_get_mail_templates();

/** SEND ADMIN NOTIFICATION EMAIL */
// the "This could have been an email" fix by Hubert :)
function send_admin_notification_email(string $email_content, int $post_id, int $admin_id, int $recipient_id) {

    // Get data
    $admin = get_userdata($admin_id);
    $admin_name = $admin ? $admin->display_name : '';
    $recipient = get_userdata($recipient_id);
    $to = $recipient ? $recipient->user_email : '';
    $post_link = get_permalink( $post_id );
    $location_name = get_post_meta($post_id, 'location', true );
    $location_name = ($location_name === 'Skåpet') ? get_locker_data('name') : $location_name;
    $post_title = html_entity_decode(
        html_entity_decode(get_the_title($post_id), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        ENT_QUOTES | ENT_HTML5,
        'UTF-8'
    );

    // Avoid undefined-error in cron-job (by Poe) 
    $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';

    // Remove tabs and extra whitespace (by CoPilot 2025-12-04)
    $email_content = preg_replace('/\t+/', '', $email_content); // Remove all tabs
    $email_content = preg_replace('/\n\s+/', "\n", $email_content); // Remove leading spaces on new lines
    $subject = "🔔 {$post_title}";

    // Set content in email form
    $headers = loopis_mail_headers();
    $outro = array(loopis_mail_ping($admin_name, $post_link, $post_title), loopis_mail_location($location_name));
    $the_email = loopis_mail_template('',$outro,$email_content);
    $the_email .= loopis_mail_footer('Gå till LOOPIS.app för att hantera annonsen eller skriva ett svar.');

    // Send email
    wp_mail($to, $subject, $the_email, $headers); 
}