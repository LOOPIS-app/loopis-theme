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

// Arguments
$args = array(
    'date_query' => array(
        array(
            'after'     => '3 days ago',
            'inclusive' => true,
        ),
    ),
    'fields'     => 'ids',
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
    while ($the_query->have_posts()) {
        $the_query->the_post();
        $post_id = get_the_ID();
        $participants = get_post_meta($post_id, 'participants', true);
        if (!empty($participants)) {
            $participants_array = maybe_unserialize($participants);
            if (is_array($participants_array) && in_array($user_id, $participants_array)) {
                $matching_posts[] = $post_id;
            }
        }
    }
}

// Output
$count = count($matching_posts);
?>

<!--Output-->
<div class="columns"><div class="column1">↓ <?php echo $count; ?> lottningar</div>
<div class="column2 bottom"></div></div>
<hr>

<div class="post-list">

<?php if (!empty($matching_posts)): ?>

<?php foreach ($matching_posts as $post_id): ?>

    <div class="post-list-post" onclick="location.href='<?php the_permalink($post_id); ?>';">
        <div class="post-list-post-thumbnail">
            <?php echo get_the_post_thumbnail($post_id, 'thumbnail'); ?>
        </div>
        <div class="post-list-post-title">
            <?php echo get_the_title($post_id); ?>
        </div>
        <div class="post-list-post-meta">
            <span><?php if (in_category('new', $post_id)) { echo get_the_category_list(' ', '', $post_id); echo  raffle_time_post_id($post_id); } else {
            $fetcher = get_post_meta($post_id, 'fetcher', true);
            if ($fetcher == $user_id) { echo '🥳 Du vann!'; }
            else { echo '💔 Du vann tyvärr inte'; } } ?></span>
            <span class="right"><i class="fas fa-arrow-alt-circle-up"></i><?php echo human_time_diff(get_the_time('U', $post_id), current_time('timestamp')); ?> sen</span>
        </div>
    </div>        
<?php endforeach; ?>
<?php wp_reset_postdata(); ?>

<?php else : ?>
    <p>💢 Du har inga aktuella lottningar.</p>
<?php endif; ?>

</div> <!--post-list-->