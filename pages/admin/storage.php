<?php
/**
 * Storage page
 * 
 * Dynamic content of page-admin.php
 * Reached on /admin/?view=storage
 * 
 * Shows all items in storage category
 * Access restricted to users with loopis_storage capability
 * Includes search functionality for finding specific items
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📦 Lager</h1>
<hr>
<p class="small">💡 Här ser du alla saker i kategorin <span class="small-label">📦 Lager</span></p>

<?php if (current_user_can('manage_options') || current_user_can('loopis_storage')) : ?>

    <?php
    // Get search term
    $search_term = isset($_GET['storage_search']) ? sanitize_text_field($_GET['storage_search']) : '';

    // Get category IDs
    $storage_category = loopis_cat('storage');

    // Query arguments
    $args = array(
        'post_type'      => 'post',
        'cat'            => $storage_category,
        'posts_per_page' => -1,
        'post_status'    => array('publish', 'draft')
    );

    // Add search to arguments if search term exists
    if (!empty($search_term)) {
        $args['s'] = $search_term;
    }

    // Execute query
    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;
    ?>

    <!-- Search Box -->
    <form class="loopis-form" id="search-form" method="GET" action="/admin/" novalidate>
        <input type="hidden" name="view" value="storage">
            <input type="text" 
                   name="storage_search" 
                   value="<?php echo esc_attr($search_term); ?>" 
                   placeholder="🔍 Skriv sökord">
            <input type="submit" value="Sök">
            <?php if (!empty($search_term)) : ?>
                <button type="button" 
                        class="grey small" 
                        onclick="location.href='/admin/?view=storage'">
                    Rensa
                </button>
            <?php endif; ?>
    </form>

    <!-- List header -->
    <div class="columns">
        <div class="column1">↓ <?php echo $count; ?> sak<?php echo ($count != 1) ? 'er' : ''; ?></div>
        <div class="column2"></div>
    </div>
    <hr>

    <!-- Posts -->
    <div class="post-list">
        <?php if ($the_query->have_posts()) : ?>
            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                <?php get_template_part('templates/post-list/small-posts-author'); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php if (!empty($search_term)) : ?>
                <p>💢 Inga saker hittades för "<?php echo esc_html($search_term); ?>"</p>
            <?php else : ?>
                <p>💢 Inga saker i lager</p>
            <?php endif; ?>
        <?php endif; ?>
    </div><!--post-list-->

    <?php wp_reset_postdata(); ?>

<?php else : ?>
    <!-- No Access Message -->
    <?php include_once LOOPIS_THEME_DIR . '/templates/access/no-access.php'; ?>
<?php endif; ?>