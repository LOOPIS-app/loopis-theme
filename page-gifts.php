<?php
/**
 * Content for page using url /gifts
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


    $all_tags = get_terms( array('taxonomy' => 'post_tag', 'hide_empty' => false,));

    $unselected_tags = isset( $_GET['untags'] )
        ? array_filter( array_map( 'absint', (array) wp_unslash( $_GET['untags'] ) ) )
        : array();

    $filter_submitted = isset( $_GET['filter_submitted'] ) || isset( $_GET['untags'] );
    $save = isset($_GET['save']) ? (int) $_GET['save']  : 0;

    if (
        is_user_logged_in()
        && $save
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


    if(!empty($tax_query)){
        $args['tax_query'] = $tax_query;
    }

    $the_query = new WP_Query($args);
    $count = $the_query->found_posts;

    include_once LOOPIS_THEME_DIR . '/includes/output/front-page/filter-header.php';
    ?>


        <!-- Posts -->
        <div class="post-list">
            <?php if ($the_query->have_posts()) : ?>
                <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                    <?php get_template_part('templates/post-list/big-posts'); ?>
                <?php endwhile; ?>
        </div><!--post-list-->
        <?php 
            set_query_var('custom_query', $the_query);
            include_once get_template_directory() . '/templates/post-list/pagination.php'; 
        ?>
        <?php else : ?>
            <p>💢 Det finns inga aktuella annonser</p>
        <?php endif; ?>

        <?php 
            wp_reset_postdata(); 
        ?>

    </div><!--page-padding-->

<?php get_footer(); ?>