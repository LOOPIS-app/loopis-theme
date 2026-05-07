<?php
/**
 * Template part for displaying small posts with tags instead of category.
 * Used in: search.php for recommendations, only?
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
	<div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>
	<div class="post-list-post-title"><?php the_title(); ?></div>
	<div class="post-list-post-meta">
		<span><i class="fas fa-hashtag"></i><?php the_tags(''); ?></span>
	</div>
</div>