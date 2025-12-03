<?php
/**
 * Function for archive cronjob.
 * 
 * Runs every sunday at 01.00.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** CRON: ARCHIVE */
function cron_job_archive() {
    $start_time = new DateTime(current_time('mysql'));
    $now_time = new DateTime(current_time('mysql'));
    $four_weeks_ago = new DateTime(current_time('mysql'));
    $four_weeks_ago->modify('-28 days');

    $old_category_ids = array(37, 159);
    $new_category_id = 167;

    $args = array(
        'category__in' => $old_category_ids,
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    $old_posts = new WP_Query($args);
    $checked_count = $old_posts->found_posts;

    $user_ids = array();
    $archived_count = 0;

    if ($old_posts->have_posts()) {
        foreach ($old_posts->posts as $post_id) {
            $extend_date = get_field('extend_date', $post_id);
            $do_archive = null;

            if ($extend_date) {
                $extend_date_time = new DateTime($extend_date);
                $extend_date_time->modify('+28 days');

                if ($extend_date_time <= $now_time) {
                    $do_archive = true;
                }
            } else {
                $publish_date = new DateTime(get_the_date('Y-m-d H:i:s', $post_id));

                if ($publish_date < $four_weeks_ago) {
                    $do_archive = true;
                }
            }

            if ($do_archive) {
                wp_set_post_categories($post_id, array($new_category_id));
                update_field('archive_date', current_time('Y-m-d H:i:s'), $post_id);

                $archived_count++;

                $user_id = get_post_field('post_author', $post_id);
                if ($user_id && !in_array($user_id, $user_ids)) {
                    $user_ids[] = $user_id;
                }
            }
        }
    }

    $unique_users = count($user_ids);
    $end_time = new DateTime(current_time('mysql'));
    $execution_time = $start_time->diff($end_time)->s;

    $to = "lotten@loopis.app";
    $subject = "⭕ Arkivering";
    $message = "
    <b>⭕ {$archived_count} annonser har arkiverats</b><br>
    <hr>
    👤 {$unique_users} användare påverkades<br>
	<br>
	🎁 {$checked_count} annonser kontrollerades<br>
	<br>
    🐎 Processen tog {$execution_time} sekunder<br>
    ⏰ " . $start_time->format('H:i:s') . " → " . $end_time->format('H:i:s') . "<br>
    ";
    $headers = array(
        'From: info@loopis.app',
        'Content-Type: text/html; charset=UTF-8',
        'X-Emoji-Service: twemoji',
    );

    wp_mail($to, $subject, $message, $headers);
}