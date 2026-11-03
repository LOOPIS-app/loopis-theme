<?php
/**
 * Count posts where it's time to send an sms reminder.
 *
 * A post qualifies when 3 or more automatic reminders have been sent
 * and enough days have passed since the last one (same condition as the sms button).
 *
 * The result is cached for 1 hour in the transient 'loopis_reminder_sms_count'.
 * Cleared when an sms reminder is sent, see reminder_sms.php.
 *
 * @return int Number of posts waiting for an sms.
 */
function reminder_sms_count(): int {

    // Return cached count if available
    $cached = get_transient('loopis_reminder_sms_count');
    if ($cached !== false) {
        return (int) $cached;
    }

    // Get current timestamp
    $now_time = (new DateTime(current_time('mysql')))->getTimestamp();

    // Each list: category, reminder meta key, and the date meta key the sms timing is based on
    $reminder_lists = array(
        array('cat' => loopis_cat('locker'),        'reminder' => 'reminder_fetch', 'date' => 'locker_date'),
        array('cat' => loopis_cat('booked'),        'reminder' => 'reminder_leave', 'date' => 'book_date'),
        array('cat' => loopis_cat('booked_custom'), 'reminder' => 'reminder_fetch', 'date' => 'book_date'),
    );

    $sms_count = 0;

    foreach ($reminder_lists as $list) {

        // Posts with 3 or more reminders sent
        $the_query = new WP_Query(array(
            'post_type'      => 'post',
            'cat'            => $list['cat'],
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'meta_query'     => array(
                array(
                    'key'     => $list['reminder'],
                    'value'   => 3,
                    'type'    => 'NUMERIC',
                    'compare' => '>=',
                ),
            ),
        ));

        // Count posts where it's time for the sms
        foreach ($the_query->posts as $post_id) {
            $date_time = strtotime((string) get_post_meta($post_id, $list['date'], true));
            if (!$date_time) { continue; } // Skip missing or invalid date

            $reminder = absint(get_post_meta($post_id, $list['reminder'], true));
            if (($now_time - $date_time) > ($reminder + 1) * DAY_IN_SECONDS) {
                $sms_count++;
            }
        }
    }

    set_transient('loopis_reminder_sms_count', $sms_count, HOUR_IN_SECONDS);

    return $sms_count;
}
