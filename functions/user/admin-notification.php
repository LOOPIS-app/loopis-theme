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

/** SEND ADMIN NOTIFICATION */
// Add comment from admin + delete
function send_admin_notification(string $comment_content, int $post_id, int $user_id) {

    // Get admin user data
    $user_data = get_userdata($user_id);

    // Avoid undefined-error in cron-job (by Poe)
    $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';

    // Remove tabs and extra whitespace (by CoPilot 2025-12-04)
    $comment_content = preg_replace('/\t+/', '', $comment_content); // Remove all tabs
    $comment_content = preg_replace('/\n\s+/', "\n", $comment_content); // Remove leading spaces on new lines

    // Set up comment data
    $comment_data = array(
        'comment_post_ID' => $post_id,
        'comment_author' => $user_data->display_name,
        'comment_author_email' => $user_data->user_email,
        'comment_author_url' => $user_data->user_url,
        'comment_content' => $comment_content,
        'comment_type' => '',
        'comment_parent' => 0,
        'user_id' => $user_data->ID,
        'comment_approved' => '1',
        'comment_author_IP' => $remote_addr,
    );

    // Disable all comment content filtering
    add_filter('pre_comment_content', function($content) use ($comment_content) {
        return $comment_content;
    }, 999);

    // Post comment
    $comment_id = wp_new_comment($comment_data, true);

    // Restore the default comment content filtering
    remove_filter('pre_comment_content', function($content) use ($comment_content) {
        return $comment_content;
    }, 999);
    
    if ($comment_id) {
        // Comment was successfully added
        wp_delete_comment($comment_id, true);
    } else {
        // There was an error adding the comment
        return false;
    }
}

/** SEND ADMIN NOTIFICATION EMAIL */
// the "This could have been an email" fix :)
function send_admin_notification_email(string $email_content, int $post_id, int $admin_id, int $recipient_id) {

    // Get data
    $admin = get_userdata($admin_id);
    $admin_name = $admin ? $admin->display_name : '';
    $recipient = get_userdata($recipient_id);
    $to = $recipient ? $recipient->user_email : '';
    $post_link = get_permalink( $post_id );
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
    $the_email = '<div style="padding: 10px;font-size: 18px;font-style: italic;background: #f5f5f5;border-radius: 10px">'.$email_content.'</div>
        <p style="font-size: 14px"><strong>'.$admin_name.'</strong> pingade dig → <a href="'.$post_link.'">'.$post_title.'</a></p>

        <table style="border-collapse: collapse;border-top: 1px solid">
        <tbody>
        <tr>
        <td style="padding: 5 5 0 0"><img style="height: 32px" src="https://loopis.app/wp-content/themes/loopis-theme/assets/img/LOOPIS_icon.png" alt="LOOPIS_logo" /></td>
        <td style="padding: 5 10 0 0">
        <p style="font-size: 11px;font-style: italic;margin: 0;line-height: 1.2">Gå till LOOPIS.app för att hantera annonsen eller skriva ett svar.</p>
        </td>
        </tr>
        </tbody>
        </table>';

    $headers = array(
        'From: LOOPIS <info@loopis.app>',
        'Content-Type: text/html; charset=UTF-8',
        'Content-Language: sv-SE',
        'X-Emoji-Service: twemoji'
            );
    // Send email
    wp_mail($to, $subject, $the_email, $headers); 
}