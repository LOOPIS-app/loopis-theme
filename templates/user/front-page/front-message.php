<?php
/**
 * Front page message for member/visitor.
 *
 * Included in front-page.php
 * 
 * Improvements:
 * - Revise to work with WordPress multisite and membership on different sites.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ( is_user_logged_in() ) {
    
// Member pending
if (current_user_can('member_pending')) { echo "<h5>💚 Ett steg kvar...</h5><hr>"; }

// Member earlier
elseif (current_user_can('member_earlier')) { echo "<h5>💚 Nytt år!</h5><hr>"; }

// Member outside
elseif (current_user_can('member_outside')) { echo "<h5>💚 Tack!</h5><hr>"; }

// Not logged in
} else { echo "<h5>💚 Välkommen!</h5><hr>"; }

// All of them
include_once LOOPIS_THEME_DIR . '/templates/access/message.php';