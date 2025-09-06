<?php
/**
 * The main template file.
 *
 * This is the fallback template in WordPress.
 * It is used to display content when no specific template matches a query.
 *
 */
?>

<?php get_header(); ?>

<div class="content">
	<div class="page-padding">


<h1>🎁 Saker att få</h1>						

<?php
$count_new_args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'cat' => 1,
);

$count_new_query = new WP_Query( $count_new_args );
$count_new = $count_new_query->found_posts;

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 50,
    'cat'   		 => '1,37,57,147',
    'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1, 
);

$the_query = new WP_Query( $args );
$count_total = $the_query->found_posts;
$count_old = $count_total - $count_new;
 ?>

<div class="columns"><div class="column1">↓ <?php echo $count_new; ?> nya och <?php echo $count_old; ?> tidigare</div>
<div class="column2"><a href="/things-random/">🤹 Fyndhörnan →</a></div></div>
<hr>
<div class="post-list">

<?php if ( $the_query->have_posts() ) : ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	<div class="post-list-post-big" onclick="location.href='<?php the_permalink(); ?>';">
		<div class="post-list-post-thumbnail-big"><?php the_post_thumbnail('thumbnail'); ?></div>
		<div class="post-list-post-title-big"><?php the_title(); ?></div>
		<div class="post-list-post-meta">
			<p><?php the_category(' '); if (in_category( 'new' )) { echo raffle_time(); } ?></p>
			<p><i class="fas fa-walking"></i><?php echo get_field('location'); ?></p>
			<p><i class="fas fa-hashtag"></i><?php the_tags(''); ?></p>		
		</div>
	</div>
	<?php endwhile; ?>

<?php if ( $the_query->max_num_pages > 1 ) : ?>
    <div id="post-pagination">
        <?php
        echo wp_kses_post( paginate_links( array(
            'base'         => trailingslashit(home_url('/gifts/page/%#%/')),
            'total'        => $the_query->max_num_pages,
            'current'      => max( 1, $paged ),
            'format'       => '%#%',
            'show_all'     => false,
            'type'         => 'plain',
            'end_size'     => 2,
            'mid_size'     => 2,
            'prev_next'    => true,
            'prev_text'    => sprintf( '<i></i> %1$s', esc_html__( '<', 'wp-user-manager' ) ),
            'next_text'    => sprintf( '%1$s <i></i>', esc_html__( '>', 'wp-user-manager' ) ),
            'add_args'     => false,
            'add_fragment' => '',
        ) ) );
        ?>
    </div><!--/.post-pagination-->
<?php endif; ?>
<?php else : ?>
    <p>💢 Det finns inga aktuella annonser</p>
<?php endif; ?>

</div><!--post-list-->

<?php wp_reset_postdata(); ?>

</div><!--page-padding-->
</div><!--content-->

<?php
// Add to homescreen
if ( is_user_logged_in() ) { echo do_shortcode( '[code_snippet id=160 php]' ); } ?>

<?php get_footer(); ?>