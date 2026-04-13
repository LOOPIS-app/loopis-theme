<?php
/**
 * List of posts fetched by the current user.
 * 
 * Reached on https://loopis.app/activity/?view=posts-fetched
 * Linked from /profile/user_nicename/posts (WPUM profile page) 
 * 
 * Uses non-existent category slug from URL to filter posts and a function to output labels and content.
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
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-forward.php';

// Include sql-pagination functionality
include_once LOOPIS_THEME_DIR . '/templates/post-list/pagination-sql.php';

// Get current user ID
$user_ID = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : wp_get_current_user()->ID;
// Set show forward
if (intval($user_ID)===intval(wp_get_current_user()->ID)){
    $user_has_stuff = true;
} else{
    $user_has_stuff = false;
}

// Set the category (non-existing slug for forwarded posts)
$url_slug = 'others_fetched';
$category_id = loopis_cat('fetched');
$search = (string)($_GET['search'] ?? false);
$view = (string) $_GET['view'] ?? '';
$tags = (array) (!empty($_GET['tag']) ? [loopis_tag($_GET['tag'])] : []);
$posts_per_page = 50;
[$results, $total] = loopis_get_posts_query($view,  $user_ID, false, $category_id, $tags, $search);
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
<div class="columns"><div class="column1">↓ Visar annons<?php if ($count !== 1) { echo "er ".$offset." -";} ?><?php echo " ".($offset+$count); ?><?php echo " av " . $total . " totalt"; ?></div>
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
        $fetch_date = $post->fetch_date; // Already fetched in the query
        $permalink = get_permalink($post_id);
        $thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');

        // Check if post already has been forwarded
        $forward_post_id = get_post_meta($post_id, 'forward_post', true);
        if ($forward_post_id) {
            $forward_post_category = get_the_category($forward_post_id);
            // Later: Fetch category symbol
        }
        ?>
        <div class="post-list-post" style="position:relative;">
            <div class="post-list-post-thumbnail" onclick="location.href='<?php echo esc_url($permalink); ?>';">
                <?php echo $thumbnail; ?>
            </div>
            <div class="post-list-post-title"><?php echo esc_html($post_title); ?></div>
            <?php if ($forward_post_id) { 
                // Later: Add button to view the forwarded post
                } else { if ($user_has_stuff){list_button_output($url_slug, $post_id); }} ?>
            <div class="notif-meta post-list-post-meta">
                <span>
                    <?php list_category_output($url_slug); ?> för 
                    <?php echo human_time_diff(strtotime($fetch_date), current_time('timestamp')); ?> sen
                </span>
            </div>
        </div><!--post-list-post-->
    <?php endforeach; ?>
<?php else : ?>
    <p>💢 Du har inte hämtat några saker ännu.</p>
<?php endif; ?>
<?php loopis_sql_pagination($max_pages);?>
</div><!--post-list-->

<?php get_footer(); ?>