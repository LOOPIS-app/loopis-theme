<?php
/**
 * Storage page
 * Shows all items in storage category
 * Includes search functionality for finding specific items
 * Access restricted to users with loopis_storage_book capability
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📦 Lager</h1>
<hr>
<p class="small">💡 Här ser du alla saker i kategorierna <span class="small-label">📦 Lager</span> - och ibland <span class="small-label">📌 Tips</span>.</p>

<?php if (current_user_can('loopis_storage_book')) : ?>

    <?php
    // Get search term
    $search_term = isset($_GET['storage_search']) ? sanitize_text_field($_GET['storage_search']) : '';

    // Query arguments
    $args = array(
        'post_type'      => 'post',
        'cat'            => '157',  // storage 157 + tips 155
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
    <form method="GET" action="/admin/" class="searchandfilter">
        <input type="hidden" name="view" value="storage">
        <div style="white-space: nowrap;">
            <input type="text" 
                   name="storage_search" 
                   value="<?php echo esc_attr($search_term); ?>" 
                   placeholder="🔍 Skriv sökord"
                   style="display: inline-block;">
            <input type="submit" value="Sök">
            <?php if (!empty($search_term)) : ?>
                <button type="button" 
                        class="grey small" 
                        onclick="location.href='/admin/?view=storage'">
                    Rensa
                </button>
            <?php endif; ?>
        </div>
    </form>

    <!-- Items List -->
    <h7>🎁 Saker att få</h7>
    <div class="columns">
        <div class="column1">
            ↓ <?php echo $count; ?> sak<?php echo ($count != 1) ? 'er' : ''; ?>
        </div>
        <div class="column2"></div>
    </div>
    <hr>

    <div class="post-list">
        <?php if ($the_query->have_posts()) : ?>
            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                <div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
                    <div class="post-list-post-thumbnail">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </div>
                    <div class="post-list-post-title">
                        <?php the_title(); ?>
                    </div>
                    <div class="post-list-post-meta">
                        <span>👤 <?php the_author_posts_link(); ?></span>
                        <span class="right">
                            <i class="fas fa-arrow-alt-circle-up"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> sen
                        </span>
                    </div>
                </div><!--post-list-post-->
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
    <?php echo do_shortcode('[code_snippet id=124 php]'); ?>
<?php endif; ?>