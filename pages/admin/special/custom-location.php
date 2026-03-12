<?php
/**
 * List of posts with location different from "Skåpet"
 * Filterable by categories.
 * Should be improved.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📍 Annonser med hämtning på annan plats</h1>
<hr>
<p class="small">💡 Annonser med hämtning på annan plats än "Skåpet".</p>

<?php
// Fetch selected categories from the URL query string (if any).
$selected_categories = isset($_GET['categories']) ? array_map('intval', $_GET['categories']) : array();

// Query arguments for fetching posts.
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'location',
            'value'   => 'Skåpet',
            'compare' => '!=',
        ),
    ),
);

// Exclude selected categories from the query.
if (!empty($selected_categories)) {
    $args['category__not_in'] = $selected_categories;
}

// Run the custom query.
$the_query = new WP_Query($args);

// Count the number of posts in the query.
$post_count = $the_query->found_posts;
?>

<!DOCTYPE html>
<html>
<head>
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
</head>
<body>
    <!-- Category filter form -->
    <form method="GET" class="category-filters">
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
        <button type="submit" class="small">Apply Filter</button>
    </form>

	<div class="columns"><div class="column1">↓ <?php echo $post_count; ?> annonser</div>
<div class="column2"></div></div>
<hr>
<div class="post-list">
	
    <!-- Post list -->
    <div id="post-list">
        <?php if ($the_query->have_posts()) : ?>
            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                <?php $post_id = get_the_ID(); ?>
                <div class="post-list-post-big" onclick="location.href='<?php the_permalink(); ?>';">
                    <div class="post-list-post-thumbnail-big"><?php the_post_thumbnail('thumbnail'); ?></div>
                    <div class="post-list-post-title-big"><?php the_title(); ?></div>
                    <div class="post-list-post-meta">
                        <p><?php the_category(' '); if (in_category('new')) { echo raffle_time(); } ?></p>
                        <p><i class="fas fa-walking"></i><?php echo get_post_meta($post_id, 'location', true); ?></p>
                        <p><i class="fas fa-hashtag"></i><?php the_tags(''); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>💢 Det finns inga sådana annonser</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</body>
</html>