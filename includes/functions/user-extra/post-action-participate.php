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
 * USER: PARTICIPATE
 * User clicks participate in raffle on post
 */
function action_participate(int $post_id) {
	
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
	$participants = get_post_meta($post_id, 'participants', true);
	if (!is_array($participants)) { 
		$participants = array(); 
	}
	$participants[] = $user_ID;
	update_post_meta($post_id, 'participants', $participants);
	
	// Leave comment by participant
	add_comment('<p class="participate">🧡 Jag vill delta i lottning.</p>', $post_id);
	
	// Refresh page
	refresh_page();
}

/**
 * USER: UNPARTICIPATE
 * User clicks unparticipate in raffle on post
 */
function action_unparticipate(int $post_id) {
	
	// Set post meta
	$current = get_current_user_id();
	$participants = get_post_meta($post_id, 'participants', true);
	$participants = array_diff($participants, array($current));
	update_post_meta($post_id, 'participants', $participants);
	
	// Leave comment by participant
	add_comment ('<p class="unparticipate">✋ Jag vill inte längre delta i lottning.</p>', $post_id );
	
	// Refresh page
    refresh_page();
}
