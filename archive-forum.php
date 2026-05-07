<?php
/**
 * Archive for custom post type 'forum' reached on URL /forum
 * 
 * IMPROVEMENTS:
 * - Use pagination template
 * - Add filtering by category
 * - Add search function
 * – Change name of CPT to "news"?
 */

get_header(); ?>

<div class="content">
	<div class="page-padding">

<h1>📡 Nyheter</h1>						
<hr>
<p class="small">💡 Här hittar du de senaste nyheterna från föreningen</p>

<?php
// Arguments
$args = array(
    'post_type' => 'forum',
    'posts_per_page' => 50,
    'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1, 
);

// Query
$the_query = new WP_Query( $args );
$count = $the_query->found_posts; ?>

<!--Output-->
<div class="columns"><div class="column1">↓ <?php echo $count; ?> nyheter</div>
<div class="column2 small">💡 Senast överst</div></div>
<hr>
<div class="post-list">

<!--Post loop-->
<?php if ( $the_query->have_posts() ) : ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    <div class="post-list-forum" onclick="location.href='<?php echo the_permalink(); ?>';">
        <div class="post-list-forum-content">
            <div class="post-list-forum-title"><?php the_title(); ?></div>
            <div class="post-list-forum-excerpt"><?php echo get_the_excerpt(); ?></div>
        </div>
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-list-forum-thumbnail">
                <?php the_post_thumbnail('thumbnail'); // Display the square thumbnail ?>
            </div>
        <?php endif; ?>
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
            'prev_text'    => '<',
            'next_text'    => '>',
            'add_args'     => false,
            'add_fragment' => '',
        ) ) );
        ?>
    </div><!--/.post-pagination-->
<?php endif; ?>

<?php else : ?>
    <p>💢 Det finns inga nyheter.</p>
<?php endif; ?>

</div><!--post-list-->

<?php wp_reset_postdata(); ?>

</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>