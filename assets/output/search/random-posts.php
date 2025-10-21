<?php
/**
 * Three random posts for search page
 * 
 * Included in searchform.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Count all current posts
$count_args = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
	'category__in'   => array(1, 37, 57, 147),
);
$count_query = new WP_Query($count_args);
$count = $count_query->found_posts;
?>

<?php
// Get random posts from categories 1 or 37
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'orderby'        => 'rand',
    'category__in'   => array(1, 37),
);
$the_query = new WP_Query($args);
?>

<div class="columns"><div class="column1">↓ Tre förslag</div>
<div class="column2"><a href="/category/stuff/">Visa alla <?php echo $count; ?> →</a></div></div>
<hr>
<div class="post-list">
<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
	<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
		<div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>
		<div class="post-list-post-title"><?php the_title(); ?></div>
		<div class="post-list-post-meta">
			<p><i class="fas fa-hashtag"></i><?php the_tags(''); ?></p>
		</div>
    </div>
<?php endwhile; ?>
</div>

<?php wp_reset_postdata(); ?>