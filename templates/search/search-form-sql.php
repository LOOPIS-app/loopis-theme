<?php
/**
 * Search form with tag filter for sql
 */

if (!defined('ABSPATH')) {
    exit;
}

// Categories
$available_cats = loopis_cats(['new', 'old', 'booked_custom', 'booked']);

// Get current tag if on tag archive
$current_tag = '';
if (is_tag()) {
    $current_tag = get_queried_object()->slug;
} elseif (isset($_GET['tag']) && !empty($_GET['tag'])) {
    $current_tag = sanitize_text_field(wp_unslash($_GET['tag']));
}

$allowed_category_ids = function_exists('loopis_get_search_category_ids')
    ? loopis_get_search_category_ids()
    : array();

$current_url = home_url(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$action = esc_url(add_query_arg($_GET, $current_url));
$search = isset($_GET['search'])? $_GET['search']:'';

global $wpdb;

$post_ids = get_query_var('search_postids');

if (empty($post_ids)) {
    $post_ids = [0];
}

$id_list = implode(',', array_map('intval', $post_ids));

$results = $wpdb->get_results("
    SELECT tt.term_id, t.name, t.slug, COUNT(*) as count
    FROM {$wpdb->term_relationships} tr
    INNER JOIN {$wpdb->term_taxonomy} tt
        ON tr.term_taxonomy_id = tt.term_taxonomy_id
    INNER JOIN {$wpdb->terms} t
        ON t.term_id = tt.term_id
    WHERE tr.object_id IN ($id_list)
    AND tt.taxonomy = 'post_tag'
    GROUP BY tt.term_id
");
$tag_counts = [];

foreach ($results as $row) {
    $tag_counts[$row->term_id] = $row->count;
}

?>
<div class="searchandfilter">
    <form method="get" action="<?php echo $action; ?>">
        <!-- Search input -->
        <input type="text" 
               name="search" 
               value="<?php echo $search;?>" 
               placeholder="🔍 Skriv sökord">

        <?php
        foreach ($_GET as $key => $value){
            if (!in_array($key, ['tag','pagenum','search'])){
                echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
            }
        }
        ?>
        <!-- Tag/Category filter -->
        <select name="tag">
            <option value="">Alla kategorier</option>
            <?php
            $tags = get_tags(array(
                'orderby' => 'name',
                'order'   => 'ASC',
                'hide_empty' => true
            ));
            
            foreach ($tags as $tag) {
            
                $available_count = $tag_counts[$tag->term_id] ?? 0;
                $selected = ($current_tag === $tag->slug) ? 'selected' : '';
            
                echo '<option value="' . esc_attr($tag->slug) . '" ' . $selected . '>';
                echo esc_html($tag->name). '(' .$available_count.')';
                echo '</option>';
            }
            ?>
        </select>
        
        <!-- Submit button -->
        <input type="submit" class="green small" value="Sök">
    </form>
</div>