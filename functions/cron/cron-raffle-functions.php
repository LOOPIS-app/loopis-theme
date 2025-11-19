<?php
/**
 * Extra functions for raffle cronjob.
 *
 * Included for everyone in functions.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** CRON: SWITCH */
// Post with 0 participants
function admin_action_switch(int $post_id) {
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'first', 'category' );
}

/** CRON: BOOK LOCKER */
// Post with 1 participant and locker fetching
function admin_action_book_locker(int $winner_id, int $post_id) {

	// Get variables
	$winner_name = get_user_by('ID', $winner_id)->display_name;
	$code_001 = do_shortcode('[code_snippet id=93]');
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'booked_locker', 'category' );
	update_field('fetcher', $winner_id);
	update_field('book_date', current_time('Y-m-d H:i:s'));
	update_field('raffle_date', current_time('Y-m-d H:i:s'));
	
	// Send notification from LOTTEN to winner	
	send_admin_notification ('🥳 Grattis @'.$winner_name.' – du har vunnit lottningen! <br>
	⌛ Du får ett meddelande när du kan hämta i skåpet.', $post_id, 11 );
	
	// Send notification from LOTTEN to author
	send_admin_notification ('❤ '.$winner_name.' har vunnit lottningen! <br>
	⌛ Lämna gärna i skåpet inom 24 timmar @'.get_the_author().'. <br>
	🔓 Kod till skåpet: <b>'.$code_001.'</b>', $post_id, 11 );
	
	// Leave comment by LOTTEN
	add_admin_comment ('<p class="lotten">🎲 Dags för lottning! Men vi har bara en deltagare... <br>
	❤ Grattis <span>🔔' . $winner_name . '</span> – du har paxat! <br>
	⏳ Du får ett meddelande när du kan hämta i skåpet.</p>', $post_id, 11 );
	
}

/** CRON: BOOK CUSTOM */
// Post with 1 participant and custom location
function admin_action_book_custom(int $winner_id, int $post_id) {

	// Get variables
	$winner_name = get_user_by('ID', $winner_id)->display_name;
	$author_name = get_the_author_meta('display_name');
	$author_phone = get_the_author_meta('wpum_phone');
	$location = get_field('location');
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'booked_custom', 'category' );
	update_field('fetcher', $winner_id);
	update_field('book_date', current_time('Y-m-d H:i:s'));
	update_field('raffle_date', current_time('Y-m-d H:i:s'));
	
	// Send notification from LOTTEN to winner	
	send_admin_notification ('🥳 Grattis @'.$winner_name.' – du har vunnit lottningen! <br>
	📱 Du ska nu skicka ett sms till '.$author_name.' på '.$author_phone. ' för att komma överens om hämtning. <br>
	📍 '.$location.' är den angivna adressen.', $post_id, 11 );
	
	// Send notification from LOTTEN to author
	send_admin_notification ('❤ '.$winner_name.' har vunnit lottningen! <br>
	📱 '.$winner_name . ' ska nu skicka ett sms till dig för att komma överens om hämtning. <br>
	📍 ' . $location . ' är den angivna adressen @' . get_the_author() . '.', $post_id, 11);
	
	// Leave comment by LOTTEN
	add_admin_comment ('<p class="lotten">🎲 Dags för lottning! Men vi har bara en deltagare... <br>
	❤ Grattis <span>🔔' . $winner_name . '</span> – du har paxat! <br>
	📱 Du ska nu skicka ett sms till <span>🔔'.$author_name.'</span> för att komma överens om hämtning.</p>', $post_id, 11 );
	
}

/** CRON: RAFFLE LOCKER */
// Post with 1+ participants and locker fetching
function admin_action_raffle_locker(array $participants, int $count, int $post_id) {

	// Raffle!
	$raffle = rand(0,$count -1);
	$winner_id = $participants[$raffle];
	$winner_name = get_user_by('ID', $winner_id)->display_name;
	
	// Create queue
	unset($participants[$raffle]);					/* remove winner  */
	$participants = array_filter($participants);    /* remove gaps  */
	$participants = array_values($participants);    /* re-index */
	shuffle($participants);                         /* shuffle the array randomly */
	update_post_meta($post_id, 'queue', $participants);
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'booked_locker', 'category' );
	update_field('fetcher', $winner_id);
	update_field('book_date', current_time('Y-m-d H:i:s'));
	update_field('raffle_date', current_time('Y-m-d H:i:s'));
	
	// Send notification from LOTTEN to winner	
	send_admin_notification ('🥳 Grattis @'.$winner_name.' – du har vunnit lottningen! <br>
	⏳ Du får ett meddelande när du kan hämta i skåpet.', $post_id, 11);
	
	// Send notification from LOTTEN to author
	$code_001 = do_shortcode('[code_snippet id=93]');
	send_admin_notification ('❤ '.$winner_name.' har vunnit lottningen! <br>
	⌛ Lämna gärna i skåpet inom 24 timmar. <br>
	🔓 Kod till skåpet: <b>'.$code_001.'</b> <br>
	🙏 Tack för att du loopar! @'.get_the_author(), $post_id, 11);
	
	// Send notification to losers
	foreach ($participants as $user_id) {
		if ($user_id == $winner_id) { continue; }
		$loser_id = get_userdata($user_id);
		$loser_name = $loser_id->display_name;
		send_admin_notification ('💔 Du vann tyvärr inte lottningen @'.$loser_name.'. <br>
		🍀 '.$count.' personer deltog. Bättre lycka nästa gång!', $post_id, 11); } 
	
	// Leave comment by LOTTEN
	add_admin_comment ('<p class="lotten">🎲 Dags för lottning! '.$count.' personer deltar...<br>
	❤ Grattis <span>🔔'.$winner_name.'</span> du har vunnit lottningen!<br>
	⌛ Du får ett meddelande när du kan hämta i skåpet.<br>
	👫 Övriga deltagare har fått en lottad plats i kö.</p>', $post_id, 11);
	
}

/** CRON: RAFFLE CUSTOM */
// Post with 1+ participants and custom location
function admin_action_raffle_custom(array $participants, int $count, int $post_id) {

	// Raffle!
	$raffle = rand(0,$count -1);
	$winner_id = $participants[$raffle];
	$winner_name = get_user_by('ID', $winner_id)->display_name;
	
	// Create queue
	unset($participants[$raffle]);					/* remove winner  */
	$participants = array_filter($participants);    /* remove gaps  */
	$participants = array_values($participants);    /* re-index */
	shuffle($participants);                         /* shuffle the array randomly */
	update_post_meta($post_id, 'queue', $participants);
	
	// Get variables
	$author_name = get_the_author_meta('display_name');
	$author_phone = get_the_author_meta('wpum_phone');
	$location = get_field($post_id, 'location', true);
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'booked_custom', 'category' );
	update_field('fetcher', $winner_id);
	update_field('book_date', current_time('Y-m-d H:i:s'));
	update_field('raffle_date', current_time('Y-m-d H:i:s'));
	
	// Send notification from LOTTEN to winner	
	send_admin_notification ('🥳 Grattis @'.$winner_name.' – du har vunnit lottningen! <br>
	📱 Du ska nu skicka ett sms till '.$author_name.' på '.$author_phone. ' för att komma överens om hämtning på '.$location.'.', $post_id, 11);
	
	// Send notification from LOTTEN to author
	send_admin_notification ('❤ '.$winner_name.' har vunnit lottningen! <br>
	📱 Du ska få ett sms för att komma överens om hämtning på '.$location.'.<br>🙏 Tack för att du loopar! @' . $author_name, $post_id , 11);
	
	// Send notification from LOTTEN to losers
	foreach ($participants as $user_id) {
		if ($user_id == $winner_id) { continue; }
		$loser_id = get_userdata($user_id);
		$loser_name = $loser_id->display_name;
		send_admin_notification ('💔 Du vann tyvärr inte lottningen @'.$loser_name.'. <br>
		🍀 '.$count.' personer deltog. Bättre lycka nästa gång!', $post_id, 11); } 
	
	// Leave comment by LOTTEN
	add_admin_comment ('<p class="lotten">🎲 Dags för lottning! '.$count.' personer deltar...<br>
	❤ Grattis <span>🔔'.$winner_name.'</span> du har vunnit lottningen!<br>
	📱 Du ska nu skicka ett sms till <span>🔔'.$author_name.'</span> för att komma överens om hämtning.<br>
	👫 Övriga deltagare har fått en lottad plats i kö.</p>', $post_id, 11);
	
}

/** CRON: ERASE */
// Post removed by user before raffle
function admin_action_erase($post_id) {

    // Move the post to the trash
    wp_trash_post($post_id);
}