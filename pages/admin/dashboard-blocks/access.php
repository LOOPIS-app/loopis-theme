<?php
/**
 * Show statistics for comments in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>
        <p class="small">Administrators:
            <?php
            $users = get_users(array('role' => 'administrator'));
            foreach ($users as $user) {
                $user_first_name = get_user_meta($user->ID, 'first_name', true);
                $author_link = get_author_posts_url($user->ID);
                echo '<a href="' . esc_url($author_link) . '">👽' . esc_html($user_first_name) . '</a> &nbsp;';
            }
            ?>
        </p>

        <p class="small">Develoopers:
            <?php
            $users = get_users(array('role' => 'develooper'));
            foreach ($users as $user) {
                $user_first_name = get_user_meta($user->ID, 'first_name', true);
                $author_link = get_author_posts_url($user->ID);
                echo '<a href="' . esc_url($author_link) . '">👽' . esc_html($user_first_name) . '</a> &nbsp;';
            }
            ?>
        </p>
        
        <p class="small">Managers:
            <?php
            $users = get_users(array('role' => 'manager'));
            foreach ($users as $user) {
                $user_first_name = get_user_meta($user->ID, 'first_name', true);
                $user_last_name = get_user_meta($user->ID, 'last_name', true);
                $author_link = get_author_posts_url($user->ID);
                echo '<a href="' . esc_url($author_link) . '">👤' . esc_html($user_first_name . ' ' . $user_last_name) . '</a> &nbsp;';
            }
            ?>
        </p>
        
        <p class="small">Styrelsen:
            <?php
            $users = get_users(array('role' => 'board_member'));
            foreach ($users as $user) {
                $user_first_name = get_user_meta($user->ID, 'first_name', true);
                $user_last_name = get_user_meta($user->ID, 'last_name', true);
                $author_link = get_author_posts_url($user->ID);
                echo '<a href="' . esc_url($author_link) . '">👤' . esc_html($user_first_name . ' ' . $user_last_name) . '</a> &nbsp;';
            }
            ?>
        </p>
