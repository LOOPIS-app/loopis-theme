<?php
/**
 * Function for fetching and displaying posts relevant to special pages.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */


/**
 *  Shortcode: [loopis_get_posts]
 * 
 *  Displays an array of random posts from specified tags
 * 
 * Attributes:
 *  - posts (int): Number of posts to display. Default: 3
 *  - tags  (string): Comma-separated tag slugs. Default: None
 * 
 * @return string HTML output
 */
add_shortcode( 'loopis_get_posts', function ($atts) {

    $atts = shortcode_atts(
        array(
            'posts' => 3,
            'tags'  => '',
        ),
        $atts
    );

	ob_start();
	?>

	<?php
	// Get 3 random available posts with specified tags
	$args = array(
	    'post_type'      => 'post',
	    'posts_per_page' => $atts['posts'],
	    'orderby'        => 'rand',
	    'category__in'   => loopis_cats(['new','old']),
	    'tag_slug__in'   => $atts['tags'] ? explode(',', $atts['tags']) : [], // Specify tags here
	);
	
	$the_query = new WP_Query($args);
	?>
	
	<div class="columns">
	    <div class="column1">↓ <?php echo $the_query->post_count ?> tips</div>
	    <div class="column2"></div>
	</div>
	<hr>
	
	<div class="post-list">
	    <?php if ($the_query->have_posts()) : ?>
	        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
	            <div class="post-list-post" style="background: #ffffff" onclick="location.href='<?php the_permalink(); ?>';">
	                <div class="post-list-post-thumbnail">
	                    <?php the_post_thumbnail('thumbnail'); ?>
	                </div>
	                <div class="post-list-post-title">
	                    <?php the_title(); ?>
	                </div>
	                <div class="post-list-post-meta">
	                    <p><i class="fas fa-hashtag"></i><?php the_tags(''); ?></p>
	                </div>
	            </div><!--post-list-post-->
	        <?php endwhile; ?>
	    <?php else : ?>
	        <p>💢 Inga annonser hittades.</p>
	    <?php endif; ?>
	</div><!--post-list-->
	
	<?php wp_reset_postdata(); ?>

	<?php
	return ob_get_clean();
} );
