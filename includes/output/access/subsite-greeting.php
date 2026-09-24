<?php
/**
 * Subsite greeting depending on role.
 *
 * Passed from front-page.php:
 * $user_id
 * $user_firstname
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ( is_user_logged_in() ) {
    
    // Member pending
    if (current_user_can('member_pending')) {  
        echo "<h5>Hej $user_firstname! 👋</h5><hr>"; 
        }

    // Member earlier
    elseif (current_user_can('member_earlier')) { 
        echo "<h5>Nytt år! 💚</h5><hr>"; 
        }

    // Not a user on the current subsite.
    elseif (!is_user_member_of_blog($user_id, get_current_blog_id())) {
        echo "<h5>Hej besökare! 👋</h5><hr>"; 
        }

// Not logged in
} else { 
    echo "<h5>💚 Välkommen!</h5><hr>"; 
    }
