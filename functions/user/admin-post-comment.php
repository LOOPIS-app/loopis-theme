<?php
/**
 * Comment functions for admin.
 *
 * Included for all users in functions.php
 * Included for cronjobs when needed
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** ADD ADMIN COMMENT */
// Add comment from admin
function add_admin_comment(string $comment_content, int $post_id, int $user_id) {
        
    // Get selected admin user data
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
        return $comment_id;
    } else {
        // There was an error adding the comment
        return false;
    }
}