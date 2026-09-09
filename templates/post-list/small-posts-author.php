<?php
/**
 * Template part for displaying small posts with category + timestamp.
 * Used in: nowhere yet
 * 
 * Improvements:
 * –Output different timestamp depending on category (e.g. time since booking for "booked" category)
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
	<div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>
	<div class="post-list-post-title"><?php the_title(); ?></div>
	<div class="post-list-post-meta">
		<span>👤 <?php the_author_posts_link(); ?></span>
		<span class="right"><i class="fas fa-arrow-alt-circle-up"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> sen</span>
	</div>
</div>