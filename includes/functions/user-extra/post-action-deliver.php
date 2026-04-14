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
* AUTHOR: DELIVER
* User clicks button when item is delivered to locker
*/	
function action_locker(int $post_id) {
	
	// Get user variables
	$fetcher = get_post_meta($post_id, 'fetcher', true);
	if ($fetcher) { $fetcher_name = get_userdata($fetcher)->display_name; } 
	
	// Get locker code
    $locker_code = get_locker_code(LOCKER_ID);
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'locker', 'category' );
	update_post_meta($post_id, 'locker_date', current_time('Y-m-d H:i:s'));
	
	// Send notification from LOOPIS to fetcher	
	send_admin_notification_email('🎁 Nu kan du hämta i skåpet @' . $fetcher_name . '! <br>⌛ Hämta gärna inom 24 timmar. <br>🔓 Kod till skåpet: <b>'.$locker_code.'</b>', $post_id, 1, $fetcher);
	
	// Leave comment by author
	add_comment ('<p class="locker">✅ Nu har jag lämnat i skåpet! <span>🔔' . $fetcher_name . '</span></p>', $post_id );
		
	// Refresh page
    refresh_page();
}

