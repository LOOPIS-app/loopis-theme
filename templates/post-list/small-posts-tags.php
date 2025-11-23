<?php
/**
 * Template part for displaying small posts, with tags only, in list view.
 * Used in: page-gifts.php, archive pages, search results, etc.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
	<div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>
	<div class="post-list-post-title"><?php the_title(); ?></div>
	<div class="post-list-post-meta">
		<p><i class="fas fa-hashtag"></i><?php the_tags(''); ?></p>
	</div>
</div>