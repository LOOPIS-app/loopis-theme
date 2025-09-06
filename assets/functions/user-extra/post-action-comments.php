<?php
/**
 * Post booking functions for LOOPIS user.
 *
 * Included in comments.php
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
		include LOOPIS_THEME_DIR . '/assets/output/access/no-coins.php'; 
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

/**
 * USER: BOOK LOCKER
 * User clicks book to fetch in locker
 */
function action_book_locker(int $post_id) {
    // Check economy
    $fetcher = get_current_user_id();
    $profile_economy = get_economy($fetcher);
    $coins = $profile_economy['coins'];
    if ($coins < 1) {
        include LOOPIS_THEME_DIR . '/assets/output/access/no-coins.php';
        echo '<script src="' . LOOPIS_THEME_DIR . '/assets/js/scroll-to-warning.js"></script>';
        return;
    }

    // Get variables
    $author = get_post_field('post_author');
    $author_name = get_userdata($author)->display_name;
    $fetcher_name = get_userdata($fetcher)->display_name;
    $code_001 = do_shortcode('[code_snippet id=93]');

    // Set post meta
    wp_set_object_terms($post_id, null, 'category');
    wp_set_object_terms($post_id, 'booked_locker', 'category');
    update_field('fetcher', $fetcher, $post_id);
    update_field('book_date', current_time('Y-m-d H:i:s'), $post_id);

    // Send notification from LOOPIS to author
    send_admin_notification('
    ❤ ' . $fetcher_name . ' har paxat! <br>
    ⌛ Lämna gärna i skåpet inom 24 timmar. <br>
    🔓 Kod till skåpet: <b>' . $code_001 . '</b> <br>
    🙏 Tack för att du loopar! @' . $author_name, $post_id, 1);

    // Leave comment by fetcher
    add_comment('<p class="book">
    ❤ Paxad! Du kan lämna i skåpet nu <span>🔔' . $author_name . '</span></p>', $post_id);

    // Refresh page
    refresh_page();
}

/** 
 * USER: BOOK CUSTOM
 * User clicks book to fetch at custom location
 */
function action_book_custom(int $post_id) {
    // Check economy
    $fetcher = get_current_user_id();
    $profile_economy = get_economy($fetcher);
    $coins = $profile_economy['coins'];
    if ($coins < 1) {
        include LOOPIS_THEME_DIR . '/assets/output/access/no-coins.php';
        echo '<script src="' . LOOPIS_THEME_DIR . '/assets/js/scroll-to-warning.js"></script>';
        return;
    }

    // Get variables
    $location = get_field('location');
    $author = get_post_field('post_author');
    $author_name = get_userdata($author)->display_name;
    $fetcher_name = get_userdata($fetcher)->display_name;
    $author_phone = get_the_author_meta('wpum_phone');

    // Set post meta
    wp_set_object_terms($post_id, null, 'category');
    wp_set_object_terms($post_id, 'booked_custom', 'category');
    update_field('fetcher', $fetcher, $post_id);
    update_field('book_date', current_time('Y-m-d H:i:s'), $post_id);

    // Send notification from LOOPIS to author
    send_admin_notification('
    ❤ ' . $fetcher_name . ' har paxat!<br>
    📱 Du kommer få ett sms för att komma överens om hämtning på ' . $location . '. <br>
    🙏 Tack för att du loopar! @' . $author_name, $post_id, 1);

    // Send notification from LOOPIS to fetcher
    send_admin_notification('
    📍 Du har paxat för hämtning på ' . $location . '. <br>
    📱 Skicka ett sms till ' . $author_name . ' på <a href="sms:' . $author_phone .'">' . $author_phone .'</a> <br>
    🙏 Tack för att du loopar! @' . $fetcher_name, $post_id, 1);

    // Leave comment by fetcher
    add_comment('<p class="book">
    ❤ Paxad för hämtning på <span>📍 ' . $location . '</span> <br>
    📱 Jag skickar ett sms inom kort <span>🔔' . $author_name . '</span></p>', $post_id);

    // Refresh page
    refresh_page();
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
		include LOOPIS_THEME_DIR . '/assets/output/access/no-coins.php'; 
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

/**
 * FETCHER: REGRET
 * Fetcher clicks regret on booked post
 */
function action_regret(int $post_id) {
	
	// Get variables
	$fetcher = get_post_meta($post_id, 'fetcher', true);
	$fetcher_name = get_userdata($fetcher)->display_name; 
	$code_001 = do_shortcode('[code_snippet id=93]');
	
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
	send_admin_notification('💔 Mottagaren har ångrat sig men... <br>❤ ' . $fetcher_name . ' stod i kö och har nu paxat! <br>⌛ Lämna gärna i skåpet inom 24 timmar @' . get_the_author() . '.<br>🔓 Kod till skåpet: <b>' . $code_001 . '</b>', $post_id, 1);
	
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
	send_admin_notification('💔 Mottagaren har ångrat sig och... <br>❤ Du stod först i kön @' . $fetcher_name . ' ! <br>⌛ Du  bör hämta i skåpet inom 24 timmar. <br>🔓 Kod till skåpet: <b>' . $code_001 . '</b>', $post_id, 1); 
	
	// Leave comment by LOOPIS
	add_admin_comment('💔 Mottagaren har ångrat sig men... <br>❤ ' . $fetcher_name . ' stod i kö och har nu paxat! ', $post_id, 1); 
	} 
		
	}
	
	// Refresh page
    refresh_page();
}