<?php
/**
 * Template for displaying LOOPIS user tab content.
 * 
 * Not yet used, because we need to decide if/how we show areas on sub sites.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current user ID
$user_id = get_current_user_id();
$user = wp_get_current_user();
?>

<h3>📍 Mina områden</h3>
<hr>
<p class="small">💡 Områden där du är medlem.</p>

<?php
        wp_reset_postdata();        
        // See what subsites the user has access to and fetch matching area posts by slug using this mapping:
        $site_path_to_post_slug = array(
            '/12845/' => 'bagarmossen',
			'/12833/' => 'skarpnack',
			// Add more mappings as needed
        );

        $allowed_post_slugs = array();
        $user_blogs = get_blogs_of_user( $user_id );

        foreach ( $user_blogs as $user_blog ) {
            if ( isset( $site_path_to_post_slug[ $user_blog->path ] ) ) {
                $allowed_post_slugs[] = sanitize_title( $site_path_to_post_slug[ $user_blog->path ] );
            }
        }

        $allowed_post_slugs = array_values( array_unique( $allowed_post_slugs ) );

        if ( empty( $allowed_post_slugs ) ) {
            $allowed_post_slugs = array( '__no_matching_area__' );
        }

        $args = array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 50,
            'orderby'        => 'post_name__in',
            'post_name__in'  => $allowed_post_slugs,
        );

        $the_query = new WP_Query( $args );
        $count_total = $the_query->found_posts;
        ?>

        <!-- List header -->
        <div class="columns">
            <div class="column1">↓ <?php echo $count_total; ?> områden</div>
            <div class="column2"></div>
        </div>
        <hr>

        <!-- Posts output -->
        <div class="post-list">
            <?php if ( $the_query->have_posts() ) { ?>
                <?php while ( $the_query->have_posts() ) { $the_query->the_post(); ?>
                    <?php get_template_part( 'templates/post-list/area-posts' ); ?>
                <?php } ?>
            <?php } else { ?>
			<p>💢 Du är inte medlem i något område.</p>
            <?php } ?>
        </div><!--post-list-->

        <?php wp_reset_postdata(); ?>