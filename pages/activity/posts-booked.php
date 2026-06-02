<?php
/**
 * List of posts booked by the current user.
 * 
 * Reached on https://loopis.app/activity/?view=posts-booked
 * 
 * Uses post-list-output.php to output title and instructions.
 * Uses a non-existent category slug: others_booked
 * Uses an optional user ID from URL to show posts from that user: &id=user_ID
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

// Set the category (non-existing slug for others posts booked user)
$url_slug = 'others_booked';
$category_ids = loopis_cats(['booked_custom', 'booked', 'locker']); 
$placeholders = implode(',', $category_ids);

// Handle search and pagination
$search = (string)($_GET['search'] ?? false);
$view = (string) $_GET['view'] ?? '';
$tags = (array) (!empty($_GET['tag']) ? [loopis_tag($_GET['tag'])] : []);
$posts_per_page = 50;
[$results, $total] = loopis_get_posts_query($view,  $user_ID, false, $category_ids, $tags, $search);
$max_pages = ceil($total/$posts_per_page);
$pagenum = loopis_GET_pagenum($max_pages);
$offset = ($pagenum - 1)*$posts_per_page;
set_query_var( 'search_postids', wp_list_pluck($results, 'ID'));

// Count the number of posts retrieved
$count = count($results);
?>

<!-- Output title and instructions -->
<h1><?php list_header_output($url_slug) ?></h1>
<hr>
<p><?php list_instruction_output($url_slug, $count) ?></p>

<div class="columns"><div class="column1">↓ <?php if ($count !== 1) { echo $offset." -";} ?><?php echo " ".($offset+$count); ?><?php echo " av " . $total . " totalt"; ?></div>
<div class="column2 small">💡 Senaste överst</div></div>
<hr>

<!-- Output post list -->
<div class="post-list">
<?php if (!empty($results)) : ?>
    <?php foreach ($results as $post) : ?>
        <?php
        // Get post data
        $post_id = $post->ID;
        $post_title = $post->post_title;
        $book_date = $post->book_date; // Not used yet, but could be used for metadata output
        $permalink = get_permalink($post_id);
        $thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');
        ?>
        <div class="post-list-post" style="position:relative;">
            <div class="post-list-post-thumbnail" onclick="location.href='<?php echo esc_url($permalink); ?>';">
                <?php echo $thumbnail; ?>
            </div>
            <div class="post-list-post-title"><?php echo esc_html($post_title); ?></div>
            <div class="notif-meta post-list-post-meta">
                <span><?php the_category(' '); ?></span>
            </div>
        </div><!--post-list-post-->
    <?php endforeach; ?>
<?php else : ?>
    <p>💢 Du har inte paxat några saker ännu.</p>
<?php endif; ?>
<?php loopis_sql_pagination($max_pages);?>
</div><!--post-list-->

<?php get_footer(); ?>