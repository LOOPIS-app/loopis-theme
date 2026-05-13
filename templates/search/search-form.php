<?php
/**
 * Search form with tag filter
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
?>

<div class="searchandfilter">
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <!-- Search input -->
        <input type="text" 
               name="s" 
               value="<?php echo get_search_query(); ?>" 
               placeholder="🔍 Skriv sökord">
        
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
                // Count posts with this tag in available categories 'new', 'old', 'booked_custom', 'booked'
                $count_args = array(
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'cat'            => $available_cats,
                    'tag_id'         => $tag->term_id,
                    'fields'         => 'ids',
                );

                if (!empty($allowed_category_ids)) {
                    $count_args['category__in'] = $allowed_category_ids;
                }

                $count_query = new WP_Query($count_args);
                $available_count = $count_query->found_posts;
                wp_reset_postdata();
                
                $selected = ($current_tag === $tag->slug) ? 'selected' : '';
                echo '<option value="' . esc_attr($tag->slug) . '" ' . $selected . '>';
                echo esc_html($tag->name) . ' (' . $available_count . ')';
                echo '</option>';
            }
            ?>
        </select>
        
        <!-- Submit button -->
        <input type="submit" class="green small" value="Sök">
    </form>
</div>