<?php
/**
 * Front page template
 * Displays all available posts in categories 1, 37, 57, 147
 * Displays messages for users and visitors
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

        <?php
        // Messages for users and visitors
        if (current_user_can('member') || current_user_can('administrator')) {
            include_once LOOPIS_THEME_DIR . '/templates/user/front-page/front-alerts.php';
            include_once LOOPIS_THEME_DIR . '/templates/user/front-page/front-forum.php';
        } else {
            include_once LOOPIS_THEME_DIR . '/templates/user/front-page/front-message.php';
        }

        // Count new posts
        $count_new_args = array(
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'cat'            => loopis_cat('new'),
        );
        $count_new_query = new WP_Query($count_new_args);
        $count_new = $count_new_query->found_posts;

        wp_reset_postdata();

        // Check pagination
        $paged = get_query_var('paged') ?: 1;

        // Get available posts categories
        $available_posts = loopis_cats(['new', 'old', 'booked', 'booked_custom']);
        
        // Fetch and count available posts
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 50,
            'category__in'   => $available_posts,
            'paged'          => $paged,
        );

        $the_query = new WP_Query($args);
        $count_total = $the_query->found_posts;
        $count_old = $count_total - $count_new;
        ?>

        <h1>🎁 Saker att få</h1>

        <!-- List header -->
        <div class="columns">
            <div class="column1">↓ <?php echo $count_new; ?> nya och <?php echo $count_old; ?> tidigare</div>
            <div class="column2"><a href="/discover/?view=random-posts">🤹 Fyndhörnan →</a></div>
        </div>
        <hr>

        <!-- Posts output -->
        <div class="post-list">
            <?php if ($the_query->have_posts()) : ?>
                <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                    <?php get_template_part('templates/post-list/big-posts'); ?>
                <?php endwhile; ?>
        </div><!--post-list-->

        <?php if ($the_query->max_num_pages > 1) : ?>
            <div id="post-pagination">
                <?php
                // Custom pagination that redirects to /gifts/
                echo wp_kses_post(paginate_links(array(
                    'base'         => trailingslashit(home_url('/gifts/page/%#%/')),
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
                )));
                ?>
            </div><!--/.post-pagination-->
        <?php endif; ?>

        <?php else : ?>
            <p>💢 Det finns inga aktuella annonser</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div><!--page-padding-->
</div><!--content-->

<?php
// Add to homescreen prompt for logged-in users
if (is_user_logged_in()) {
    get_template_part('templates/user/front-page/add-to-homescreen');
}
?>

<?php get_footer(); ?>