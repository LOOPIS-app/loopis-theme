<?php
/**
 * Template part for displaying support posts, with all post meta, in list view.
 * Used in: archive-support.php
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get variables
$post_id = get_the_ID();

// Private support posts are visible only to the author or loopis_support users.
$author_id = (int) get_post_field('post_author', $post_id);
$current_id = (int) get_current_user_id();
$is_private_support = has_term('private', 'support-category', $post_id);
$support_categories = wp_list_pluck((array) get_the_terms($post_id, 'support-category'), 'name');
if ($is_private_support && $current_id !== $author_id && !current_user_can('loopis_support') && !current_user_can('manage_options')) {
    return;
}

?>

    <div class="post-list-cpt" onclick="location.href='<?php the_permalink(); ?>';">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-list-cpt-thumbnail">
                <?php the_post_thumbnail('thumbnail'); // Display the square thumbnail ?>
            </div>
        <?php endif; ?>
        <div class="post-list-cpt-title">🗨 <?php echo esc_html(strip_emoji(get_the_title())); ?></div>
        <div class="post-list-cpt-excerpt"><?php echo get_the_excerpt(); ?></div>
        <div class="post-list-cpt-meta">
            <?php foreach ($support_categories as $support_category_name) : ?>
                <span><?php echo esc_html($support_category_name); ?></span>
            <?php endforeach; ?>
            <span><i class="far fa-clock"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp'));?> sen</span>
            <span><i class="far fa-comment"></i><?php echo get_comments_number(); ?></span>
            <!--span>👤 php echo get_the_author_posts_link(); </span-->
        </div>
    </div>