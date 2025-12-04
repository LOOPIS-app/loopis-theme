<?php
/**
 * Search form with tag filter
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get current tag if on tag archive
$current_tag = '';
if (is_tag()) {
    $current_tag = get_queried_object()->slug;
} elseif (isset($_GET['tag']) && !empty($_GET['tag'])) {
    $current_tag = sanitize_text_field($_GET['tag']);
}
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
                // Count posts with this tag in available categories (1,37,57,147)
                $count_args = array(
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'cat'            => '1,37,57,147',
                    'tag_id'         => $tag->term_id,
                    'fields'         => 'ids',
                );
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
        <input type="submit" value="Sök">
    </form>
</div>