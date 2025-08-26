<?php
/**
 * Post handling functions for LOOPIS user.
 *
 * Included in activity.php
 * Included in comments.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** 
* AUTHOR: DELIVERED
* User clicks button when item is delivered to locker
*/	
function action_locker(int $post_id) {
	
	// Get variables
	$fetcher = get_post_meta($post_id, 'fetcher', true);
		if ($fetcher) { $fetcher_name = get_userdata($fetcher)->display_name; } 
	$code_001 = do_shortcode('[code_snippet id=93]');
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'locker', 'category' );
	update_field('locker_date', current_time('Y-m-d H:i:s'));
	
	// Send notification from LOOPIS to fetcher	
	send_admin_notification ('🎁 Nu kan du hämta i skåpet @' . $fetcher_name . '! <br>⌛ Hämta gärna inom 24 timmar. <br>🔓 Kod till skåpet: <b>'.$code_001.'</b>', $post_id, 1);
	
	// Leave comment by author
	add_comment ('<p class="locker">✅ Nu har jag lämnat i skåpet! <span>🔔' . $fetcher_name . '</span></p>', $post_id );
		
	// Refresh page
    refresh_page();
}

/** 
* FETCHER: FETCHED
* Fetcher clicks button when item is fetched (from locker or custom location)
*/	
function action_fetched(int $post_id) {
	
	// Set post meta
	wp_set_object_terms($post_id, null, 'category');
	wp_set_object_terms($post_id, 'fetched', 'category'); 
	update_field('fetch_date', current_time('Y-m-d H:i:s'));
		
	// Leave comment by fetcher
	add_comment ('<p class="fetched">☑ Nu har jag hämtat! <span>🔔LOOPIS</span></p>', $post_id );
		
	// Refresh page
    refresh_page();
}

/** 
* AUTHOR: FETCHED
* Author clicks button when item is fetched (from custom location)
*/	
function action_fetched_custom(int $post_id) {

	// Set post meta
	wp_set_object_terms($post_id, null, 'category');
	wp_set_object_terms($post_id, 'fetched', 'category'); 
	update_field('fetch_date', current_time('Y-m-d H:i:s'));
		
	// Get fetcher name
	$fetcher = get_post_meta($post_id, 'fetcher', true); if ($fetcher) { $fetchername = get_userdata($fetcher)->display_name; } 
		
	// Leave comment by author
	add_comment ('<p class="fetched">☑ Nu har ' . $fetchername . ' hämtat! <span>🔔LOOPIS</span></p>', $post_id );
		
	// Refresh page
    refresh_page();
}