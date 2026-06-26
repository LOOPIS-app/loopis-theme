<?php
/**
 * Messages for user/visitor.
 * 
 * Improvements:
 * - Revise to work with WordPress multisite and membership on different sites.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Initialize message
$options = '';

if (is_user_logged_in()) { 

    // Member pending, earlier or outside
    if (current_user_can('member_pending') || current_user_can('member_earlier') || current_user_can('member_outside')) {
        $options = '<p>⏳ Du behöver komplettera ditt medlemskap.</p>
                    <p>Gå till <span class="big-link"><a href="'.esc_url(network_site_url('')).'">🗺 LOOPIS startsida</a></span> </p>';
    }

} else {
    // Not logged in
    $options = '<p><span class="big-link"><a href="'.esc_url(get_loopis_login_url()).'">👤 Logga in</a></span> om du är medlem.</p>
                <p><span class="big-link"><a href="'.esc_url(get_signup_url()).'">📋 Bli medlem</a></span> för att kunna logga in.</p>
                <p><span class="big-link"><a href="'.esc_url(network_site_url('/faq/hur-funkar-loopis/')).'">📌 Nyfiken?</a></span> Läs hur LOOPIS funkar.</p>';
}

// Output the message if it exists
if (!empty($options)) {
    echo '<div class="loopis-message information">' . $options . '</div>';
}