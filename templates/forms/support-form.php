<?php
/**
 * Post form for creating a new support post.
 * 
 * Included for users on all pages/posts in footer.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<!-- Output post form -->
<div>
<form class="loopis-form" id="support-form" action="" method="post">
    <h5>🛟 Behöver du hjälp?</h5>
    <p class="small">💡 Skriv din fråga på sidan det gäller.</p>
    <textarea id="post_content" name="post_content" placeholder="Jag undrar..." required></textarea>
    <input type="submit" class="green small" name="submit_support_post" value="Skicka!">
</form>
</div>
<?php

// Handle form submission
if ( isset( $_POST['submit_support_post'] ) ) {

    // Set new post data based on form input
    $post_author_id = get_current_user_id();
    $post_content = sanitize_textarea_field( $_POST['post_content'] );

    // Set new post data based on current post (where support form was submitted)
    $current_post_id = get_queried_object_id();
    $current_post_title = get_the_title();
    $current_post_url = get_permalink();
    $post_title = $current_post_title;

    // Set numerical post slug to the next available number (avoid slug suffixes)
    global $wpdb;
    $post_slug = (int) $wpdb->get_var("SELECT COALESCE(MAX(CAST(post_name AS UNSIGNED)), 0)
        FROM {$wpdb->posts}
        WHERE post_type = 'support'
        AND post_name REGEXP '^[0-9]+$'
    ") + 1;

    while (get_page_by_path((string) $post_slug, OBJECT, 'support')) {
        $post_slug++;
    }

    // Create new support post
    $post_id = array(
        'post_title'   => $post_title,
        'post_content' => $post_content,
        'post_status'  => 'publish',
        'post_name'    => $post_slug,
        'post_author'  => $post_author_id,
        'post_type'    => 'support',
    );
    $post_id = wp_insert_post( $post_id, true );

    // Set additional post data and notify managers
    if ( ! is_wp_error( $post_id ) ) {
        // Set custom post category (status) to active
        wp_set_post_terms( $post_id, loopis_support_cat( 'active' ), 'support-category', false );

        // Inherit thumbnail from the current post/page
        $current_post_thumbnail_id = $current_post_id ? get_post_thumbnail_id($current_post_id) : 0;
        if ( $current_post_thumbnail_id ) {
            set_post_thumbnail( $post_id, $current_post_thumbnail_id );
        }

        // Set custom post fields 'title' and 'link' (shown as "Sent from" when viewing post)
        update_post_meta( $post_id, 'title', $current_post_title );
        update_post_meta( $post_id, 'link', $current_post_url );

        // Show success message
        echo '<p>✅ Din fråga är skickad!</p>';

        // Notify managers
        include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/support-notification.php';
        send_support_notification($post_id);
    } else {
        echo '<p>❤️‍🩹 Något gick fel, försök gärna igen.</p>';
    }
}
