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

/** ADMIN/USER: SEND NOTIFICATION */
// Add comment from admin + delete
function send_admin_notification(string $comment_content, int $post_id, int $user_id) {

	// Get admin user data
	$user_data = get_userdata($user_id);

    // Avoid undefined-error in cron-job (by Poe)
    $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';

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