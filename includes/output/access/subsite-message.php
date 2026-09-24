<?php
/**
 * Subsite message depending on role.
 * 
 * Passed from page-start.php:
 * $user_id
 * $user_firstname
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (is_user_logged_in()) { 

    // Member pending or earlier
    if (current_user_can('member_pending') || current_user_can('member_earlier')) {
        echo '<div class="loopis-message information">';
        echo '<p>⏳ Du behöver komplettera ditt medlemskap.</p>';
        echo '<p>Gå till <span class="big-link"><a href="'.esc_url(network_site_url('/start/')).'">🗺 LOOPIS startsida</a></span> </p>';
        echo '</div>';
    }

    // Not a user on the current subsite.
    elseif (!is_user_member_of_blog($user_id, get_current_blog_id())) {
        echo '<div class="loopis-message information">';
        echo '<p>❤️‍🩹 Du är inte medlem i detta område.</p>';
        echo '<p><span class="big-link"><a href="'.esc_url(network_site_url('/start/')).'">🗺 Gå till LOOPIS startsida</a></span></p>';
        echo '</div>';
    }

} else {
    // Not logged in
    echo '<div class="loopis-message information">';
    echo '<p><span class="big-link"><a href="'.esc_url(get_loopis_login_url()).'">👤 Logga in</a></span> om du är medlem.</p>';
    echo '<p><span class="big-link"><a href="'.esc_url(get_signup_url()).'">📋 Bli medlem</a></span> för att kunna logga in.</p>';
    echo '<p><span class="big-link"><a href="'.esc_url(network_site_url('/faq/hur-funkar-loopis/')).'">📌 Nyfiken?</a></span> Läs hur LOOPIS funkar.</p>';
    echo '</div>';
}
