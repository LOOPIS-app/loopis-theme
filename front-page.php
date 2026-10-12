<?php
/**
 * LOOPIS subsite front page
 * 
 * Display notifications + messages + current posts.
 */

get_header(); ?>
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/front-page.js"></script>
<div class="page-padding">

    <?php
    // Get current user
    $user_roles = array();
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $user = wp_get_current_user();
        $user_roles = (array) $user->roles;
        $user_firstname = $user->first_name;
    }
    
    // Greeting & message for non-members
    if (!in_array('member', $user_roles, true) && !current_user_can('loopis_admin')) {
        include LOOPIS_THEME_DIR . '/includes/output/access/subsite-greeting.php';
        include LOOPIS_THEME_DIR . '/includes/output/access/subsite-message.php';
    }

    // Notifications for members
    if (in_array('member', $user_roles, true) || current_user_can('loopis_locker') || current_user_can('loopis_admin')) {
        include LOOPIS_THEME_DIR . '/includes/output/front-page/front-alerts.php';
        include LOOPIS_THEME_DIR . '/includes/output/front-page/front-news.php';
        }

    $all_tags = get_terms( array('taxonomy' => 'post_tag', 'hide_empty' => false,));

    $unselected_tags = isset( $_GET['untags'] )
        ? array_filter( array_map( 'absint', (array) wp_unslash( $_GET['untags'] ) ) )
        : array();

    $filter_submitted = isset( $_GET['filter_submitted'] ) || isset( $_GET['untags'] );

    if (
        is_user_logged_in()
        && isset( $_GET['save'] )
        && isset( $_GET['loopis_filter_nonce'] )
        && wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_GET['loopis_filter_nonce'] ) ),
            'save_loopis_filter'
        )
    ) {
        update_user_meta(
            $user_id,
            'loopis_filter_gifts',
            array(
                'untags' => $unselected_tags
            )
        );
    } elseif ( is_user_logged_in() && ! $filter_submitted ) {
        $presets = get_user_meta( $user_id, 'loopis_filter_gifts', true );
        if ( is_array( $presets ) ) {
            $unselected_tags = isset( $presets['untags'] ) && is_array( $presets['untags'] )
                ? array_map( 'absint', $presets['untags'] )
                : array();
        }
    }

    $tax_query = array();

    if(!empty($unselected_tags)){
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field'    => 'term_id',
            'terms'    => $unselected_tags,
            'operator' => 'NOT IN',
        );
    }

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

     // Count new posts
    $count_new_args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'cat'            => loopis_cat('new'),
    );

    if(!empty($tax_query)){
        $args['tax_query'] = $tax_query;
        $count_new_args['tax_query'] = $tax_query;
    }
    $count_new_query = new WP_Query($count_new_args);
    $count_new = $count_new_query->found_posts;


    $the_query = new WP_Query($args);
    $count_total = $the_query->found_posts;
    $count_old = $count_total - $count_new;
    ?>

    <h1>🎁 Saker att få</h1>

    <div class="columns">
        <div class="column1">↓ <?php echo $count_new; ?> nya och <?php echo $count_old; ?> tidigare</div>
        <div class="column2"><button class="link-style" onclick="reveal('filter');"><i class="fas fa-sliders-h"></i>Filter</a></div>
    </div>
    <hr>
    <script>
    function reveal(objectId) {
        const object = document.getElementById(objectId);
        object.hidden = !object.hidden;
    }
    </script>
    
    <div id="filter" hidden>
        <div class="filter-wrapper">
            <form method="get" action="">
                <div class="form-row" id="gift-untags-row">
                <label for="untags-search">Jag vill inte se:</label><br>
                <input type="search" id="untags-search" placeholder="Sök kategori..." autocomplete="off">
                <div id="untags-options" class="untags-options" role="group" aria-label="Kategorier">
                    <?php if ( ! is_wp_error( $all_tags ) ) : ?>
                        <?php foreach ( $all_tags as $tag ) : ?>
                            <label class="untag-option" data-term-label="<?php echo esc_attr($tag->name); ?>">
                                <input type="checkbox" name="untags[]" value="<?php echo esc_attr($tag->term_id); ?>" <?php checked(in_array((int) $tag->term_id, $unselected_tags, true)); ?>>
                                <span><?php echo esc_html($tag->name); ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>         
                </div>
                <input type="hidden" name="filter_submitted" value="1">
                <?php wp_nonce_field( 'save_loopis_filter', 'loopis_filter_nonce', false ); ?>               
                <input type="checkbox" id="save" name="save" value="1">
                <label for="save">Spara inställningar?</label>
                <button type="submit">Filtrera</button>
            </form>
        </div>
        <hr>
    </div>
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
                'add_args'     => array_filter(array('untags' => $unselected_tags)),
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

<?php
// Add to homescreen prompt for logged-in users
if (is_user_logged_in()) {include LOOPIS_THEME_DIR . '/includes/output/front-page/add-to-homescreen.php'; } ?>

<?php get_footer(); ?>