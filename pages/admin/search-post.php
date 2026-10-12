<?php
/**
 * Post search.
 * 
 * All posts filtered by location, category, and tag.
 */

if (!defined('ABSPATH')) {
    exit;
}

// ── Inputs ───────────────────────────────────────────────────────────────────
$get_filter_value = static function ($key) {
    if (!isset($_GET[$key])) {
        return '';
    }

    $value = wp_unslash($_GET[$key]);
    if (is_array($value)) {
        $value = reset($value);
    }

    return is_scalar($value) ? sanitize_text_field((string) $value) : '';
};

$location_options = array(
    'skapetet' => 'Skåpet',
    'bord'     => 'LOOPIS-bord',
    'custom'   => 'Annan adress',
);
$selected_location  = $get_filter_value('location');
$selected_category  = absint($get_filter_value('categories'));
$selected_tag       = absint($get_filter_value('tags'));
$search_term         = '';
if (isset($_GET['search'])) {
    $search_term = sanitize_text_field(wp_unslash($_GET['search']));
} elseif (isset($_GET['s'])) {
    // Backward compatibility for old links.
    $search_term = sanitize_text_field(wp_unslash($_GET['s']));
}
$paged               = max(
    1,
    absint(get_query_var('paged')),
    isset($_GET['paged']) ? absint(wp_unslash($_GET['paged'])) : 0
);
$current_view        = trim(isset($_GET['view']) ? sanitize_text_field(wp_unslash($_GET['view'])) : 'search-post', '/');
$clear_url           = add_query_arg(
    array('view' => $current_view),
    remove_query_arg(array('search', 's', 'location', 'categories', 'tags', 'paged', 'filter_submitted'))
);

$selected_location = isset($location_options[$selected_location]) ? $selected_location : '';

// ── Query ─────────────────────────────────────────────────────────────────────
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 50,
    'paged'          => $paged,
);

if ($selected_location !== '') {
    if ($selected_location === 'custom') {
        $args['meta_query'] = array(
            array(
                'key'     => 'location',
                'value'   => array('Skåpet', 'LOOPIS-bord'),
                'compare' => 'NOT IN',
            ),
        );
    } else {
        $args['meta_query'] = array(
            array(
                'key'     => 'location',
                'value'   => $location_options[$selected_location],
                'compare' => '=',
            ),
        );
    }
}

if ($selected_category > 0) {
    $args['category__in'] = array($selected_category);
}

if ($selected_tag > 0) {
    $args['tag__in'] = array($selected_tag);
}

if ($search_term !== '') {
    $args['s'] = $search_term;
}

$the_query  = new WP_Query($args);
$post_count = $the_query->found_posts;

// ── Data for filter UI ───────────────────────────────────────────────────────
$all_categories = get_categories(array('hide_empty' => false));
$all_tags       = get_tags(array('hide_empty' => false));
?>

<h1>🔍 Sök annons</h1>
<hr>
<p class="small">💡 Använd filter och/eller sökord.</p>

<!-- Filter form -->
<form method="GET" class="loopis-form form-blocks">
    <input type="hidden" name="view" value="<?php echo esc_attr($current_view); ?>">

    <!-- Search -->
    <div class="form-block">
        <input type="text" id="post-search-term" name="search" value="<?php echo esc_attr($search_term); ?>" placeholder="🔍 Sökord">
    </div>

    <!-- Categories -->
    <?php if (!empty($all_categories)) : ?>
        <div class="form-block">
            <select name="categories" id="post-search-category">
                <option value="">Alla statusar</option>
                <?php foreach ($all_categories as $cat) : ?>
                    <option value="<?php echo esc_attr($cat->term_id); ?>" <?php selected($selected_category, $cat->term_id); ?>>
                        <?php echo esc_html($cat->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>

    <!-- Tags -->
    <?php if (!empty($all_tags)) : ?>
        <div class="form-block">
            <select name="tags" id="post-search-tag">
                <option value="">Alla kategorier</option>
                <?php foreach ($all_tags as $tag) : ?>
                    <option value="<?php echo esc_attr($tag->term_id); ?>" <?php selected($selected_tag, $tag->term_id); ?>>
                        #<?php echo esc_html($tag->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>

    <!-- Location -->
    <div class="form-block">
        <select name="location" id="post-search-location">
            <option value="">Alla platser</option>
            <?php foreach ($location_options as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($selected_location, $value); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-block-actions">
        <button type="submit" class="green small">Sök</button>
        <a href="<?php echo esc_url($clear_url); ?>" class="small"><i class="fas fa-times"></i> Rensa</a>
    </div>

</form>

<div class="columns"><div class="column1">↓ <?php echo $post_count; ?> annonser</div>
<div class="column2 small">💡 Senaste överst</div></div>
<hr>

<!-- Post output -->
<div class="post-list">
    <?php if ($the_query->have_posts()) : ?>
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <?php get_template_part('templates/post-list/big-posts'); ?>
        <?php endwhile; ?>
</div><!--post-list-->

        <?php 
            set_query_var('custom_query', $the_query);
            include_once get_template_directory() . '/templates/post-list/pagination-views.php'; 
        ?>

<?php else : ?>
    <p>💢 Inga annonser</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
