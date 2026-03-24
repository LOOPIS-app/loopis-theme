<?php
/**
 * List of posts in a specific category for the current user.
 * 
 * Reached on https://loopis.app/activity/?view=posts-submitted&status=category-slug
 * Linked from /profile/user_nicename/posts (WPUM profile page) 
 * 
 * Uses category slug from URL to filter posts and a function to output labels and content.
 * 
 * Improvements:
 * – Add search form if more than 20 posts (adaptation of templates/search/search-form.php?)
 * – Add pagination (will templates/post-list/pagination.php work with the URL structure?)
 * – Later: Use a template for post list output? (needs a fix for output of specific button and metadata)
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include post list output functions
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-list-output.php';

// Include post action functions
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-extend.php';
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-remove.php';
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-pause.php';
?>

<?php
// Get current user ID
$user_ID = wp_get_current_user()->ID;

// Get category slug from the URL (e.g. /?status=paused)
$url_slug = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

/** Set category IDs from URL slug */

// Set multiple IDs for booked posts
if ($url_slug === 'booked') {
	$category_ids = loopis_cats(['booked', 'booked_locker', 'booked_custom']); // booked_locker will be renamed to booked after migration


// Set multiple IDs for old posts (Remove after migration)
} elseif ($url_slug === 'old') {
	$category_ids = loopis_cats(['old', 'first']); // first will be deprecated

// Set single ID for other slugs
} else {
	// Get the category by slug
	$category_id = loopis_cat($url_slug);
	$category_ids = $category_id ? array($category_id) : array();
}

// Query args to get posts from current user...
$args = array(
	'author' => $user_ID,
	'posts_per_page' => -1,
);

// ...with category filter if ID(s) are set...
if (!empty($category_ids)) {
	$args['category__in'] = $category_ids;
}

// ...and go!
$the_query = new WP_Query($args);
$count = $the_query->found_posts;
?>

<!-- Output title and instructions -->
<h1><?php list_header_output($url_slug) ?></h1>
<hr>
<p><?php list_instruction_output($url_slug, $count) ?></p>

<div class="columns"><div class="column1">↓ <?php echo $count; ?> annons<?php if ($count !== 1) { echo "er"; } ?></div>
<div class="column2 small">💡 Senast överst</div></div>
<hr>
<!-- Output post list -->
<div class="post-list">
<?php if ( $the_query->have_posts() ) : ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); $post_id = get_the_ID(); ?>
	<div class="post-list-post" style="position:relative;">
		<div class="post-list-post-thumbnail" onclick="location.href='<?php the_permalink(); ?>';"><?php echo the_post_thumbnail('thumbnail'); ?></div>
		<div class="post-list-post-title"><?php the_title(); ?></div>
		<?php list_button_output($url_slug, $post_id) ?>
		<div class="notif-meta post-list-post-meta">
			<span><?php the_category(' '); ?><?php if (in_category('new')) { echo raffle_time(); } ?></span>
		</div>
	</div><!--post-list-post-->
<?php endwhile; ?>
		
<?php else : ?>
		<p>💢 Du har inga annonser med denna status.</p>
<?php endif; ?>
</div><!--post-list-->

<?php wp_reset_postdata(); ?>