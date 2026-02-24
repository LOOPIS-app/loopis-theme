<?php
/**
 * List posts with location different from "Skåpet", with category filter.
 * 
 * Used for communication/statistics.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📍 Annan adress</h1>
<hr>
<p class="small">💡 Annonser med hämtning på annan plats än "Skåpet".</p>

<?php
// Fetch selected categories from the URL query string (if any).
$selected_categories = isset($_GET['categories']) ? array_map('intval', $_GET['categories']) : array();
$paged = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;

// Query arguments for fetching posts.
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 50,
    'paged'          => $paged,
    'meta_query'     => array(
        array(
            'key'     => 'location',
            'value'   => 'Skåpet',
            'compare' => '!=',
        ),
    ),
);

// Include selected categories in the query.
if (!empty($selected_categories)) {
    $args['category__in'] = $selected_categories;
}

// Run the custom query.
$the_query = new WP_Query($args);

// Count the number of posts in the query.
$post_count = $the_query->found_posts;
?>

<style>
    .category-filters {
        margin-bottom: 20px;
    }
    .category-filters label {
        margin-right: 10px;
        font-size: 14px;
    }
    .filter-button {
        display: inline-block;
        margin-top: 10px;
        padding: 5px 10px;
        background-color: #0073aa;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }
    .filter-button:hover {
        background-color: #005177;
    }
    .post-count {
        margin-bottom: 20px;
        font-size: 16px;
        font-weight: bold;
    }
</style>

<!-- Category filter form -->
<form method="GET" class="category-filters">
    <input type="hidden" name="view" value="<?php echo esc_attr(trim(isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'special/custom-location', '/')); ?>">
    <?php
    // Get all available categories.
    $categories = get_categories();
    foreach ($categories as $category) {
        ?>
        <label>
            <input type="checkbox" name="categories[]" value="<?php echo esc_attr($category->term_id); ?>"
                <?php if (in_array($category->term_id, $selected_categories)) echo 'checked'; ?>>
            <?php echo esc_html($category->name); ?>
        </label>
        <?php
    }
    ?>
    <br>
    <button type="submit" class="small">Filtrera</button>
</form>

<div class="columns"><div class="column1">↓ <?php echo $post_count; ?> annonser</div>
<div class="column2"></div></div>
<hr>

<!-- Post output -->
<div class="post-list">
    <?php if ($the_query->have_posts()) : ?>
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <?php get_template_part('templates/post-list/big-posts'); ?>
        <?php endwhile; ?>
</div><!--post-list-->

<?php include_once get_template_directory() . '/templates/post-list/pagination.php'; ?>

<?php else : ?>
    <p>💢 Inga annonser</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>