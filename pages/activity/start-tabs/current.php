<?php
/**
 * Posts tab.
 * 
 * Showing active posts for the current user.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Extra php functions (not yet used)
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-remove.php'; 

// Get current user ID
$user_ID = get_current_user_id();

// Get category IDs
$active_categories = loopis_cats(['new', 'first', 'booked_locker', 'locker', 'booked_custom', 'borrow']);

// Arguments
$args = array( 
    'author'         => $user_ID,
    'category__in'   => $active_categories,
    'posts_per_page' => -1,
    'no_found_rows'  => true,
    'update_post_term_cache' => false,
);

// Query + count
$the_query = new WP_Query($args); 
$count = $the_query->found_posts;
?>

<!--Output-->
<div class="columns">
    <div class="column1">↓ <?php echo $count; ?> annons<?php if ($count != 1) { echo "er"; } ?></div>
    <div class="column2 bottom"><a href="<?php echo esc_url(home_url() . '/profile/' . wp_get_current_user()->user_login . '/posts'); ?>">Visa lämnade →</a></div>
</div>
<hr>

<div class="post-list">
    <?php if ($the_query->have_posts()) : ?>
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <div class="post-list-post">
                <a href="<?php the_permalink(); ?>">
                    <div class="post-list-post-thumbnail">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </div>
                    <div class="post-list-post-title">
                        <?php the_title(); ?>
                    </div>
                    <div class="post-list-post-meta">
                        <span><?php the_category(' '); if (in_category(loopis_cat('new'))) { echo raffle_time(); } ?></span>
                        <span class="right"><i class="fas fa-arrow-alt-circle-up"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> sen</span>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p>💢 Du har inga aktuella annonser.</p>
        <p>Gå till <span class="link"><a href="<?php echo esc_url(home_url() . '/profile/' . wp_get_current_user()->user_login . '/posts'); ?>">⬆ Saker lämnade</a></span></p>
        <p>Gå till <span class="link"><a href="/submit/">💚 Ge bort</a></span></p>
    <?php endif; ?>
</div><!--post-list-->

<?php wp_reset_postdata(); ?>