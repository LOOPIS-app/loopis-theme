<?php
/**
 * Archive for custom post type 'news' reached on URL /news
 * 
 * IMPROVEMENTS:
 * - Use pagination template
 * - Add filtering by category
 */

get_header(); ?>

<div class="page-padding">

<h1>📡 Nyheter</h1>
<hr>
<p class="small">💡 Nyheter i ditt område.</p>

<?php get_template_part('templates/forms/search-form-news'); ?>

<?php
// Arguments for archive search/filter within this CPT only
$args = array(
    'post_type' => 'news',
    'posts_per_page' => 50,
    'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
);

$news_search = ! empty( $_GET['news-search'] ) ? sanitize_text_field( wp_unslash( $_GET['news-search'] ) ) : '';
if ( $news_search !== '' ) {
    $args['s'] = $news_search;
}

if ( ! empty( $_GET['news-category'] ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'news-category',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( wp_unslash( $_GET['news-category'] ) ),
        ),
    );
}

// Query
$the_query = new WP_Query( $args );
$count = $the_query->found_posts;
?>

<!--Output-->
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' nyhet'; } else { echo ' nyheter'; } ?>
</div><div class="column2 small">💡 Senaste överst</div></div>
<hr>
<div class="post-list">

<!--Post loop-->
<?php if( $the_query->have_posts() ): ?>
    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
		<?php $post_id = get_the_ID(); ?>
			<div class="post-list-cpt" onclick="location.href='<?php the_permalink(); ?>';">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-list-cpt-thumbnail">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </div>
                <?php endif; ?>
                <div class="post-list-cpt-title"><?php echo esc_html(strip_emoji(get_the_title())); ?></div>
                <div class="post-list-cpt-excerpt"><?php echo esc_html(strip_emoji(get_the_excerpt())); ?></div>
                <div class="post-list-cpt-meta">
					<?php
					$news_terms = get_the_terms($post_id, 'news-category');
					$news_category_name = (!empty($news_terms) && !is_wp_error($news_terms)) ? $news_terms[0]->name : '💢 Ingen kategori';
					?>
					<span><?php echo esc_html($news_category_name); ?></span>
					<span><i class="far fa-clock"></i><?php echo human_time_diff(get_the_time('U'), current_time('timestamp'));?> sen</span>
                    <span><i class="far fa-comment"></i><?php echo get_comments_number(); ?></span>
                    <!--span>👤 echo get_the_author_posts_link(); </span-->
				</div>
			</div>
    <?php endwhile; ?>


<?php if ( $the_query->max_num_pages > 1 ) : ?>
    <div id="post-pagination">
        <?php
        echo wp_kses_post( paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $the_query->max_num_pages,
            'current'      => max( 1, get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 ),
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

<?php get_footer(); ?>