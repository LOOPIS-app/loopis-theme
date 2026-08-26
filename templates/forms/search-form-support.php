<?php
/**
 * Search form for CPT 'support'.
 */

if (!defined('ABSPATH')) {
    exit;
}

$terms = get_terms(array(
    'taxonomy' => 'support-category',
    'hide_empty' => true,
));
?>

<div>
    <form class="loopis-form" id="search-form" method="get" action="<?php echo esc_url(get_post_type_archive_link('support')); ?>">
        <input type="hidden" name="post_type" value="support">

        <input type="text"
               name="forum-search"
               value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_GET['forum-search'] ?? '' ) ) ); ?>"
               placeholder="🔍 Skriv sökord">

        <?php if (!is_wp_error($terms) && !empty($terms)) : ?>
            <select name="support-category">
                <option value=""><?php echo esc_html__('Alla kategorier', 'loopis'); ?></option>
                <?php foreach ($terms as $term) : ?>
                    <option value="<?php echo esc_attr($term->slug); ?>" <?php selected(get_query_var('support-category'), $term->slug); ?>>
                        <?php echo esc_html($term->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>

        <input type="submit" class="green small" value="Sök">
    </form>
</div>
