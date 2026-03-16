<?php
/**
 * Extra functions for reminders cronjob.
 *
 * Included for cronjob + admin in manual reminder sending.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** REMINDER: DELIVER */
// Post booked for locker fetching
function reminder_leave(int $reminder_leave, int $post_id) {
    // Get variables
    $number = $reminder_leave + 1;
	$locker_code = get_locker_code(LOCKER_ID);

    // Get user data
    $author_id = get_post_field('post_author', $post_id);
    $author_name = get_userdata($author_id)->display_name;
    $fetcher_id = get_post_meta($post_id, 'fetcher', true);
    $fetcher_name = get_userdata($fetcher_id)->display_name;

    // Send notifications & leave comments
    if ($number == 1) {
        send_admin_notification_email('
		💡 Påminnelse #1 att lämna i skåpet @' . $author_name . ' <br>
		✅ Tryck &quot;Lämnat&quot; på LOOPIS.app när du har lämnat. <br>
		🔓 Kod till skåpet: <b>' . $locker_code . '</b>
        ', $post_id, 1, $author_id);
		
        add_admin_comment('<p class="reminder">
		💡 Påminnelse #1 att lämna i skåpet <span>🔔' . $author_name . '</span>
		</p>', $post_id, 1);

    } elseif ($number == 2) {
        send_admin_notification_email('
		💡 Påminnelse #2 att lämna i skåpet @' . $author_name . ' <br>
		😍 ' . $fetcher_name . ' väntar på att få hämta... <br>
		🔓 Kod till skåpet: <b>' . $locker_code . '</b>
        ', $post_id, 1, $author_id);
		
        add_admin_comment('<p class="reminder">
		💡 Påminnelse #2 att lämna i skåpet <span>🔔' . $author_name . '</span>
		</p>', $post_id, 1);

    } elseif ($number == 3) {
        send_admin_notification_email('
		⚠ Påminnelse #3 att lämna i skåpet @' . $author_name . ' <br>
		🗨 Skriv gärna i en kommentar till ' . $fetcher_name . ' om/när du kommer att lämna. <br>
		🔓 Kod till skåpet: <b>' . $locker_code . '</b>
        ', $post_id, 1, $author_id);
		
        send_admin_notification_email('
		💡 Vi har nu skickat tre påminnelser till ' . $author_name . ' att lämna i skåpet. <br>
		💚 Beklagar fördröjningen! @' . $fetcher_name . '
        ', $post_id, 1, $fetcher_id);
		
        add_admin_comment(
            '<p class="reminder">' .
            '⚠ Påminnelse #3 att lämna i skåpet <span>🔔' . $author_name . '</span><br>' .
            '🗨 Skriv gärna i en kommentar till <span>🔔' . $fetcher_name . '</span> om/när du kommer att lämna.' .
            '</p>',
            $post_id,
            1
        );

    } else {
        return 0;
    }

    // Set new number
    update_field('reminder_leave', $reminder_leave + 1, $post_id);
    return 1;
}

/** REMINDER: FETCH */
// Post delivered for locker fetching
function reminder_fetch(int $reminder_fetch, int $post_id) {
    // Get variables
    $number = $reminder_fetch + 1;
    $locker_code = get_locker_code(LOCKER_ID);

    // Get user data
    $fetcher_id = get_post_meta($post_id, 'fetcher', true);
    $fetcher_name = get_userdata($fetcher_id)->display_name;

    // Send notifications & leave comments
    if ($number == 1) {
        send_admin_notification_email('
        💡 Påminnelse #1 att hämta i skåpet @' . $fetcher_name . ' <br>
        ☑ Tryck &quot;Hämtat&quot; på LOOPIS.app när du har hämtat. <br>
        🔓 Kod till skåpet: <b>' . $locker_code . '</b>
        ', $post_id, 1, $fetcher_id);

        add_admin_comment('<p class="reminder">
        💡 Påminnelse #1 att hämta i skåpet <span>🔔' . $fetcher_name . '</span>
		</p>', $post_id, 1);

    } elseif ($number == 2) {
        send_admin_notification_email('
        💡 Påminnelse #2 att hämta i skåpet @' . $fetcher_name . ' <br>
        ♻ För att skåpet inte ska bli fullt önskar vi att du hämtar. <br>
        🔓 Kod till skåpet: <b>' . $locker_code . '</b>
        ', $post_id, 1, $fetcher_id);

        add_admin_comment('<p class="reminder">
        💡 Påminnelse #2 att hämta i skåpet <span>🔔' . $fetcher_name . '</span>
		</p>', $post_id, 1);

    } elseif ($number == 3) {
        send_admin_notification_email('
        ⚠ Påminnelse #3 att hämta i skåpet @' . $fetcher_name . ' <br>
        🗨 Skriv gärna i en kommentar till LOOPIS när du kommer att hämta. <br>
        🔓 Kod till skåpet: <b>' . $locker_code . '</b>
        ', $post_id, 1, $fetcher_id);

        add_admin_comment(
        '<p class="reminder">' .
        '⚠ Påminnelse #3 att hämta i skåpet <span>🔔' . $fetcher_name . '</span><br>' .
        '🗨 Skriv gärna i en kommentar till <span>🔔LOOPIS</span> när du kommer att hämta.' .
        '</p>',
        $post_id,
        1
        );

    } else {
        return 0;
    }

    // Set new number
    update_field('reminder_fetch', $reminder_fetch + 1, $post_id);
    return 1;
}

/** REMINDER: CUSTOM */
// Post booked for custom location fetching
function reminder_custom(int $reminder_fetch, int $post_id) {
    // Get variables
    $number = $reminder_fetch + 1;
    $location = get_post_meta($post_id, 'location', true);

    // Get user data
    $author_id = get_post_field('post_author', $post_id);
    $fetcher_id = get_post_meta($post_id, 'fetcher', true);
    $fetcher_name = get_userdata($fetcher_id)->display_name;
    $author_name = get_userdata($author_id)->display_name;
    $author_phone = get_the_author_meta('wpum_phone', $author_id);

    // Send notifications & leave comments
    if ($number == 1) {
        send_admin_notification_email('
        💡 Påminnelse att ta kontakt @' . $fetcher_name . ' <br>
        📱 Har du skickat ett sms till ' . $author_name . ' på <a href="sms:' . $author_phone .'">' . $author_phone .'</a>? <br>
        📍 Adress för hämtning: ' . $location . ' <br>
        ', $post_id, 1, $fetcher_id);
        
        add_admin_comment('<p class="reminder">
        💡 Påminnelse att ta kontakt <span>🔔' . $fetcher_name . '</span>
        </p>', $post_id, 1);

    } elseif ($number == 2) {
        send_admin_notification_email('
        💡 Påminnelse att hämta @' . $fetcher_name . ' <br>
        📱 Hoppas att du har kontakt med ' . $author_name . ' nu! <br>
        ☑ Tryck &quot;Hämtat&quot; på LOOPIS.app när du har hämtat. <br>
        ', $post_id, 1, $fetcher_id);
        
        add_admin_comment('<p class="reminder">
        💡 Påminnelse att hämta <span>🔔' . $fetcher_name . '</span>
        </p>', $post_id, 1);

    } elseif ($number == 3) {
        $fetcher_phone = get_user_meta($fetcher_id, 'wpum_phone', true);
        send_admin_notification_email('
        💡 Nu har det gått tre dagar sedan ' . $fetcher_name . ' paxade. <br>
        📱 Om ni inte har kontakt ännu, skicka ett sms till <a href="sms:' . $fetcher_phone .'">' . $fetcher_phone .'</a> <br>
        💚 Hoppas hämtningen går bra! @' . $author_name . '.
        ', $post_id, 1, $author_id);

    } else {
        return 0;
    }

    // Set new number
    update_field('reminder_fetch', $reminder_fetch + 1, $post_id);
    return 1;
}