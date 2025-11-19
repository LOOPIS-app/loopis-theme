<?php
/**
 * Template for gifts pages.
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

        <h1>🎁 Saker att få</h1>

        <?php
        // Check pagination
        $paged = get_query_var('paged') ?: 1;

        // Fetch and count available posts
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 50,
            'cat'            => '1,37,57,147',
            'paged'          => $paged,
        );

        $the_query = new WP_Query($args);
        $count = $the_query->found_posts;
        ?>

        <!-- List header -->
        <div class="columns">
            <div class="column1">↓ <?php echo $count; ?> aktuella annonser</div>
            <div class="column2 small bottom">💡 Senaste överst</div>
        </div>
        <hr>

        <!-- Posts -->
        <div class="post-list">
            <?php if ($the_query->have_posts()) : ?>
                <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                    <?php get_template_part('templates/post-list/big-posts'); ?>
                <?php endwhile; ?>
        </div><!--post-list-->

        <?php get_template_part('templates/post-list/pagination'); ?>

        <?php else : ?>
            <p>💢 Det finns inga aktuella annonser</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>