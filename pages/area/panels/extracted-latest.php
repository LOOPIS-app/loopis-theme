<?php
/**
* Compact output of the three latest posts in category 'extracted' 
**/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Arguments
$args = array(
    'post_type' => 'post',
    'category_name' => 'extracted',
    'posts_per_page' => 3,
);

// Query
$the_query = new WP_Query( $args );
$count = $the_query->found_posts; ?>

<!--Output-->
<p class="small">↓ 3 senaste<span class="right blue">Alla →</span></p>
<hr>
<?php if ( $the_query->have_posts() ) : ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    <p class="small">🎁 <span class="bold"><?php echo esc_html(strip_emoji(get_the_title())); ?></span></p>
<?php endwhile; ?>

<?php else : ?>
    <p class="info">💢 Det finns inga.</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
