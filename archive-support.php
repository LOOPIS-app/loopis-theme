<?php
/**
 * Archive for custom post type 'support' reached on URL /support
 * 
 * IMPROVEMENTS:
 * - Use pagination template?
 * - Add filtering by category
 * - Add form for creating new forum threads
 */

get_header(); ?>


<div class="page-padding">

<h1>🛟 Supportforum</h1>
<hr>
<p class="small">💡 Supportfrågor i ditt område.</p>

<!-- Access check-->
<?php if ( current_user_can('member') || current_user_can('manage_options') ) { ?>

<p>Här kan du få support av admin och andra medlemmar: <button type="button" class="orange small" onclick="window.location.href='<?php echo esc_url(add_query_arg('view', 'create-support-post', home_url('/area/'))); ?>'">Skapa tråd</button></p>
<p>Innan du skapar en ny tråd, sök bland de som finns:</p>
<?php get_template_part('templates/forms/search-form-support'); ?>

<?php
// Arguments for archive search/filter within this CPT only
$paged = ( get_query_var( 'paged' ) ) ? (int) get_query_var( 'paged' ) : 1;

$args = array(
    'post_type' => 'support',
    'posts_per_page' => 50,
    'paged' => $paged,
);

$forum_search = ! empty( $_GET['forum-search'] ) ? sanitize_text_field( wp_unslash( $_GET['forum-search'] ) ) : '';
if ( $forum_search !== '' ) {
    $args['s'] = $forum_search;
}

if ( ! empty( $_GET['forum-category'] ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'support-category',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( wp_unslash( $_GET['forum-category'] ) ),
        ),
    );
}

// Query
$the_query = new WP_Query( $args );
$count = 0;

// Count visible posts across all pages (not only current page).
$count_args = $args;
$count_args['posts_per_page'] = -1;
$count_args['paged'] = 1;
$count_args['fields'] = 'ids';
$count_args['no_found_rows'] = true;

$count_query = new WP_Query( $count_args );

if ( ! empty( $count_query->posts ) ) {
    $current_id = (int) get_current_user_id();
    $can_view_private = current_user_can( 'loopis_support' ) || current_user_can( 'manage_options' );

    foreach ( $count_query->posts as $support_post_id ) {
        $post_id = (int) $support_post_id;
        $author_id = (int) get_post_field( 'post_author', $post_id );
        $is_private_support = has_term( 'private', 'support-category', $post_id );

        if ( ! $is_private_support || $can_view_private || $current_id === $author_id ) {
            $count++;
        }
    }
}
?>

<!--Output-->
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' tråd'; } else { echo ' trådar'; } ?>
</div><div class="column2 small">💡 Senaste överst</div></div>
<hr>
<div class="post-list">

<!--Post loop-->
<?php if( $the_query->have_posts() ): ?>
    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
     <?php get_template_part('templates/post-list/support-posts'); ?>
    <?php endwhile; ?>

<?php if ( $count > 0 && $the_query->max_num_pages > 1 ) : ?>
    <div id="post-pagination">
        <?php
        echo wp_kses_post( paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                    'total'        => $the_query->max_num_pages,
                    'current'      => max(1, $paged),
                    'format'       => '%#%',
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
		<p>💢 Det finns inga supporttrådar.</p>
	<?php endif; ?>

</div>

<?php wp_reset_postdata(); ?>


<?php } else { 
    echo "<h3>🛠 Work in progress!</h3>";
    include LOOPIS_THEME_DIR . '/includes/output/access/only-user.php';
} ?>

</div><!--page-padding-->

<?php get_footer(); ?>