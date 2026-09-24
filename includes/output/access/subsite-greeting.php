<?php
/**
 * Front page greeting depending on role.
 *
 * Included in front-page.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ( is_user_logged_in() ) {
    
// Member pending or outside
if (current_user_can('member_pending') || current_user_can('member_outside')) {  
    echo "<h5>💚 Välkommen!</h5><hr>"; 
    }

// Member earlier
elseif (current_user_can('member_earlier')) { 
    echo "<h5>💚 Nytt år!</h5><hr>"; 
    }

// Not logged in
} else { 
    echo "<h5>💚 Välkommen!</h5><hr>"; 
    }
