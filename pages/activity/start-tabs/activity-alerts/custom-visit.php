<?php
/**
 * Activity page alert for member.
 *
 * Included in activity-alerts.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// query
$count = $the_query->post_count;

// Only query full posts if we have results
if ( $count > 0 ) {
    // Now get the actual posts with thumbnails
    $args['fields'] = ''; // Get full post objects
    $args['update_post_meta_cache'] = true; // We need meta for thumbnails
    $the_query = new WP_Query( $args );
} else {
    $the_query = $count_query;
}

// Output
if( $the_query->have_posts() ): ?>
<h7>📍 Dags att hämta!</h7>
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' sak'; } else { echo ' saker'; } ?>
</div><div class="column2">
</div></div>
<hr>
    <div class="post-list">
    <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
        <div class="post-list-post notif" style="position:relative;" onclick="location.href='<?php the_permalink(); ?>';">
            <div class="post-list-post-thumbnail">
                <?php 
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail('thumbnail');
                }
                ?>
            </div>
            <div class="post-list-post-title">
                <?php the_title(); ?>
                <?php $post_id = get_the_ID();
                if (isset($_POST['fetched' . $post_id])) { action_fetched($post_id); } ?>
                <form method="post" class="arb" action="">
                <button name="fetched<?php echo $post_id; ?>" type="submit" class="notif-button small blue"><i class="fas fa-check"></i>Hämtat</button>
                </form>
            </div>
            <div class="notif-meta post-list-post-meta">
                <p><span>📱 Skicka sms till <?php echo get_the_author_meta('display_name'); ?></span></p>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
<div style="height:10px" aria-hidden="true" class="wp-block-spacer"></div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>