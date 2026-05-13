<?php
/**
 * Post search for managers.
 * 
 * All posts filtered by location, category, and tag.
 * 
 * Improvements:
 * - Integrate CSS (simplified and reusable) with style.css
 */

if (!defined('ABSPATH')) {
    exit;
}

// ── Inputs ───────────────────────────────────────────────────────────────────
$selected_locations  = isset($_GET['location'])   ? array_map('sanitize_text_field', (array) $_GET['location']) : array();
$selected_categories = isset($_GET['categories']) ? array_map('intval', (array) $_GET['categories'])            : array();
$selected_tags       = isset($_GET['tags'])        ? array_map('intval', (array) $_GET['tags'])                 : array();
$search_term         = '';
if (isset($_GET['search'])) {
    $search_term = sanitize_text_field(wp_unslash($_GET['search']));
} elseif (isset($_GET['s'])) {
    // Backward compatibility for old links.
    $search_term = sanitize_text_field(wp_unslash($_GET['s']));
}
$paged               = isset($_GET['paged'])       ? max(1, absint($_GET['paged']))                             : 1;
$current_view        = trim(isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'special/custom-location', '/');
$clear_url           = add_query_arg(
    array('view' => $current_view),
    remove_query_arg(array('search', 's', 'location', 'categories', 'tags', 'paged', 'filter_submitted'))
);

$has_custom   = in_array('custom',   $selected_locations, true);
$has_bord     = in_array('bord',     $selected_locations, true);
$has_skapetet = in_array('skapetet', $selected_locations, true);

// ── Query ─────────────────────────────────────────────────────────────────────
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 50,
    'paged'          => $paged,
);

// Build a meta_query OR clause for each selected location type.
// No selection = no location filter (show all posts).
$location_clauses = array();
if ($has_skapetet) {
    $location_clauses[] = array('key' => 'location', 'value' => 'Skåpet',      'compare' => '=');
}
if ($has_bord) {
    $location_clauses[] = array('key' => 'location', 'value' => 'LOOPIS-bord', 'compare' => '=');
}
if ($has_custom) {
    $location_clauses[] = array('key' => 'location', 'value' => array('Skåpet', 'LOOPIS-bord'), 'compare' => 'NOT IN');
}
if (!empty($location_clauses)) {
    $args['meta_query'] = count($location_clauses) === 1
        ? $location_clauses
        : array_merge(array('relation' => 'OR'), $location_clauses);
}

if (!empty($selected_categories)) {
    $args['category__in'] = $selected_categories;
}

if (!empty($selected_tags)) {
    $args['tag__in'] = $selected_tags;
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

<h1>🔍 Alla annonser</h1>
<hr>
<p class="small">💡 Filtrera och/eller skriv sökord.</p>

<style>
    .post-search-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    .post-search-filters .search-row {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .post-search-filters .search-row input[type="text"] {
        min-width: 240px;
        flex: 0 1 360px;
    }
    .post-search-filters .search-row .clear-link {
        text-decoration: none;
    }
    .post-search-filters .filter-group {
        float: left; /* fallback */
        display: flex;
        flex-direction: column;
    }
    .post-search-filters .filter-group-submit {
        align-self: flex-end;
        padding-top: 20px;
    }
    .post-search-filters .group-label {
        display: block;
        font-weight: bold;
        margin-bottom: 4px;
        font-size: 13px;
    }
    .post-search-filters .select-hint {
        font-weight: normal;
        font-size: 11px;
    }
    /* Checkbox list styled to look like a <select multiple> */
    .filter-checklist {
        border: 1px solid #8c8f94;
        border-radius: 3px;
        min-width: 180px;
        max-width: 240px;
        min-height: 88px;
        max-height: 120px;
        overflow-y: auto;
        background: #fff;
        padding: 2px 0;
    }
    .post-search-filters .location-checklist {
        height: 120px;
    }
    .filter-checklist label {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        cursor: pointer;
        font-size: 13px;
        white-space: nowrap;
        user-select: none;
    }
    .filter-checklist label:hover {
        background: #f0f6fc;
    }
    .filter-checklist label input[type="checkbox"] {
        margin: 0;
        flex-shrink: 0;
    }
    /* Twemoji images inside the list labels */
    .filter-checklist label img.emoji {
        height: 1.1em;
        width: 1.1em;
        vertical-align: -0.15em;
    }
</style>

<!-- Filter form -->
<form method="GET" class="post-search-filters">
    <input type="hidden" name="view" value="<?php echo esc_attr($current_view); ?>">

    <!-- Location -->
    <div class="filter-group">
        <span class="group-label">Överlämning</span>
        <div class="filter-checklist location-checklist">
            <label>
                <input type="checkbox" name="location[]" value="skapetet" <?php checked($has_skapetet); ?>>
                Skåpet
            </label>
            <label>
                <input type="checkbox" name="location[]" value="bord" <?php checked($has_bord); ?>>
                LOOPIS-bord
            </label>
            <label>
                <input type="checkbox" name="location[]" value="custom" <?php checked($has_custom); ?>>
                Annan adress
            </label>
        </div>
    </div>

    <!-- Categories -->
    <?php if (!empty($all_categories)) : ?>
    <div class="filter-group">
        <span class="group-label">Kategorier</span>
        <div class="filter-checklist">
            <?php foreach ($all_categories as $cat) : ?>
                <label>
                    <input type="checkbox" name="categories[]" value="<?php echo esc_attr($cat->term_id); ?>"
                        <?php checked(in_array($cat->term_id, $selected_categories, true)); ?>>
                    <?php echo esc_html($cat->name); ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Tags -->
    <?php if (!empty($all_tags)) : ?>
    <div class="filter-group">
        <span class="group-label">Taggar</span>
        <div class="filter-checklist">
            <?php foreach ($all_tags as $tag) : ?>
                <label>
                    <input type="checkbox" name="tags[]" value="<?php echo esc_attr($tag->term_id); ?>"
                        <?php checked(in_array($tag->term_id, $selected_tags, true)); ?>>
                    #<?php echo esc_html($tag->name); ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="search-row">
        <input type="text" name="search" value="<?php echo esc_attr($search_term); ?>" placeholder="🔍 Skriv sökord">
        <button type="submit" class="green small">Sök / Filtrera</button>
        <a href="<?php echo esc_url($clear_url); ?>" class="small clear-link">Rensa</a>
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

<?php include_once get_template_directory() . '/templates/post-list/pagination.php'; ?>

<?php else : ?>
    <p>💢 Inga annonser</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
