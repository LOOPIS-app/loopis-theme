<?php
/**
 * Sending of sms reminders for admin.
 *
 * Included for admin in functions.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** REMINDER: DELIVER */
// Post booked for locker fetching
function reminder_leave_sms(int $reminder_leave, int $post_id) {
    // Check if the reminder leave count is 3
    if ($reminder_leave == 3) {
        // Prepare SMS
        $author = get_post_field('post_author');
		$author_name = get_userdata($author)->display_name;
        $book_time = strtotime(get_field('book_date'));
        $current_time = (new DateTime(current_time('mysql')))->getTimestamp();
        $time_difference = $current_time - $book_time;
        $days = floor($time_difference / (24 * 3600));
        $phone = get_the_author_meta('wpum_phone', $author);
        $name = get_userdata($author)->first_name;
        $link = get_the_permalink($post_id);
        $message = "Hej $name!\nHar du sett våra påminnelser om att lämna i skåpet? Det har gått $days dagar nu.\n💚 Johan på LOOPIS\n\n$link";

        // Encode the message for URL and replace '+' with '%20'
        $message_encoded = str_replace('+', '%20', urlencode($message));

        // Add admin comment
        add_admin_comment('<p class="reminder">💡 Påminnelse skickad via sms till <span>📱' . $author_name . '</span></p>', $post_id, 1);

        // Increase number
        update_field('reminder_leave', $reminder_leave + 1);

        // Return the SMS URL
        return "sms:$phone?body=$message_encoded";
    } else { 
        $author = get_post_field('post_author');
		$phone = get_the_author_meta('wpum_phone', $author);
		$name = get_userdata($author)->first_name;
		$message = "Hej igen $name!\n";
		$message_encoded = str_replace('+', '%20', urlencode($message));
		return "sms:$phone?body=$message_encoded";
	}
	return null;
}

/** REMINDER: FETCH */
// Post delivered for locker fetching
function reminder_fetch_sms(int $reminder_fetch, int $post_id) {
    // Check if the reminder fetch count is 3
    if ($reminder_fetch == 3) {
        // Prepare SMS
        $fetcher = get_field('fetcher');
		$fetcher_name = get_userdata($fetcher)->display_name;
        $locker_time = strtotime(get_field('locker_date'));
		$current_time = (new DateTime(current_time('mysql')))->getTimestamp();
        $time_difference = $current_time - $locker_time;
        $days = floor($time_difference / (24 * 3600));
        $phone = get_the_author_meta('wpum_phone', $fetcher);
        $name = get_userdata($fetcher)->first_name;
        $link = get_the_permalink($post_id);
        $message = "Hej $name!\nHar du sett våra påminnelser om att hämta i skåpet? Det har gått $days dagar nu.\n💚 Johan på LOOPIS\n\n$link";
        
        // Encode the message for URL and replace '+' with '%20'
        $message_encoded = str_replace('+', '%20', urlencode($message));

        // Add admin comment
        add_admin_comment('<p class="reminder">💡 Påminnelse skickad via sms till <span>📱' . $fetcher_name . '</span></p>', $post_id, 1);

        // Increase number
        update_field('reminder_fetch', $reminder_fetch + 1);

        // Return the SMS URL
        return "sms:$phone?body=$message_encoded";
    } else { 
		$fetcher = get_field('fetcher');
		$name = get_userdata($fetcher)->first_name;
		$phone = get_the_author_meta('wpum_phone', $fetcher);
		$message = "Hej igen $name!\n";
		$message_encoded = str_replace('+', '%20', urlencode($message));
		return "sms:$phone?body=$message_encoded";
	}
	return null;
}

/** REMINDER: CUSTOM */
// Post booked for custom location fetching
function reminder_custom_sms(int $reminder_fetch, int $post_id) {
    // Check if the reminder fetch count is 3
    if ($reminder_fetch == 3) {
        // Prepare SMS
        $fetcher = get_field('fetcher');
		$fetcher_name = get_userdata($fetcher)->display_name;
        $book_time = strtotime(get_field('book_date'));
		$current_time = (new DateTime(current_time('mysql')))->getTimestamp();
        $time_difference = $current_time - $book_time;
        $days = floor($time_difference / (24 * 3600));
        $phone = get_the_author_meta('wpum_phone', $fetcher);
        $name = get_userdata($fetcher)->first_name;
		$location = get_field('location');
        $link = get_the_permalink($post_id);
        $message = "Hej $name!\nHur går det med hämtningen på $location? Det har gått $days dagar nu.\n💚 Johan på LOOPIS\n\n$link";
        
        // Encode the message for URL and replace '+' with '%20'
        $message_encoded = str_replace('+', '%20', urlencode($message));

        // Add admin comment
        add_admin_comment('<p class="reminder">💡 Påminnelse skickad via sms till <span>📱' . $fetcher_name . '</span></p>', $post_id, 1);

        // Increase number
        update_field('reminder_fetch', $reminder_fetch + 1, $post_id);

        // Return the SMS URL
        return "sms:$phone?body=$message_encoded";
    } else { 
		$fetcher = get_field('fetcher');
		$phone = get_the_author_meta('wpum_phone', $fetcher);
		$name = get_userdata($fetcher)->first_name;
		$message = "Hej igen $name!\n";
		$message_encoded = str_replace('+', '%20', urlencode($message));
		return "sms:$phone?body=$message_encoded";
	}
	return null;
}