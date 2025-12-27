<?php
/**
 * Booked tab.
 * 
 * Showing booked posts for the current user.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Extra php functions (not yet used)
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-regret.php'; 

// Get current user ID
$user_ID = get_current_user_id();

// Get category IDs
$booked_categories = loopis_cats(['booked_locker', 'booked_custom', 'locker']);

// Arguments
$args = array(
    'meta_key'       => 'fetcher',
    'meta_value'     => $user_ID,
    'category__in'   => $booked_categories,
    'posts_per_page' => -1,
    'update_post_term_cache' => false,
);

// Query + count
$the_query = new WP_Query($args);
$count = $the_query->found_posts; 
?>

<!--Output-->
<div class="columns">
    <div class="column1">↓ <?php echo $count; ?> annons<?php if ($count != 1) { echo "er"; } ?></div>
    <div class="column2 bottom"><a href="<?php echo esc_url(home_url() . '/profile/' . wp_get_current_user()->user_login . '/fetched'); ?>">Visa hämtade →</a></div>
</div>
<hr>

<div class="post-list">
    <?php if ($the_query->have_posts()) : ?>
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <div class="post-list-post">
                <a href="<?php the_permalink(); ?>">
                    <div class="post-list-post-thumbnail">
                        <?php echo the_post_thumbnail('thumbnail'); ?>
                    </div>
                    <div class="post-list-post-title">
                        <?php the_title(); ?>
                    </div>
                    <div class="post-list-post-meta">
                        <span><?php the_category(' '); ?></span>
                        <span class="right">
                            <i class="fas fa-heart"></i><?php echo human_time_diff(strtotime(get_field('book_date')), current_time('timestamp')); ?> sen
                        </span>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p>💢 Du har inga aktuella paxningar.</p>
    <?php endif; ?>
</div><!--post-list-->

<?php wp_reset_postdata(); ?>