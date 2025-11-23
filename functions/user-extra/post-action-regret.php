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
 * FETCHER: REGRET
 * Fetcher clicks regret on booked post
 */
function action_regret(int $post_id) {
	
	// Get variables
	$fetcher = get_post_meta($post_id, 'fetcher', true);
	$fetcher_name = get_userdata($fetcher)->display_name; 
	$locker_code = get_locker_code(LOCKER_ID);
	
	// Count queue
	$queue = get_post_meta($post_id, 'queue', true);
	if (!empty($queue)) { $queue_count = count($queue);
	} else { $queue_count = 0; }
		
	// Change post meta
	update_field('fetcher', null);
	update_field('book_date', null);
		
	// Leave comment by fetcher
	add_comment ('<p class="regret">💔 Jag har ångrat mig... <span>🔔LOOPIS</span></p>', $post_id );
	
	// No queue
	if ($queue_count == 0) {
		
	// Send notification from LOOPIS to author	
	send_admin_notification('💔 ' . $fetcher_name . ' har ångrat sig och ingen stod i kö. <br>⏳ Du får behålla tills vidare @' . get_the_author() , $post_id, 1); 
	
	// Leave comment by LOOPIS
	add_admin_comment ('<p class="unremove">🟢 Tillgänglig för andra att paxa. <br>⏳ Du får behålla tills vidare <span>🔔' . get_the_author() . '</span></p>', $post_id, 1); 
		
	// Set category
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'first', 'category' );
	}
	
	// Queue exists
	if ($queue_count > 0) {
	
	// Pick next in queue
	$fetcher = $queue[0];							// Pick first user ID in queue
	array_shift($queue);   							// Remove first user ID from queue
	update_post_meta($post_id, 'queue', $queue); 	// Update queue
	
	// Change post meta & variables
	update_field('fetcher', $fetcher);
	update_field('book_date', current_time('Y-m-d H:i:s'));
	$fetcher_name = get_userdata($fetcher)->display_name;
	
	// Category is 'booked_locker'
	if (has_category( 'booked_locker', $post_id)) {
	
	// Send notification from LOOPIS to fetcher
	send_admin_notification('💔 Mottagaren har ångrat sig och... <br>❤ Du stod först i kön @' . $fetcher_name . ' ! <br>⌛ Du får ett meddelanden när du kan hämta i skåpet.', $post_id, 1); 
	
	// Send notification from LOOPIS to author	
	send_admin_notification('💔 Mottagaren har ångrat sig men... <br>❤ ' . $fetcher_name . ' stod i kö och har nu paxat! <br>⌛ Lämna gärna i skåpet inom 24 timmar @' . get_the_author() . '.<br>🔓 Kod till skåpet: <b>' . $locker_code . '</b>', $post_id, 1);
	
	// Leave comment by LOOPIS
	add_admin_comment ('<p class="book">❤ Paxad av <span>🔔' . $fetcher_name . '</span> som stod först i kön. <br>⌛ Du får ett meddelanden när du kan hämta i skåpet.</p>', $post_id, 1); 
	}
		
	// Category is 'booked_custom'
	if (has_category( 'booked_custom', $post_id)) {
	
	// Get variables
	$author_name = get_the_author_meta('first_name');
	$author_phone = get_the_author_meta('wpum_phone');
	$custom_location = get_post_meta($post_id, 'custom_location', true);
		
	// Send notification from LOOPIS to fetcher
	send_admin_notification('💔 Mottagaren har ångrat sig och... <br>❤ Du stod först i kön @' . $fetcher_name . '! <br>📲 Du ska nu skicka ett sms till ' .$author_name. ' på ' .$author_phone. ' för att komma överens om hämtning på ' .$custom_location. '.', $post_id, 11);
	
	// Send notification from LOOPIS to author	
	send_admin_notification('💔 Mottagaren har ångrat sig men... <br>❤ ' . $fetcher_name . ' stod i kö och har nu paxat! <br>⌛ ' . $fetcher_name . ' ska nu skicka ett sms till dig för att komma överens om hämtning på ' . $custom_location . ' @' . get_the_author() . '.', $post_id, 1); 
	
	// Leave comment by LOOPIS
	add_admin_comment ('<p class="book">❤ Paxad av <span>🔔' . $fetcher_name . '</span> som stod först i kön. <br>📱 Du ska nu skicka ett sms till <span>🔔'.$author_name.'</span> för att komma överens om hämtning.</p>', $post_id, 11 );
	}
	
	// Already in locker?
	if (has_category( 'locker', $post_id)) {
	
	// Change post meta
	update_field('locker_date', current_time('Y-m-d H:i:s'));
	
	// Send notification from LOOPIS to fetcher
	send_admin_notification('💔 Mottagaren har ångrat sig och... <br>❤ Du stod först i kön @' . $fetcher_name . ' ! <br>⌛ Du  bör hämta i skåpet inom 24 timmar. <br>🔓 Kod till skåpet: <b>' . $locker_code . '</b>', $post_id, 1); 
	
	// Leave comment by LOOPIS
	add_admin_comment('💔 Mottagaren har ångrat sig men... <br>❤ ' . $fetcher_name . ' stod i kö och har nu paxat! ', $post_id, 1); 
	} 
		
	}
	
	// Refresh page
    refresh_page();
}