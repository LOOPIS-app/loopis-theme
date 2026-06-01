<?php
/**
 * Archive for custom post type 'support' reached on URL /support
 * 
 * IMPROVEMENTS:
 * – Unique post list styling
 * - Use pagination template
 * - Add filtering by category (active/inactive)
 * - Add search function
 * – Add list of current admins
 */

get_header(); ?>

<div class="content">
	<div class="page-padding">

<h1>🛟 Support</h1>
<hr>
<p class="small">💡 Här visas alla supportfrågor.</p>

<!-- Access check -->
<?php if (current_user_can('loopis_support')) { ?>
    
<p>Alla medlemmar kan delta i LOOPIS support! Om du har ett bra svar; skriv en kommentar.<br>
Admin hjälper till att svara + markerar frågor som besvarade.</p>

<?php
// Arguments
$args = array(
    'post_type' => 'support',
    'posts_per_page' => 50,
    'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1, 
);

// Query
$the_query = new WP_Query( $args );
$count = $the_query->found_posts; 
?>

<!--Output-->
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' ärende'; } else { echo ' ärenden'; } ?>
</div><div class="column2 small">💡 Senaste överst</div></div>
<hr>
<div class="post-list">

<!--Post loop-->
<?php if( $the_query->have_posts() ): ?>
    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
		<?php $post_id = get_the_ID(); ?>
			<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">
				<div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>
                <div class="post-list-post-title">
                    <span class="rounded">🛟 <?php echo get_post_field('post_name', $post_id); ?></span>
					<?php echo esc_html( get_the_title() ); ?>
				</div>
				<div class="post-list-post-meta">
					<span><?php echo esc_html(get_the_terms($post_id, 'support-category')[0]->name); ?></span>
					<span><i class="far fa-clock"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp'));?> sen</span>
                    <span><i class="far fa-comment"></i><?php echo get_comments_number(); ?></span>
                    <span class="right">👤 <?php echo get_the_author_posts_link(); ?></span>
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
		<p>💢 Inga pågående support-ärenden.</p>
	<?php endif; ?>

</div>

<?php wp_reset_postdata(); ?>


<?php } else { 
include LOOPIS_THEME_DIR . '/templates/access/no-access.php';
 } ?>

</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>