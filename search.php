<?php
/**
 * Search results template
 * Displays search results with optional tag filtering
 */

get_header(); ?>

<div class="page-padding">

        <h1>🔍 Sök </h1>
        <hr>
        <p class="small">💡 Sök bland alla aktuella annonser.</p>
        <!-- Search Form -->
        <?php get_template_part('templates/forms/search-form'); ?>

        <?php
        
        // Categories
        $available_cats = loopis_cats(['new', 'old', 'booked_custom', 'booked']);  
        // Check if search query exists
        $search_query = get_search_query();
        $tag_slug = (string) get_query_var('tag');
        $tag_term = false;
        if ($tag_slug !== '') {
            $tag_term = get_term_by('slug', $tag_slug, 'post_tag');
            if (!$tag_term) {
                $tag_slug = '';
            }
        }
        $has_search = !empty($search_query) || !empty($tag_slug);

        if ($has_search) :
            global $wp_query;
            $hits = $wp_query->found_posts;
            ?>

            <!-- List header -->
            <div class="columns">
                <div class="column1 bottom">
                    ↓ <?php echo $hits; ?> träff<?php if ($hits != 1) { echo 'ar'; } ?>
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
                <p>💢 Inga träffar<?php if ($search_query) : ?> för <span class="big-label"><b><i>"<?php echo $search_query; ?>"</i></b></span><?php endif; ?><?php if ($tag_term) : ?> i kategorin <span class="big-label"><i class="fas fa-hashtag"></i><?php echo esc_html($tag_term->name); ?></span><?php endif; ?>. Pröva något annat!</p>
            <?php endif; ?>

        <?php else : ?>
            <!-- No search query - show popular tags and random post -->
            <?php get_template_part('templates/discover/popular-tags'); ?>
            <?php get_template_part('templates/discover/random-posts'); ?>
        <?php endif; ?>

</div><!--page-padding-->

<?php get_footer(); ?>