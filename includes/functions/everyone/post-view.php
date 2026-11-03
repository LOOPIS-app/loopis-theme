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
* Uses the current post in the loop, or the given $post_id
*/	
function raffle_time(?int $post_id = null): string {
    $post_date = get_the_time('Y-m-d', $post_id);
    $current_date = current_time('Y-m-d');
    $next_day = date('Y-m-d', strtotime($post_date . ' +1 day'));

    $raffle_time = '';
    if ($post_date === $current_date) { $raffle_time = ' imorrn kl. 12'; }
    elseif ($next_day === $current_date) { $raffle_time = ' idag kl. 12'; }
    else { error_log("raffle_time: post $post_id is outside raffle window"); }

    return $raffle_time;
}

/**
 * Participants count output
 * Uses the current post in the loop, or the given $post_id
 */
 function raffle_count(?int $post_id = null): string {
    $post_id = $post_id ?? get_the_ID();
    $participants = get_post_meta($post_id, 'participants', true); 
    if (is_array($participants) && !empty($participants)) { 
        $count = count($participants); 
    } else { 
        $count = 0; 
    }
    
    if ($count > 0) { return "🧡 $count";
        } else { return "🤍 0"; }
 }


 /**
 * Raffle data output (not yet used)
 * Outputs both raffle time and participants count
 * Uses the current post in the loop, or the given $post_id
 */
 function raffle_data(?int $post_id = null): string {
    $raffle_time = raffle_time($post_id);
    $raffle_participants = raffle_count($post_id);

    return "$raffle_time – $raffle_participants";
 }