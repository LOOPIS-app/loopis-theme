<?php
/**
* Compact output of the three latest posts in category 'complaint' 
**/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Arguments
$args = array(
    'post_type' => 'post',
    'category_name' => 'complaint',
    'posts_per_page' => 3,
);

// Query
$the_query = new WP_Query( $args );
$count = $the_query->found_posts; ?>

<!--Output-->
<div class="wrapped link" style="min-width:250px" onclick="location.href='<?php echo get_home_url( null, '/category/complaint/' ); ?>'">
<h5>💩 Reklamerade saker</h5>
<p class="small">💡 Saker som har reklamerats av mottagare.</p>
<p class="small">↓ 3 senaste<span class="right blue">Se alla →</span></p>
<hr>
<?php if ( $the_query->have_posts() ) : ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    <p class="small">🎁 <span class="bold"><?php echo esc_html(strip_emoji(get_the_title())); ?></span></p>
<?php endwhile; ?>

<?php else : ?>
    <p class="info">💩 Inga reklamationer.</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
</div>