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
$message = '';

if (is_user_logged_in()) { 

    // Member pending
    if (current_user_can('member_pending')) {
        $message = '<p>⏳ Du har inte betalat din medlemsavgift ännu?</p>
                    <p><span class="big-link">💳 <a href="'.esc_url(network_site_url('/shop/?option=membership-stripe')).'">Betala medlemskap</a></span> för att börja loopa.</p>';
    }

    // Member earlier
    elseif (current_user_can('member_earlier')) {
        $message = '<p>Du behöver förnya ditt medlemskap för att fortsätta använda LOOPIS. ✨</p>';
    }

    // Member outside
    elseif (current_user_can('member_outside')) {
        $message = '<p>🙏 Tack för att du stöttar LOOPIS med ditt medlemskap!</p>
                    <p>Vi hoppas att du i framtiden kan använda föreningens tjänster där du bor.</p>
                    <p><span class="link"><a href="'.esc_url(network_site_url('/faq/varfor-bagis/')).'">📌 Varför måste jag bo i Bagarmossen?</a></span></p>';
    }

} else {
    // Not logged in
    $message = '<p><span class="big-link"><a href="'.esc_url(get_login_url()).'">👤 Logga in</a></span> om du är medlem.</p>
                <p><span class="big-link"><a href="'.esc_url(get_signup_url()).'">📋 Bli medlem</a></span> för att kunna logga in.</p>
                <p><span class="big-link"><a href="'.esc_url(network_site_url('/faq/hur-funkar-loopis/')).'">📌 Nyfiken?</a></span> Läs hur LOOPIS funkar.</p>';
}

// Output the message if it exists
if (!empty($message)) {
    echo '<div class="loopis-message information">' . $message . '</div>';
}