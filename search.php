<?php
/**
 * Search results template
 * Displays search results with optional tag filtering
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

        <h1>🔍 Sök</h1>
        <hr>
        <p class="small">💡 Sök bland alla aktuella annonser.</p>

        <!-- Search Form -->
        <?php get_template_part('templates/search/search-form'); ?>

        <?php
        // Check if search query exists
        $search_query = get_search_query();
        $has_search = !empty($search_query) || (isset($_GET['tag']) && !empty($_GET['tag']));

        if ($has_search) :
            // Modify query if tag is selected
            if (isset($_GET['tag']) && !empty($_GET['tag'])) {
                global $wp_query;
                $tag_slug = sanitize_text_field($_GET['tag']);

                $args = array(
                    's'              => $search_query,
                    'tag'            => $tag_slug,
                    'cat'            => '1,37,57,147',  // Only available categories
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'posts_per_page' => get_option('posts_per_page'),
                    'paged'          => get_query_var('paged') ?: 1,
                );

                $wp_query = new WP_Query($args);
            }

            $hits = $wp_query->found_posts;
            ?>

            <!-- List header -->
            <div class="columns">
                <div class="column1 bottom">
                    ↓ <?php echo $hits; ?> träff<?php if ($hits != 1) { echo 'ar'; } ?>
                    <?php if (isset($_GET['tag']) && !empty($_GET['tag'])) : ?>
                        <?php $tag = get_term_by('slug', $_GET['tag'], 'post_tag'); ?>
                    <?php endif; ?>
                </div>
                <div class="column2 small bottom">
                    <?php if ($hits > 1) { echo '💡 Senaste överst'; } ?>
                </div>
            </div>
            <hr>

            <!-- Post output -->
            <div class="post-list">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('templates/post-list/big-posts'); ?>
                    <?php endwhile; ?>
            </div>

            <?php get_template_part('templates/post-list/pagination'); ?>

            <?php else : ?>
                <p>💢 Inga träffar<?php if ($search_query) : ?> för <span class="big-label"><b><i>"<?php echo $search_query; ?>"</i></b></span><?php endif; ?><?php if (isset($_GET['tag']) && !empty($_GET['tag'])) : ?><?php $tag = get_term_by('slug', $_GET['tag'], 'post_tag'); ?><?php if ($tag) : ?> i kategorin <span class="big-label"><i class="fas fa-hashtag"></i><?php echo esc_html($tag->name); ?></span><?php endif; ?><?php endif; ?>. Pröva något annat!</p>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

        <?php else : ?>
            <!-- No search query - show popular tags and random post -->
            <?php get_template_part('templates/search/popular-tags'); ?>
            <?php get_template_part('templates/search/random-posts'); ?>
        <?php endif; ?>

    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>