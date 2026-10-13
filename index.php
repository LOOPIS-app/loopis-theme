<?php

/**
 * The main template file.
 *
 * This is the fallback template in WordPress.
 * It is used to display content when no specific template matches a query.
 *
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
    ?>

        <!-- List header -->
        <div class="columns">
            <div class="column1">↓ <?php echo $count; ?> aktuella annonser</div>
            <div class="column2"><button class="link-style" onclick="reveal('filter');"><i class="fas fa-sliders-h"></i>Filter</a></div>
        </div>
        <hr>
        
    <script>
        function reveal(objectId) {
            const object = document.getElementById(objectId);
            object.classList.toggle('is-visible');
        }
    </script>
    
    <div id="filter" class='filter-container'>
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
                <button type="submit" name="save" value="1" class="blue">Spara</button>
                <button type="submit" name="save" value="0" class="green">Filtrera</button>
            </form>
        </div>
        <hr>
    </div>

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
