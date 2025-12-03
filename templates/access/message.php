<?php
/**
 * Messages for user/visitor.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Initialize message
$message = '';

if (is_user_logged_in()) { 

    // Member pending
    if (current_user_can('member_pending')) {
        $message = '<p>🙏 Tack för din ansökan om medlemskap!</p>
                    <p class="small">När vi har kontrollerat din medlemsavgift får du ett mail och kan börja loopa.</p>';
    }

    // Member earlier
    elseif (current_user_can('member_earlier')) {
        $message = '<p>Du behöver förnya ditt medlemskap för att fortsätta använda LOOPIS. ✨</p>
                    <p><span class="big-link"><a href="/fornya-medlemskap">🌈 Förnya medlemskap</a></span></p>';
    }

    // Member outside
    elseif (current_user_can('member_outside')) {
        $message = '<p>🙏 Tack för att du stöttar LOOPIS med ditt medlemskap!</p>
                    <p>Vi hoppas att du i framtiden kan använda föreningens tjänster där du bor.</p>
                    <p><span class="link"><a href="/faq/varfor-bagis">📌 Varför måste jag bo i Bagarmossen?</a></span></p>';
    }

    // Member outside earlier
    elseif (current_user_can('member_outside_earlier')) {
        $message = '<p>Förnya ditt medlemskap för att fortsätta stötta oss!</p>
                    <p>Eller börja loopa om vi finns i ditt område nu.</p>
                    <p><span class="link"><a href="/fornya-medlemskap">🌈 Förnya medlemskap</a></span></p>
                    <p><span class="link"><a href="/faq/varfor-bagis">📌 Varför måste jag bo i Bagarmossen?</a></span></p>';
    }

} else {
    // Not logged in
    $message = '<p><span class="link"><a href="/log-in">👤 Logga in</a></span> om du är medlem.</p>
                <p><span class="link"><a href="/register">📋 Bli medlem</a></span> för att kunna logga in.</p>
                <p><span class="link"><a href="/faq/hur-funkar-loopis/">📌 Nyfiken?</a></span> Läs hur LOOPIS funkar.</p>';
}

// Output the message if it exists
if (!empty($message)) {
    echo '<div class="wpum-message information">' . $message . '</div>';
}