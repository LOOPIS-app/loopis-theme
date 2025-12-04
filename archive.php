<?php
/**
 * Archive template
 * Displays category, tag, and other archive pages
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

        <?php if (is_category()) : ?>
            <h1><?php single_cat_title(); ?></h1>
        <?php elseif (is_tag()) : ?>
            <h1><i class="fas fa-hashtag"></i><?php single_tag_title(); ?></h1>
        <?php else : ?>
            <h1>Arkiv</h1>
        <?php endif; ?>
		<hr>
		<p class="small">💡 Alla annonser i <span class="small-label"><?php if (is_category()) { echo single_cat_title('', false); } elseif (is_tag()) { echo '<i class="fas fa-hashtag"></i>'; echo single_tag_title('', false); } else { echo 'arkivet'; } ?>.</span></p>

        <!-- Search Form -->
		<h3>🔍 Sök</h3>
        <?php get_template_part('templates/search/search-form'); ?>

        <?php
        // Post count
        $count = $GLOBALS['wp_query']->found_posts;
        ?>

        <!-- List header -->
        <div class="columns">
            <div class="column1">↓ <?php echo $count; ?> aktuella annonser</div>
            <div class="column2"><a href="../../faq/hur-far-jag-saker/">📌 Hur får jag saker?</a></div>
        </div>
        <hr>

        <!-- Posts -->
        <div class="post-list">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('templates/post-list/big-posts'); ?>
                <?php endwhile; ?>
        </div><!--post-list-->

        <?php if ($count > 50) {
            get_template_part('templates/post-list/pagination');
        } ?>

        <?php else : ?>
            <p>💢 Inga inlägg hittades</p>
        <?php endif; ?>

    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>