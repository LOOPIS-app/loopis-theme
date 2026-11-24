<?php
/**
 * List of possible tips posts.
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$admin_url = home_url('/admin/');
// Include post list output functions
include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-list-output.php';

// Include sql-pagination functionality
include_once LOOPIS_THEME_DIR . '/templates/post-list/pagination-sql.php';

// Get current user ID
$user_ID = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : wp_get_current_user()->ID;

// Set the category (non-existing slug for others posts fetched by user)
$url_slug = 'mail_tips';
$category_id = loopis_cats(['new','old',]);

// Handle search and pagination
$search = (string)($_GET['search'] ?? false);
$view = (string) $_GET['view'] ?? '';
$tags = (array) (!empty($_GET['tag']) ? [loopis_tag($_GET['tag'])] : []);
$posts_per_page = 50;
[$results, $total] = loopis_get_posts_query($view, false, false, $category_id, $tags, $search);
$max_pages = ceil($total/$posts_per_page);
$pagenum = loopis_GET_pagenum($max_pages);
$offset = ($pagenum - 1)*$posts_per_page;


// Count the number of posts retrieved
$count = count($results);
if (isset($_GET['empty_mail_tips'])) {
    check_admin_referer('empty_mail_tips');

    if (!current_user_can('edit_posts')) {
        wp_die('You are not allowed to do this.');
    }

    $taxonomy = 'admin-tag';
    $term = get_term_by('slug', 'mail_tips', $taxonomy);

    if ($term && !is_wp_error($term)) {
        $post_ids = get_posts([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => [[
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => 'mail_tips',
            ]],
        ]);

        foreach ($post_ids as $post_id) {
            wp_remove_object_terms($post_id, $term->term_id, $taxonomy);
        }
    }
    wp_safe_redirect(remove_query_arg(['empty_mail_tips', '_wpnonce']));
}

?>

<!-- Output title and instructions -->
<h1> 💡 Möjliga tips</h1>
<hr>
<p>Här kan du lägga till tips till mailutskicket!</p>
    <p><span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/mail', $admin_url) ); ?>">
        Skicka mejlet med dina tips!
    </a></span></p>
<?php get_template_part('templates/forms/search-form-sql'); ?>
<div class="columns">
    <div class="column1">↓<?php if ($count !== 1) { echo $offset." -";} ?><?php echo " ".($offset+$count); ?><?php echo " av " . $total . " totalt"; ?></div>
    <div class="column2 small">
        <?php $url = add_query_arg('empty_mail_tips', '1'); $url = wp_nonce_url($url, 'empty_mail_tips'); ?> 
        <a href="<?php echo esc_url($url); ?>"  onclick="return confirm('Töm mailtips?')">🗑️ Töm mailtips</a>     
    </div>
</div>
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
        $cat = array_values(get_the_category($post_id));
        $cat_name = !empty($cat) ? $cat[0]->name : '';
        $thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');


        ?>
        <div class="post-list-post" style="position:relative;">
            <div class="post-list-post-thumbnail" onclick="location.href='<?php echo esc_url($permalink); ?>';">
                <?php echo $thumbnail; ?>
            </div>
            <div class="post-list-post-title"><?php echo esc_html($post_title); ?></div>
            <?php list_button_output($url_slug, $post_id);  ?>
            <div class="notif-meta post-list-post-meta">
                <span>
                    <?php echo $cat_name; ?>, Upplagd för 
                    <?php echo human_time_diff(strtotime($post_date), current_time('timestamp')); ?> sen
                </span>
            </div>
        </div><!--post-list-post-->
    <?php endforeach; ?>
<?php else : ?>
    <p>💢 Det finns inga möjliga tips.</p>
<?php endif; ?>
<?php loopis_sql_pagination($max_pages);?>
</div><!--post-list-->


<?php get_footer(); ?>