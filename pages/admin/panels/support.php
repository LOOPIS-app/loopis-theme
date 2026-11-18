<?php
/**
 * List active support posts
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Arguments
$args = array(
    'post_type' => 'support',
    'tax_query'      => array(
        array(
            'taxonomy' => 'support-category',
            'field'    => 'slug',
            'terms'    => 'active'
        )
    )
);

// Query
$the_query = new WP_Query( $args );
?>

<!--Output-->
<?php if ( $the_query->have_posts() ) : ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    <p class="shorten">🔴 <?php echo esc_html(strip_emoji(get_the_title())); ?></p>
<?php endwhile; ?>

<?php else : ?>
    <p>✅ Inga aktiva supporttrådar.</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>