<?php
/**
 * Template part for displaying big posts, with all post meta, in list view.
 * Used in: page-gifts.php, archive pages, search results, etc.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="post-list-post-big" onclick="location.href='<?php the_permalink(); ?>';">
    <div class="post-list-post-thumbnail-big"><?php the_post_thumbnail('thumbnail'); ?></div>
    <div class="post-list-post-title-big"><?php the_title(); ?></div>
    <div class="post-list-post-meta">
        <p><?php the_category(' '); ?><?php if (in_category('new')) { echo raffle_time(); } ?></p>
    <p><i class="fas fa-walking"></i><?php echo get_field('location'); ?></p>
    <p><i class="fas fa-hashtag"></i><?php the_tags(''); ?></p>
     </div>
</div>