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
include_once LOOPIS_THEME_DIR . '/includes/functions/everyone/sql-pagination.php';

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


$posts_per_page = 50;
$total = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT COUNT(DISTINCT p.ID)
         FROM {$wpdb->posts} p
         INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
         INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
         INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
         WHERE pm.meta_key = %s
         AND pm.meta_value != ''
         AND pm.meta_value IS NOT NULL
         AND EXISTS (
             SELECT 1
             FROM {$wpdb->postmeta} pm2
             WHERE pm2.post_id = p.ID
             AND pm2.meta_key = 'fetcher'
             AND pm2.meta_value = %d
         )
         AND tt.term_id = %d
         AND p.post_status = 'publish'",
        'fetch_date',
        $user_ID,
        $category_id
    )
);

$max_pages = ceil($total/$posts_per_page);

// Set pagination
$pagenum = loopis_GET_pagenum($max_pages);
$offset = ($pagenum - 1)*$posts_per_page;

// Get all things fetched (using SQL for better performance)
global $wpdb;

$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT p.ID, p.post_title, p.post_date, pm.meta_value AS fetch_date
         FROM {$wpdb->posts} p
         INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
         INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
         INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
         WHERE pm.meta_key = %s AND pm.meta_value != '' AND pm.meta_value IS NOT NULL
         AND EXISTS (
             SELECT 1
             FROM {$wpdb->postmeta} pm2
             WHERE pm2.post_id = p.ID AND pm2.meta_key = 'fetcher' AND pm2.meta_value = %d
         )
         AND tt.term_id = %d
         AND p.post_status = 'publish'
         ORDER BY pm.meta_value DESC 
         LIMIT {$posts_per_page} OFFSET {$offset}",
        'fetch_date', $user_ID, $category_id
    )
);

// Count the number of posts retrieved
$count = count($results);
?>

<!-- Output title and instructions -->
<h1><?php list_header_output($url_slug) ?></h1>
<hr>
<p><?php list_instruction_output($url_slug, $count) ?></p>

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