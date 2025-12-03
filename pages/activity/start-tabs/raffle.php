<?php
/**
 * Raffle tab.
 * 
 * Showing current/recent raffles where the current user is participant.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Extra php functions (not yet used)
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-participate.php'; 

// Get current user ID
$user_id = get_current_user_id();

// Arguments - OPTIMIZED
$args = array(
    'date_query' => array(
        array(
            'after'     => '3 days ago',
            'inclusive' => true,
        ),
    ),
    'posts_per_page' => 100, // Limit to prevent massive queries
    'fields'     => 'ids',
    'no_found_rows' => true, // Don't calculate total rows
    'update_post_meta_cache' => false, // Don't load post meta yet
    'update_post_term_cache' => false, // Don't load categories yet
    'meta_query' => array(
        array(
            'key'     => 'participants',
            'compare' => 'EXISTS',
        ),
    ),
);

// Query
$the_query = new WP_Query($args);
$matching_posts = array();

// Filter posts to check user_id and not index (solution by Poe)
if ($the_query->have_posts()) {
    foreach ($the_query->posts as $post_id) {
        $participants = get_post_meta($post_id, 'participants', true);
        if (!empty($participants)) {
            $participants_array = maybe_unserialize($participants);
            if (is_array($participants_array) && in_array($user_id, $participants_array)) {
                $matching_posts[] = $post_id;
            }
        }
    }
}

// Clean up first query
wp_reset_postdata();

// Now get full post data ONLY for matching posts
if (!empty($matching_posts)) {
    $final_query = new WP_Query(array(
        'post__in' => $matching_posts,
        'posts_per_page' => -1,
        'orderby' => 'post__in',
    ));
}

// Output
$count = count($matching_posts);
?>

<!--Output-->
<div class="columns"><div class="column1">↓ <?php echo $count; ?> lottningar</div>
<div class="column2 bottom"></div></div>
<hr>

<div class="post-list">

<?php if (!empty($matching_posts) && $final_query->have_posts()): ?>

<?php while ($final_query->have_posts()) : $final_query->the_post(); ?>

    <div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
        <div class="post-list-post-thumbnail">
            <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('thumbnail');
            }
            ?>
        </div>
        <div class="post-list-post-title">
            <?php the_title(); ?>
        </div>
        <div class="post-list-post-meta">
            <span><?php 
            if (in_category('new')) { 
                the_category(' '); 
                echo raffle_time(); 
            } else {
                $fetcher = get_post_meta(get_the_ID(), 'fetcher', true);
                if ($fetcher == $user_id) { 
                    echo '🥳 Du vann!'; 
                } else { 
                    echo '💔 Du vann tyvärr inte'; 
                } 
            } 
            ?></span>
            <span class="right"><i class="fas fa-arrow-alt-circle-up"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> sen</span>
        </div>
    </div>        
<?php endwhile; ?>

<?php else : ?>
    <p>💢 Du har inga aktuella lottningar.</p>
<?php endif; ?>

</div> <!--post-list-->

<?php wp_reset_postdata(); ?>