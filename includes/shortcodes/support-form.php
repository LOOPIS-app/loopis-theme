<?php
/**
 * Form for support-posts
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function loopis_support_post() {
    if ( is_user_logged_in() ) {
        ?>
        <!-- post form -->
        <form action="" method="post">
            <p id="post_content" >
                <textarea id="post_content" name="post_content" required></textarea>
            </p>
            <p>
                <input type="submit" name="submit_support_post" value="Skicka!">
            </p>
        </form>
        <?php
        if ( isset( $_POST['submit_support_post'] ) ) {
            // inserting published support post with 
            // title = [page title] and 
            // name = [support post count] 

            $post_title = get_the_title();
            $post_content = sanitize_textarea_field( $_POST['post_content'] );
            global $wpdb;
            $post_name = $wpdb->get_var("SELECT COUNT(*) 
                FROM {$wpdb->posts}
                WHERE post_type = 'support'
                AND post_status = 'publish'
            ");

            $new_post = array(
                'post_title'   => $post_title,
                'post_content' => $post_content,
                'post_status'  => 'publish', 
                'post_name'    => $post_name,
                'post_author'  => get_current_user_id(),
                'post_type'    => 'support',
            );

            $new_post = wp_insert_post( $new_post );
            // Set meta and taxonomy
            if(!is_wp_error($new_post)){
                update_post_meta($new_post, 'status', loopis_support_cat("active") );
                wp_set_post_terms($new_post, loopis_support_cat("active"), 'support-category', false );
                $current_title = get_the_title();
                update_post_meta($new_post, 'title', $current_title);
                $current_url = get_permalink();
                update_post_meta($new_post, 'link', $current_url );
    
                echo '<p>Tack!💚 Vi löser detta så snart vi kan!</p>';
            }else{
                echo '<p>Hoppsan det gick inte riktigt, försök gärna igen!</p>';
            }

        }
    } else {
            echo '<p>Du måste vara inloggad för att lägga in ett ärende.</p>';
    }
}
add_shortcode( 'loopis_support_post', 'loopis_support_post' );