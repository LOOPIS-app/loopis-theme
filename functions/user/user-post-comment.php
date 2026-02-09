<?php
/**
 * Comment functions for user.
 *
 * Included for all users in functions.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** ADD USER COMMENT */
// Add comment from user
function add_comment(string $comment_content, int $post_id) {

	// Get current user data
	$current_user = wp_get_current_user();
    
    // Remove tabs and extra whitespace (by CoPilot 2025-12-04)
    $comment_content = preg_replace('/\t+/', '', $comment_content); // Remove all tabs
    $comment_content = preg_replace('/\n\s+/', "\n", $comment_content); // Remove leading spaces on new lines

	// Set up comment data
	$comment_data = array(
            'comment_post_ID' => $post_id,
            'comment_author' => $current_user->display_name,
            'comment_author_email' => $current_user->user_email,
			'comment_author_url' => $current_user->user_url,
	        'comment_content' => $comment_content,
	        'comment_type' => '',
    	    'comment_parent' => 0,
			'user_id' => $current_user->ID,
	        'comment_approved' => '1',
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