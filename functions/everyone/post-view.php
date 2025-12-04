<?php
/**
 * Gift-post details for LOOPIS visitor/user.
 *
 * Included for everyone in functions.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** 
* Raffle time output
*/	
function raffle_time() {
    $post_date = get_the_time('Y-m-d');
    $current_date = current_time('Y-m-d');
    $next_day = date('Y-m-d', strtotime($post_date . ' +1 day'));
    
    if ($post_date === $current_date) { return ' imorrn kl. 12'; }
    if ($next_day === $current_date) { return ' idag kl. 12'; }
    
    return null;
}

/** 
* Raffle time output special
* (used in the activity raffle list)
*/	
function raffle_time_post_id(int $post_id) {
    $post_date = get_the_time('Y-m-d', $post_id);
    $current_date = current_time('Y-m-d');
    $next_day = date('Y-m-d', strtotime($post_date . ' +1 day'));

    if ($post_date === $current_date) { return ' imorrn kl. 12'; }
    if ($next_day === $current_date) { return ' idag kl. 12'; }

    return null;
}

/**
 * Raffle participants output
 * (not yet used)
 */
 function count_participants($post_id) {
    $participants = get_post_meta($post_id, 'participants', true); 
    if (is_array($participants) && !empty($participants)) { 
        $count = count($participants); 
    } else { 
        $count = 0; 
    }
    
    if ($count > 0) { return "🧡 $count";
        } else { return "🩶 0"; }
 }