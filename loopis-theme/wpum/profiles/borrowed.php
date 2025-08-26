<?php
/**
 * Template for displaying the profile borrowed tab content, homemade by LOOPIS.
 *
 * @version 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user ID
$user_ID = wp_get_current_user()->ID;

// Get all bookings + count
$the_query = wpum_get_posts_for_profile( $user_ID, array('post_type' => 'booking',) );
$count = $the_query->found_posts;
?>

<p class="small">💡 Här visas alla saker du lånat.</p>
<h7>🔄 Saker lånade</h7>
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' bokning'; } else { echo ' bokningar'; } ?></div>
<div class="column2">Senaste överst</div></div>
<hr>

<div class="post-list">

<?php if( $the_query->have_posts() ): ?>

    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>

		<div class="post-list-post" onclick="location.href='<?php the_permalink(); ?>';">>
			<div class="post-list-post-thumbnail">
				<?php echo get_the_post_thumbnail(get_field('object'), 'thumbnail'); ?>
			</div>
				<div class="post-list-post-title">
					<?php the_title(); ?>
				</div>
				<div class="post-list-post-meta">
						<span><?php echo get_term(get_field('status'), 'booking-status')->name; ?></span>
						<span class="right"><i class="far fa-clock"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp'));?> sen</span>
				</div>
			</div>
				
    <?php endwhile; ?>

<!-- Check if pagination is needed-->
	<?php if ( $count > 50 ) : ?>
		<div id="post-pagination">
			<?php
				echo wp_kses_post( paginate_links( array(
					'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
					'total'        => $the_query->max_num_pages,
					'current'      => max( 1, get_query_var( 'paged' ) ),
					'format'       => '?paged=%#%',
					'show_all'     => false,
					'type'         => 'plain',
					'end_size'     => 2,
					'mid_size'     => 1,
					'prev_next'    => true,
					'prev_text'    => sprintf( '<i></i> %1$s', esc_html__( '<', 'wp-user-manager' ) ),
					'next_text'    => sprintf( '%1$s <i></i>', esc_html__( '>', 'wp-user-manager' ) ),
					'add_args'     => false,
					'add_fragment' => '',
				) ) );
			?>
		</div>
<?php endif; ?>
	
	<?php else : ?>

		<p>💢 Du har inte lånat något ännu.</p>
		<p>Ta en titt på <span class="link"><a href="../../category/borrow/">🗓 Saker att låna</a></span></p>

	<?php endif; ?>

<?php wp_reset_postdata(); ?>

</div><!--post-list-->