<?php
/**
 * Count all posts submitted and booked by user.
 *
 * Included in /profile/posts
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Count all posts submitted and booked by the specified user.
 */
function user_post_count($user_ID) {
    
    // Count submitted posts.
    $count_posts_submitted = count_posts_submitted($user_ID);

    // Count raffle posts
    $count_posts_new = count_posts_new($user_ID);

    // Count waiting posts
    $count_posts_old = count_posts_old($user_ID);

    // Count active posts
    $count_posts_active = count_posts_active($user_ID);

    // Count given posts
    $count_posts_given = count_posts_given($user_ID);
    
    // Count booked posts
    $count_posts_booked = count_posts_booked($user_ID);

    // Count locker posts
    $count_posts_locker = count_posts_locker($user_ID);

    // Count removed posts
    $count_posts_removed = count_posts_removed($user_ID);

    // Count archived posts
    $count_posts_archived = count_posts_archived($user_ID);

    // Count paused posts
    $count_posts_paused = count_posts_paused($user_ID);

    // Count disappeared posts
    $count_posts_disappeared = count_posts_disappeared($user_ID);

    // Count other users claimed posts
    $count_others_claimed = count_others_claimed($user_ID);

    // Count other users booked posts
    $count_others_booked = count_others_booked($user_ID);

    // Count other users fetched posts
    $count_others_fetched = count_others_fetched($user_ID);
    
    return array(
            'count_posts_submitted' => $count_posts_submitted,
            'count_posts_new' => $count_posts_new,
            'count_posts_old' => $count_posts_old,
            'count_posts_active' => $count_posts_active,
            'count_posts_given' => $count_posts_given,
            'count_posts_booked' => $count_posts_booked,
            'count_posts_locker' => $count_posts_locker,
            'count_posts_removed' => $count_posts_removed,
            'count_posts_archived' => $count_posts_archived,
            'count_posts_paused' => $count_posts_paused,
            'count_posts_disappeared' => $count_posts_disappeared,
            'count_others_claimed' => $count_others_claimed,
            'count_others_booked' => $count_others_booked,
            'count_others_fetched' => $count_others_fetched,
        );
}


/**
 * Count submitted posts.
 */
function count_posts_submitted($user_ID) {
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
 * Count active posts
 */
function count_posts_active($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => loopis_cats(['new', 'old', 'raffle', 'first']), // Remove raffle and first after migration
        'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

/**
 * Count raffle posts
 */
function count_posts_new($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => loopis_cats(['new', 'raffle']), // Remove 'raffle' after migration
        'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

/**
 * Count waiting posts
 */
function count_posts_old($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => loopis_cats(['old', 'first']), // Remove 'first' after migration
        'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

/**
 * Count fetched posts (given).
 */
function count_posts_given($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => loopis_cat('fetched'),
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->post_count;
    wp_reset_postdata();
    return $count;
}

/**
 * Count booked posts.
 */
function count_posts_booked($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => loopis_cats(['booked_custom', 'booked_locker', 'booked']),  // Remove 'booked_locker' after migration
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->post_count;
    wp_reset_postdata();
    return $count;
}

/**
 * Count locker posts.
 */
function count_posts_locker($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => loopis_cat('locker'),
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->post_count;
    wp_reset_postdata();
    return $count;
}

/**
 * Count removed posts.
 */
function count_posts_removed($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => loopis_cat('removed'),
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

/**
 * Count archived posts.
 */
function count_posts_archived($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => loopis_cat('archived'),
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

/**
 * Count paused posts.
 */
function count_posts_paused($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => loopis_cat('paused'),
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

/**
 * Count disappeared posts.
 */
function count_posts_disappeared($user_ID) {
    $args = array(
        'author'     => $user_ID,
        'post_type'  => 'post',
        'cat'        => loopis_cat('disappeared'),
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    wp_reset_postdata();
    return $count;
}

// OTHER PEOPLE POSTS

/**
 * Count other users posts claimed by current user (including all states of booked + fetched).
 * Comment: Query Monitor says "Slow query"
 */
function count_others_claimed($user_ID) {
    $args = array(
        'post_type'   => 'post',
        'cat'        => loopis_cats(['booked_custom', 'booked_locker', 'booked', 'locker']), // Remove 'booked_locker' after migration
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
 * Count other users posts booked by current user (strictly booked, not fetched).
 * Comment: Query Monitor says "Slow query"
 */
function count_others_booked($user_ID) {
    $args = array(
        'post_type'   => 'post',
        'cat'        => loopis_cats(['booked_custom', 'booked_locker', 'booked', 'locker']), // Remove 'booked_locker' after migration
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
 * Count other users posts fetched by current user.
 */
function count_others_fetched($user_ID) {
    $args = array(
        'post_type'   => 'post',
        'cat'        => loopis_cat('fetched'),
		'meta_key'    => 'fetcher',
        'meta_value'  => $user_ID,
		'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);
    $count = $the_query->post_count;
    wp_reset_postdata();
    return $count;
}


