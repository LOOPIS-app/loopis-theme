<?php
/* Template Name: Gifts Template */
?>

<?php get_header(); ?>

<div class="content">
	<div class="page-padding">

<h1>🎁 Saker att få</h1>						

<div>
<?php echo do_shortcode( '[searchandfilter fields="search,post_tag" show_count=1 search_placeholder="🔍 Skriv sökord" submit_label="Sök"  all_items_labels=",Kategori"]' ); ?>
</div>

<?php
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 50,
    'cat'   		 => '1,37,57,147',
    'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1, 
);

$the_query = new WP_Query( $args );
$count = $the_query->found_posts; ?>

<div class="columns"><div class="column1">↓ <?php echo $count; ?> aktuella annonser</div>
<div class="column2 small bottom">(Senast överst)</div></div>
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
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $the_query->max_num_pages,
            'current'      => max( 1, $paged ),
            'format'       => '?paged=%#%',
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

<?php get_footer(); ?>