<?php
/**
 * Post handling functions for user.
 *
 * Included where needed.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * USER: QUEUE
 * User clicks queue on booked post
 */
function action_queue(int $post_id) {
	
	// Check economy		
	$user_ID = get_current_user_id();
	$profile_economy = get_economy($user_ID);
	$coins = $profile_economy['coins'];
	if ($coins < 1) { 
		include LOOPIS_THEME_DIR . '/templates/access/no-coins.php'; 
		echo '<script src="' . LOOPIS_THEME_DIR . '/assets/js/scroll-to-warning.js"></script>';
		return; 
	}
	
	// Set post meta
	$queue = get_post_meta($post_id, 'queue', true);
		if (!is_array($queue)) { $queue = array(); } 
	$queue[] = $user_ID;
	update_post_meta($post_id, 'queue', $queue);
	
	// Leave comment by user
	add_comment ('<p class="queue">👫 Jag köar om mottagaren skulle ångra sig.</p>', $post_id );
		
	// Refresh page
    refresh_page();
}

/**
 * USER: UNQUEUE
 * User clicks leave queue on booked post
 */
function action_unqueue(int $post_id) {
	$current = get_current_user_id();

	// Set post meta
	$queue = get_post_meta($post_id, 'queue', true);
	$queue = array_diff($queue, array($current));
	update_post_meta($post_id, 'queue', $queue);

	// Leave comment by user
	add_comment ('<p class="unqueue">✋ Jag lämnar kön.</p>', $post_id );

	// Refresh page
    refresh_page();
}
