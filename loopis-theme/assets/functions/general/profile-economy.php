<?php
/**
 * Profile economy for LOOPIS user.
 *
 * Included for everyone in functions.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Calculate economy for the specified user.
 * 
 * Used when booking + on profile and author page.
 */
function get_economy($user_ID) {

    // Count payments + recieved coins
    $count_payments = count_payments($user_ID);
    $payments_membership = $count_payments['payments_membership'];
    $payments_coins = $count_payments['payments_coins'];
    $membership_coins = $count_payments['membership_coins'];
    $bought_coins = $count_payments['bought_coins'];
    $joined_date = $count_payments['joined_date'];
    
    // Count rewards + recieved stars
    $count_rewards = count_rewards($user_ID);
    $rewards = $count_rewards['rewards'];
    $reward_stars = $count_rewards['reward_stars'];
    
    // Count things given
    $count_given = count_given($user_ID);
    
    // Count things booked
    $count_booked = count_booked($user_ID);
    
    // Count things borrowed
    $count_borrowed = count_borrowed($user_ID);
    
    // Count things submitted
    $count_submitted = count_submitted($user_ID);
        
    // Count things deleted
    $count_deleted = count_deleted($user_ID);
    
    // Calculate stars-covers
    $stars = $reward_stars;
    $star_coins = $stars;
        
    // Calculate clovers and clover-coins
    $clovers = $count_submitted + $count_booked + $count_borrowed;
    $clover_coins = floor($clovers / 10);
        settype($clover_coins, 'integer');
    
    // Calculate coins
    $coins = $membership_coins + $bought_coins + $clover_coins + $star_coins + $count_given - $count_booked - $count_borrowed;
    
    return array(
            'payments_membership' => $payments_membership,
            'payments_coins' => $payments_coins,
            'membership_coins' => $membership_coins,
            'bought_coins' => $bought_coins,
            'joined_date' => $joined_date,
            'count_given' => $count_given,
            'count_booked' => $count_booked,
            'count_borrowed' => $count_borrowed,
            'count_submitted' => $count_submitted,
            'count_deleted' => $count_deleted,
            'stars' => $stars,
            'star_coins' => $star_coins,
            'clovers' => $clovers,
            'clover_coins' => $clover_coins,
            'coins' => $coins,
        );
}

/**
 * Count payments + recieved coins
 */
function count_payments($user_ID) {

    $payments_membership = 0;
    $payments_coins = 0;
    $membership_coins = 0;
    $bought_coins = 0;
    $joined_date = null;
    
    $meta_key = 'wpum_payments';
    $meta_values = get_user_meta($user_ID, $meta_key, true);
    
    if (!empty($meta_values) && is_array($meta_values)) {
        foreach ($meta_values as $row) {
            $payment_type = $row['wpum_payment_type'][0]['value'];
            $received_coins = $row['wpum_received_coins'][0]['value'];
            $payment_date = $row['wpum_payment_date'][0]['value'];
    
        if ($payment_type == 'Medlemskap') {
            $payments_membership++;
            $membership_coins += $received_coins;
            $joined_date = new DateTime($payment_date);
            $joined_date = $joined_date->format('Y-m-d');
        } elseif ($payment_type == 'Mynt') {
            $payments_coins++;
            $bought_coins += $received_coins;
         } } }
    
    return array(
        'payments_membership' => $payments_membership,
        'payments_coins' => $payments_coins,
        'membership_coins' => $membership_coins,
        'bought_coins' => $bought_coins,
        'joined_date' => $joined_date,
    );
}

/**
 * Count rewards + recieved stars
 */
function count_rewards($user_ID) {
    $rewards = 0;
    $reward_stars = 0;

    $meta_key = 'wpum_rewards';
    $meta_values = get_user_meta($user_ID, $meta_key, true);

    if (!empty($meta_values) && is_array($meta_values)) {
        foreach ($meta_values as $row) {
            // Check if received_stars exists and is not empty, otherwise default to 1
            $received_stars = !empty($row['wpum_received_stars'][0]['value']) 
                ? (int) $row['wpum_received_stars'][0]['value'] 
                : 1;

            $rewards++; // Increment the rewards count
            $reward_stars += $received_stars; // Add the received stars
        }
    }

    return array(
        'rewards' => $rewards,
        'reward_stars' => $reward_stars,
    );
}

/**
 * Count things given.
 */
function count_given($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => 41,
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->post_count;
    wp_reset_postdata();
    return $count;
}

/**
 * Count things booked.
 * Comment: Query Monitor says "Slow query"
 */
function count_booked($user_ID) {
    $args = array(
        'post_type'   => 'post',
        'cat'        => '41, 57, 104, 147',
		'meta_key'    => 'fetcher',
        'meta_value'  => $user_ID,
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->post_count;
    wp_reset_postdata();
    return $count;
}

/**
 * Count things borrowed.
 */
function count_borrowed($user_ID) {
    $the_query = wpum_get_posts_for_profile($user_ID, array('post_type' => 'booking'));
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

/**
 * Count submitted posts.
 */
function count_submitted($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

/**
 * Count deleted posts.
 */
function count_deleted($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => 58,
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

/**
 * Count things fetched
 * Comment: Is this used...?
 */
function count_fetched($user_ID) {
    $args = array(
        'post_type'   => 'post',
        'cat'        => 41,
		'meta_key'    => 'fetcher',
        'meta_value'  => $user_ID,
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->post_count;
    wp_reset_postdata();
    return $count;
}