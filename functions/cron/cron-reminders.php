<?php
/**
 * Main function for reminders cronjob.
 *
 * Runs every day at 00 05 10 15 and 20.00.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** CRON: REMINDERS */
// Cronjob initiatied at 00, 05, 10, 15 and 20 every day
function cron_job_reminders() {
    // Set start time
    $start_time = new DateTime(current_time('mysql'));

    // Get current timestamp
    $now_time = $start_time->getTimestamp();

    // args
    $args = array( 
        'post_type' => 'post',
        'posts_per_page' => -1,
        'category__in' => array(57, 104, 147),
    );

    // query
    $the_query = new WP_Query($args);
    $total_count = $the_query->found_posts;

    // Set counters
    $reminders_leave = 0;
    $reminders_fetch = 0;
    $reminders_custom = 0;

    // Start loop
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $post_id = get_the_ID();

            // BOOKED
            if (in_category('booked_locker')) {
                $book_time = strtotime(get_field('book_date'));
                $reminder_leave = (int) get_field('reminder_leave');
                if ($reminder_leave === null) {
                    update_field('reminder_leave', 0, $post_id);
                    $reminder_leave = 0;
                }
                // Send reminder?
                if (($now_time - $book_time) > ($reminder_leave + 1) * (24 * 3600)) {
                    $send = reminder_leave($reminder_leave, $post_id);
                    $reminders_leave += $send;
                }
            }

            // LOCKER
            if (in_category('locker')) {
                $locker_time = strtotime(get_field('locker_date'));
                $reminder_fetch = (int) get_field('reminder_fetch');
                if ($reminder_fetch === null) {
                    update_field('reminder_fetch', 0, $post_id);
                    $reminder_fetch = 0;
                }
                // Send reminder?
                if (($now_time - $locker_time) > ($reminder_fetch + 1) * (24 * 3600)) {
                    $send = reminder_fetch($reminder_fetch, $post_id);
                    $reminders_fetch += $send;
                }
            }
            
            // CUSTOM LOC.
            if (in_category('booked_custom')) {
                $book_time = strtotime(get_field('book_date'));
                $reminder_fetch = (int) get_field('reminder_fetch');
                if ($reminder_fetch === null) {
                    update_field('reminder_fetch', 0, $post_id);
                    $reminder_fetch = 0;
                }
                // Send reminder?
                if (($now_time - $book_time) > ($reminder_fetch + 1) * (24 * 3600)) {
                    $send = reminder_custom($reminder_fetch, $post_id);
                    $reminders_custom += $send;
                }
            }
        }
        wp_reset_postdata();
    }

    // Calculate total
    $reminders = $reminders_leave + $reminders_fetch + $reminders_custom;

    if ($reminders > 0) {
        // Calculate the execution time
        $end_time = new DateTime(current_time('mysql'));
        $interval = $start_time->diff($end_time);
        $execution_time = $interval->format('%s');

        // Prepare email
        $to = "lotten@loopis.app";
        $subject = "⏰ Påminnelser " . $start_time->format('d/m') . " kl. " . $start_time->format('H');
        $message = "
        <b>✉ {$reminders} påminnelser har skickats</b><br>
        <hr>
        ▶ {$reminders_leave} påminnelser att lämna i skåpet<br>
        ⏺ {$reminders_fetch} påminnelser att hämta i skåpet<br>
        📍 {$reminders_custom} påminnelser att hämta på annan adress<br>
        <br>
        🎁 {$total_count} annonser kontrollerades<br>
        <br>
        🐎 Processen tog {$execution_time} sekunder<br>
        ⏰ " . $start_time->format('H:i:s') . " → " . $end_time->format('H:i:s') . "<br>
        ";
        $headers = array(
            'From: info@loopis.app',
            'Content-Type: text/html; charset=UTF-8',
            'X-Emoji-Service: twemoji'
        );

        // Send email
        wp_mail($to, $subject, $message, $headers);
    }
}