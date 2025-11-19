<?php
/**
 * Post handling functions for admin/manager.
 *
 * Included where needed.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** ADMIN: RAFFLED */
// Admin clicks "Skicka besked" on post
function admin_action_notif_manual($post_id) {

	// Get users
	$winner_id = get_field('fetcher', $post_id);
	$winner_name = get_user_by('ID', $winner_id)->display_name;
	$participants = get_post_meta($post_id, 'participants', true); 
	$count = count($participants);
	
	// Send notification from LOTTEN to winner	
	send_admin_notification ('🥳 Grattis @'.$winner_name.' – du har vunnit lottningen! <br>
	⏳ Du får ett meddelande när du kan hämta i skåpet. <br>💡 PS. Vi hade problem med mailutskick idag, därför kommer detta besked lite sent.', $post_id, 11);
	
	// Send notification from LOTTEN to author
	$code_001 = do_shortcode('[code_snippet id=93]');
	send_admin_notification ('❤ '.$winner_name.' har vunnit lottningen! <br>
	⌛ Lämna gärna i skåpet inom 24 timmar. <br>
	🔓 Kod till skåpet: <b>'.$code_001.'</b> <br>
	🙏 Tack för att du loopar! @'.get_the_author().' <br>
	💡 PS. Vi hade problem med mailutskick idag, därför kommer detta besked lite sent.', $post_id, 11);
	
	// Send notification to losers
	foreach ($participants as $user_id) {
		if ($user_id == $winner_id) { continue; }
		$loser_id = get_userdata($user_id);
		$loser_name = $loser_id->display_name;
		send_admin_notification ('💔 Du vann tyvärr inte lottningen @'.$loser_name.'. <br>
		🍀 '.$count.' personer deltog. Bättre lycka nästa gång! <br>💡 PS. Vi hade problem med mailutskick idag, därför kommer detta besked lite sent.', $post_id, 11); } 
	
	// Leave comment by LOOPIS
	add_admin_comment ('🤖 Idag hade vi problem med mailutskick! <br>
	💡 Besked om lottning har nu skickats med lite fördröjning.', $post_id, 1);
	
	// Refresh page
	refresh_page();
}