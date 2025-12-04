<?php
/**
 * Statistics function: Output highscore users
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function display_top_users($users, $icon, $show_percentage = false) {
    if (empty($users)) {
        echo '<p>Inga användare hittades för denna lista.</p>';
        return;
    }

    $rank = 1;
    foreach ($users as $user) {
        $user_id = $user->user_id ?? $user->post_author ?? $user->fetcher_id; // Get user ID
        $user_info = get_userdata($user_id); // Fetch user data
        $username = $user_info ? $user_info->user_login : 'Unknown User'; // Handle missing user info
        $value = $show_percentage ? round($user->percentage) . '%' : ($user->combined_count ?? $user->post_count ?? $user->fetcher_count);
        $author_posts_link = get_author_posts_url($user_id);
        
        // Output user ID alongside username
        echo "<p>{$rank}. <span class='link'><a href='{$author_posts_link}'>👤 {$username}</a></span><span class='label' style='float:right;'>{$icon} {$value}</span></p>";
        $rank++;
    }
}