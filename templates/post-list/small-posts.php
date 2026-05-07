<?php
/**
 * Template part for displaying small posts with category only.
 * Used in: nowhere yet
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
	<div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>
	<div class="post-list-post-title"><?php the_title(); ?></div>
	<div class="post-list-post-meta">
		<span><?php the_category(' '); ?><?php if (in_category('new')) { echo raffle_time(); } ?></span>
	</div>
</div>