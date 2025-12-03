<?php
/**
 * List of posts for the current user.
 * 
 * Uses category slug from URL to filter posts and custom functions to output content.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Output functions
include_once __DIR__ . '/functions/profile-list-components.php';

// Include post action functions
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-forward.php';
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-extend.php';
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-remove.php';
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-pause.php';
?>

<div class="content">
	<div class="page-padding">

<?php
// Get current user ID
$user_ID = wp_get_current_user()->ID;

// Get the category slug from the URL (e.g. /?status=paused)
$category_slug = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

// Get the category by slug
$category = get_category_by_slug($category_slug);
$category_id = $category ? $category->term_id : 0; // Ensure $category_id is 0 if the category is invalid

// Initialize $the_query and $count (to avoid errors if the category is invalid)
$the_query = new WP_Query();
$count = 0;

// Get posts (if the category ID is valid)
if ($category_id > 0) {
    // Get posts from the specified category
    $args = array(
        'author' => $user_ID,
        'cat' => $category_id,
        'posts_per_page' => -1,
    );

$the_query = new WP_Query($args);
$count = $the_query->found_posts;
}
?>

<!-- Output title and instructions -->
<h1><?php list_header_output($category_slug) ?></h1>
<hr>
<p><?php list_instruction_output($category_slug, $count) ?></p>

<div class="columns"><div class="column1">↓ <?php echo $count; ?> annons<?php if ($count !== 1) { echo "er"; } ?></div>
<div class="column2"></div></div>
<hr>

<div class="post-list">
<?php if ( $the_query->have_posts() ) : ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); $post_id = get_the_ID(); ?>
	<div class="post-list-post" style="position:relative;">
		<div class="post-list-post-thumbnail" onclick="location.href='<?php the_permalink(); ?>';"><?php echo the_post_thumbnail('thumbnail'); ?></div>
		<div class="post-list-post-title"><?php the_title(); ?></div>
		<?php list_button_output($category_slug, $post_id) ?>
		<div class="notif-meta post-list-post-meta">
			<span><?php list_category_output($category_slug) ?></span>
		</div>
	</div><!--post-list-post-->
<?php endwhile; ?>
		
<?php else : ?>
		<p>💢 Du har inga annonser med status <span class="label"><?php list_category_output($category_slug) ?></span></p>
<?php endif; ?>
</div><!--post-list-->

<?php wp_reset_postdata(); ?>

<?php get_footer(); ?>