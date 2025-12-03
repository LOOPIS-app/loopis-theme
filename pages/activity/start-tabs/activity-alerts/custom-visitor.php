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

// output
if( $the_query->have_posts() ): ?>
<h7>🚪 Dags att få besök...</h7>
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' sak'; } else { echo ' saker'; } ?>
</div><div class="column2">
</div></div>
<hr>
    <div class="post-list">
    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
    <?php 
        $fetcher = get_post_meta(get_the_ID(), 'fetcher', true); 
        $fetchername = '';
        if ($fetcher) { 
            $user = get_userdata($fetcher);
            if ($user) {
                $fetchername = $user->display_name;
            }
        } 
    ?>
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
                if (isset($_POST['fetched_custom' . $post_id])) { action_fetched_custom($post_id); } ?>
                <form method="post" class="arb" action="">
                <button name="fetched_custom<?php echo $post_id; ?>" type="submit" class="notif-button small red" onclick="return confirm('Har <?php echo addslashes($fetchername); ?> hämtat?')"><i class="fas fa-check"></i>Hämtat</button>
                </form>
            </div>
            <div class="notif-meta post-list-post-meta">
                <p>📱<?php echo esc_html($fetchername); ?> ska hämta</p>
            </div>
        </div>            
    <?php endwhile; ?>
    </div>
<div style="height:10px" aria-hidden="true" class="wp-block-spacer"></div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>