<?php
/**
 * Post form for creating a new support post.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<!-- Output post form -->
<div class="loopis-form-wrapper">
    <form class="loopis-form" action="" method="post">
    
        <div class="form-row">
        <label for="source-link">1⃣ Länk?</label>
        <input type="url" id="source-link" name="source-link" placeholder="Klistra in länk till sidan" required>
        <p class="description">Klistra gärna in en länk till sidan det gäller.</p>
    </div>

    <div class="form-row">
        <label for="post_title">2⃣ Rubrik</label>
        <input type="text" id="post_title" name="post_title" placeholder="Kort beskrivning" required>
        <p class="description">Ange vad din fråga handlar om.</p>
    </div>

    <div class="form-row">
        <label for="post_content">3⃣ Fråga</label>
        <textarea id="post_content" name="post_content" placeholder="Jag undrar..." required></textarea>
        <p class="description">Beskriv din fråga så tydligt som möjligt.</p>
    </div>

    <div class="form-row">
        <input type="checkbox" id="private_post" name="private_post" value="1">&nbsp; <b><span for="private_post">Privat</span></b>
        <p class="description">Markera om frågan endast ska synas för admin.</p>
    </div>

    <input type="submit" class="orange" name="submit_support_post" value="Skicka!">
        </div>
        </form>
</div>
<?php

// Handle form submission
if ( isset( $_POST['submit_support_post'] ) ) {

    // Set new post data based on form input
    $post_author_id = get_current_user_id();
    $post_title = sanitize_text_field( $_POST['post_title'] );
    $post_content = sanitize_textarea_field( $_POST['post_content'] );
    $is_private_post = ! empty( $_POST['private_post'] );
    $source_link = esc_url_raw( $_POST['source-link'] );
    $source_title = $post_title;

    // Try to fetch the title from the source link if available
    if ( ! empty( $source_link ) ) {
        $source_response = wp_remote_get(
            $source_link,
            array(
                'timeout'     => 5,
                'redirection' => 3,
            )
        );
        // Check if the response is valid and contains a title
        if ( ! is_wp_error( $source_response ) && 200 === (int) wp_remote_retrieve_response_code( $source_response ) ) {
            $source_body = wp_remote_retrieve_body( $source_response );

            // Attempt to extract the title from the HTML content of the source page
            if ( preg_match( '/<title[^>]*>(.*?)<\/title>/is', $source_body, $title_match ) ) {
                $source_title = trim(
                    wp_strip_all_tags(
                        html_entity_decode( $title_match[1], ENT_QUOTES | ENT_HTML5, 'UTF-8' )
                    )
                );
            }
        }
        // Fallback to using the source link as the title if no title was found
        if ( '' === $source_title ) {
            $source_title = $source_link;
        }
    }

    // Get the current post ID if a source link is provided
    $current_post_id = ! empty( $source_link ) ? (int) url_to_postid( $source_link ) : 0;

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
        $support_terms = array( (int) loopis_support_cat( 'active' ) );
        if ( $is_private_post ) {
            $support_terms[] = (int) loopis_support_cat( 'private' );
        }
        wp_set_post_terms( $post_id, $support_terms, 'support-category', false );

        // Set custom post fields 'title' and 'link' (shown as "Sent from" when viewing post)
        update_post_meta( $post_id, 'title', $source_title );
        update_post_meta( $post_id, 'link', $source_link );


        // Inherit thumbnail from the current post/page
        $current_post_thumbnail_id = $current_post_id ? get_post_thumbnail_id($current_post_id) : 0;
        if ( $current_post_thumbnail_id ) {
            set_post_thumbnail( $post_id, $current_post_thumbnail_id );
        }

        // Redirect to the new support post.
        $new_post_url = get_permalink( $post_id );
        if ( $new_post_url ) {
            if ( ! headers_sent() ) {
                wp_safe_redirect( $new_post_url );
                exit;
            }

            echo '<script>window.location.href=' . wp_json_encode( $new_post_url ) . ';</script>';
            echo '<noscript><meta http-equiv="refresh" content="0;url=' . esc_url( $new_post_url ) . '"></noscript>';
            exit;
        }

        // Notify managers
        include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/support-notification.php';
        send_support_notification($post_id);
    } else {
        echo '<div class="loopis-message warning">
        <p>❤️‍🩹 Något gick fel, försök gärna igen.</p>
        </div>';
    }
}
