<?php
/**
 * Extra functions for raffle cronjob.
 *
 * Included for cronjob + admin in manual raffle starting.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** CRON: SWITCH */
// Post with 0 participants
function admin_action_switch(int $post_id) {
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'old', 'category' );
}

/** CRON: BOOK LOCKER */
// Post with 1 participant and locker fetching
function admin_action_book_locker(int $winner_id, int $post_id) {

	// Get variables
	$winner_name = get_user_by('ID', $winner_id)->display_name;
	$locker_code = get_locker_code(LOCKER_ID);
	$author_id = get_post_field('post_author', $post_id);
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'booked', 'category' );
	update_post_meta($post_id, 'fetcher', $winner_id);
	update_post_meta($post_id, 'book_date', current_time('Y-m-d H:i:s'));
	update_post_meta($post_id, 'raffle_date', current_time('Y-m-d H:i:s'));

	// Send notification from LOTTEN to winner	
	send_admin_notification_email ('🥳 Grattis @'.$winner_name.' – du har vunnit lottningen! <br>
	⌛ Du får ett meddelande när du kan hämta i skåpet.', $post_id, 11 ,$winner_id);
	
	// Send notification from LOTTEN to author
	send_admin_notification_email ('❤ '.$winner_name.' har vunnit lottningen! <br>
	⌛ Lämna gärna i skåpet inom 24 timmar @'.get_the_author().'. <br>
	🔓 Kod till skåpet: <b>'.$locker_code.'</b>', $post_id, 11, $author_id);
	
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
	$author_id = get_post_field('post_author', $post_id);
	$author_name = get_the_author_meta('display_name', $author_id);
	$author_phone = get_the_author_meta('wpum_phone', $author_id);
	$location = get_post_meta($post_id, 'location', true);
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'booked_custom', 'category' );
	update_post_meta($post_id, 'fetcher', $winner_id);
	update_post_meta($post_id, 'book_date', current_time('Y-m-d H:i:s'));
	update_post_meta($post_id, 'raffle_date', current_time('Y-m-d H:i:s'));
	
	// Send notification from LOTTEN to winner	
	send_admin_notification_email ('🥳 Grattis @'.$winner_name.' – du har vunnit lottningen! <br>
	📱 Du ska nu skicka ett sms till '.$author_name.' på '.$author_phone. ' för att komma överens om hämtning. <br>
	📍 '.$location.' är den angivna adressen.', $post_id, 11 , $winner_id);
	
	// Send notification from LOTTEN to author
	send_admin_notification_email ('❤ '.$winner_name.' har vunnit lottningen! <br>
	📱 '.$winner_name . ' ska nu skicka ett sms till dig för att komma överens om hämtning. <br>
	📍 ' . $location . ' är den angivna adressen @'.$author_name.'.', $post_id, 11, $author_id);
	
	// Leave comment by LOTTEN
	add_admin_comment ('<p class="lotten">🎲 Dags för lottning! Men vi har bara en deltagare... <br>
	❤ Grattis <span>🔔' . $winner_name . '</span> – du har paxat! <br>
	📱 Du ska nu skicka ett sms till <span>🔔'.$author_name.'</span> för att komma överens om hämtning.</p>', $post_id, 11 );
	
}

/** CRON: RAFFLE LOCKER */
// Post with 1+ participants and locker fetching
function admin_action_raffle_locker(array $participants, int $tickets, int $post_id) {

	// Raffle!
	$raffle = rand(0,$tickets -1);
	$winner_id = $participants[$raffle];
	$winner_name = get_user_by('ID', $winner_id)->display_name;
	$author_id = get_post_field('post_author', $post_id);
	$author_name = get_the_author_meta('display_name', $author_id);

	// Create queue
	unset($participants[$raffle]);					/* remove winner  */
	$participants = array_filter($participants);    /* remove gaps  */
	$participants = array_values($participants);    /* re-index */
	shuffle($participants);                         /* shuffle the array randomly */
	update_post_meta($post_id, 'queue', $participants);
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'booked', 'category' );
	update_post_meta($post_id, 'fetcher', $winner_id);
	update_post_meta($post_id, 'book_date', current_time('Y-m-d H:i:s'));
	update_post_meta($post_id, 'raffle_date', current_time('Y-m-d H:i:s'));
	
	// Send notification from LOTTEN to winner	
	send_admin_notification_email ('🥳 Grattis @'.$winner_name.' – du har vunnit lottningen! <br>
	⏳ Du får ett meddelande när du kan hämta i skåpet.', $post_id, 11, $winner_id);
	
	// Send notification from LOTTEN to author
	$locker_code = get_locker_code(LOCKER_ID);
	send_admin_notification_email ('❤ '.$winner_name.' har vunnit lottningen! <br>
	⌛ Lämna gärna i skåpet inom 24 timmar. <br>
	🔓 Kod till skåpet: <b>'.$locker_code.'</b> <br>
	🙏 Tack för att du loopar! @'.$author_name, $post_id, 11, $author_id);
	
	// Send notification from LOTTEN to losers
	foreach ($participants as $user_id) {
		if ($user_id == $winner_id) { continue; }
		$loser_id = $user_id;
		$loser_name = get_user_by('ID', $loser_id)->display_name;
		send_admin_notification_email ('💔 Du vann tyvärr inte lottningen @'.$loser_name.'. <br>
		🍀 '.$tickets.' personer deltog. Bättre lycka nästa gång!', $post_id, 11, $loser_id); } 
	
	// Leave comment by LOTTEN
	add_admin_comment ('<p class="lotten">🎲 Dags för lottning! '.$tickets.' personer deltar...<br>
	❤ Grattis <span>🔔'.$winner_name.'</span> du har vunnit lottningen!<br>
	⌛ Du får ett meddelande när du kan hämta i skåpet.<br>
	👫 Övriga deltagare har fått en lottad plats i kö.</p>', $post_id, 11);
	
}

/** CRON: RAFFLE CUSTOM */
// Post with 1+ participants and custom location
function admin_action_raffle_custom(array $participants, int $tickets, int $post_id) {

	// Raffle!
	$raffle = rand(0,$tickets -1);
	$winner_id = $participants[$raffle];
	$winner_name = get_user_by('ID', $winner_id)->display_name;
	
	// Create queue
	unset($participants[$raffle]);					/* remove winner  */
	$participants = array_filter($participants);    /* remove gaps  */
	$participants = array_values($participants);    /* re-index */
	shuffle($participants);                         /* shuffle the array randomly */
	update_post_meta($post_id, 'queue', $participants);
	
	// Get variables
	$author_id = get_post_field('post_author', $post_id);
	$author_name = get_the_author_meta('display_name', $author_id);
	$author_phone = get_the_author_meta('wpum_phone', $author_id);
	$location = get_post_meta($post_id, 'location', true);
	
	// Set post meta
	wp_set_object_terms( $post_id, null, 'category' ); 
	wp_set_object_terms( $post_id, 'booked_custom', 'category' );
	update_post_meta($post_id, 'fetcher', $winner_id);
	update_post_meta($post_id, 'book_date', current_time('Y-m-d H:i:s'));
	update_post_meta($post_id, 'raffle_date', current_time('Y-m-d H:i:s'));
	
	// Send notification from LOTTEN to winner	
	send_admin_notification_email ('🥳 Grattis @'.$winner_name.' – du har vunnit lottningen! <br>
	📱 Du ska nu skicka ett sms till '.$author_name.' på '.$author_phone. ' för att komma överens om hämtning på '.$location.'.', $post_id, 11, $winner_id);
	
	// Send notification from LOTTEN to author
	send_admin_notification_email ('❤ '.$winner_name.' har vunnit lottningen! <br>
	📱 Du ska få ett sms för att komma överens om hämtning på '.$location.'.<br>🙏 Tack för att du loopar! @' . $author_name, $post_id , 11, $author_id);
	
	// Send notification from LOTTEN to losers
	foreach ($participants as $user_id) {
		if ($user_id == $winner_id) { continue; }
		$loser_id = $user_id;
		$loser_name = get_user_by('ID', $loser_id)->display_name;
		send_admin_notification_email ('💔 Du vann tyvärr inte lottningen @'.$loser_name.'. <br>
		🍀 '.$tickets.' personer deltog. Bättre lycka nästa gång!', $post_id, 11, $loser_id); } 
	
	// Leave comment by LOTTEN
	add_admin_comment ('<p class="lotten">🎲 Dags för lottning! '.$tickets.' personer deltar...<br>
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