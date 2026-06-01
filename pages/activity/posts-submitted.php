<?php
/**
 * List of posts submitted by the current user.
 * 
 * Reached on https://loopis.app/activity/?view=posts-submitted
 * 
 * Uses post-list-output.php to output title, instructions and buttons.
 * Uses an optional category slug from URL to filter posts: &status=category-slug
 * Uses an optional user ID from URL to show posts from that user: &id=user_ID
 * Shows actions buttons if the user is viewing their own list.
 * 
 * Improvements:
 * – Use a template for post list output? (needs a fix for outputting different buttons and metadata)
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include post list output functions
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-list-output.php';

// Include sql-pagination functionality
include_once LOOPIS_THEME_DIR . '/templates/post-list/pagination-sql.php';

// Get current user ID
$user_ID = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : wp_get_current_user()->ID;

// Is current user watching their own list?
if (intval($user_ID)===intval(wp_get_current_user()->ID)){
    $user_has_stuff = true;
    // Include post action functions
    include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-extend.php';
    include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-remove.php';
    include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-pause.php';
} else {
    $user_has_stuff = false;
}

// Get category IDs from URL slug 
$url_slug = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

// Set multiple category IDs if status is booked
if ($url_slug === 'booked') {
 	$category_ids = loopis_cats(['booked', 'booked_custom']); 
} else if ($url_slug === 'all') {
    $category_ids = array();
} else {
	// Get the category by slug
	$category_id = loopis_cat($url_slug);
	$category_ids = $category_id ? array($category_id) : array();
}

// Handle search and pagination
$search = (string)($_GET['search'] ?? false);
$view = (string) $_GET['view'] ?? '';
$tags = (array) (!empty($_GET['tag']) ? [loopis_tag($_GET['tag'])] : []);
$posts_per_page = 50;
[$results, $total] = loopis_get_posts_query($view,  false, $user_ID, $category_ids, $tags, $search);
$max_pages = ceil($total/$posts_per_page);
$pagenum = loopis_GET_pagenum($max_pages);
$offset = ($pagenum - 1)*$posts_per_page;

// Count the number of posts retrieved
$count = count($results);
?>

<!-- Output title and instructions -->
<h1><?php list_header_output($url_slug) ?></h1>
<hr>
<p><?php list_instruction_output($url_slug, $count) ?></p>
<?php get_template_part('templates/search/search-form-sql'); ?>
<div class="columns"><div class="column1">↓ <?php if ($count !== 1) { echo $offset." -";} ?><?php echo " ".($offset+$count); ?><?php echo " av " . $total . " totalt"; ?></div>
<div class="column2 small">💡 Senast överst</div></div>
<hr>
<!-- Output post list -->
<div class="post-list">
<?php if (!empty($results)) : ?>
    <?php foreach ($results as $post) : ?>
        <?php
        // Get post data
        $post_id = $post->ID;
        $post_title = $post->post_title;
        $post_date = $post->post_date; // Already fetched in the query
        $permalink = get_permalink($post_id);
        $thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');

        // Check if post already has been forwarded
        $forward_post_id = get_post_meta($post_id, 'forward_post', true);
        if ($forward_post_id) {
            $forward_post_category = get_the_category($forward_post_id);
            // Later: Fetch category symbol from current status of the forwarded post
        }
        ?>
        <div class="post-list-post" style="position:relative;">
            <div class="post-list-post-thumbnail" onclick="location.href='<?php echo esc_url($permalink); ?>';">
                <?php echo $thumbnail; ?>
            </div>
            <div class="post-list-post-title"><?php echo esc_html($post_title); ?></div>
            <?php if ($user_has_stuff) { list_button_output($url_slug, $post_id); } ?>
            <div class="notif-meta post-list-post-meta">
				<span>
					<?php the_category(' '); ?><?php if (in_category('new')) { echo raffle_time(); } ?> 
				</span>
            </div>
        </div><!--post-list-post-->
    <?php endforeach; ?>
<?php else : ?>
		<p>💢 Du har inga annonser med denna status.</p>
<?php endif; ?>
<?php 
loopis_sql_pagination($max_pages);
?>
</div><!--post-list-->

<?php wp_reset_postdata(); ?>