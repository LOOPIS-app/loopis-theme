<?php
/**
 * Archive template
 *
 * Displays category and tag archives for gift posts
 * 
 * Reached at: https://loopis.app/category/x/
 * Reached at: https://loopis.app/tag/x/
 */

get_header(); ?>

<div class="page-padding">

        <?php if (is_category()) : ?>
            <h1><?php single_cat_title(); ?></h1>
        <?php elseif (is_tag()) : ?>
            <h1><i class="fas fa-hashtag"></i><?php single_tag_title(); ?></h1>
        <?php else : ?>
            <h1>Arkiv</h1>
        <?php endif; ?>
		<hr>
		<p class="small">💡 Alla annonser <?php if (is_category()) { echo 'med status <span class="label">'; echo single_cat_title('', false); echo '</span>'; } elseif (is_tag()) { echo 'i kategorin <span class="label"><i class="fas fa-hashtag"></i>'; echo single_tag_title('', false); echo '</span>'; } else { echo 'arkivet'; } ?></p>

        <!-- Search Form -->
        <?php get_template_part('templates/forms/search-form'); ?>

        <?php
        // Post count
        $count = $GLOBALS['wp_query']->found_posts;
        ?>

        <!-- List header -->
        <div class="columns">
            <div class="column1">↓ <?php echo $count; ?> annonser</div>
            <div class="column2 small">💡 Senast överst</div>
        </div>
        <hr>

        <!-- Posts -->
        <div class="post-list">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('templates/post-list/big-posts'); ?>
                <?php endwhile; ?>
        </div><!--post-list-->

        <?php if ($count > 50) { get_template_part('templates/post-list/pagination'); } ?>

        <?php else : ?>
            <p>💢 Inga inlägg hittades</p>
        <?php endif; ?>

</div><!--page-padding-->


<?php get_footer(); ?>