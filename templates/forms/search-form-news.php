<?php
/**
 * Search form for CPT 'news'.
 */

if (!defined('ABSPATH')) {
    exit;
}

$terms = get_terms(array(
    'taxonomy' => 'news-category',
    'hide_empty' => true,
));
?>

<div>
    <form class="loopis-form" id="search-form" method="get" action="<?php echo esc_url(get_post_type_archive_link('news')); ?>">
        <input type="hidden" name="post_type" value="news">

        <input type="text"
               name="news-search"
               value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_GET['news-search'] ?? '' ) ) ); ?>"
               placeholder="🔍 Skriv sökord">

        <?php if (!is_wp_error($terms) && !empty($terms)) : ?>
            <select name="news-category">
                <option value=""><?php echo esc_html__('Alla kategorier', 'loopis'); ?></option>
                <?php foreach ($terms as $term) : ?>
                    <option value="<?php echo esc_attr($term->slug); ?>" <?php selected(get_query_var('news-category'), $term->slug); ?>>
                        <?php echo esc_html($term->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <input type="submit" class="green small" value="Sök">
    </form>
</div>
