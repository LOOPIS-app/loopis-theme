<div id="post-pagination">
	<?php
		echo wp_kses_post( paginate_links( array(
			'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'total'        => $GLOBALS['wp_query']->max_num_pages,
			'current'      => max( 1, get_query_var( 'paged' ) ),
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
