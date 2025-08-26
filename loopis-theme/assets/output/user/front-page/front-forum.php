<?php
/**
 * Front page forum post for member.
 *
 * Included in front-page.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Query to get forum post in category start
$args = array(
    'post_type' => 'forum',
    'tax_query' => array(
        array(
            'taxonomy' => 'forum-category',
            'field' => 'slug',
            'terms' => array('start'),
        ),
    ),
);
$the_query = new WP_Query($args);

// Initialize category variable
$category = '';

// Fetch the first non-'start' category for the posts
if ($the_query->have_posts()) :
    while ($the_query->have_posts()) : $the_query->the_post();
        $categories = get_the_terms(get_the_ID(), 'forum-category');
        if ($categories && !is_wp_error($categories)) {
            foreach ($categories as $cat) {
                if ($cat->slug !== 'start') {
                    $category = $cat->name;
                    break;
                }
            }
        }
    endwhile;
    wp_reset_postdata();
endif;
?>

<?php if ( $the_query->have_posts() ) : ?>
<div class="columns"><div class="column1"><h5><?php if ($category) { echo esc_html($category);} ?></h5></div>
<div class="column2 bottom"><!--a href="/forum">Arkiv →</a--></div></div>
<hr>
<style>
/* Forum-list */
.post-list-forum { display: flex; align-items: stretch; overflow: hidden; min-height: 60px; background: #f5f5f5; box-shadow: 2px 2px 5px #eee; margin-bottom: 13px; cursor: pointer; }
.post-list-forum-content { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 5px 8px; }
.post-list-forum-title { font-size: 22px; color: #333; padding: 3px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.post-list-forum-excerpt .read-more { font-weight: 400; color: #1e73be; }
.post-list-forum-thumbnail { flex: 0 0 60px; margin-left: auto; }
.post-list-forum-thumbnail img { width: 100%; height: 100%; object-fit: cover; }
</style>
<div class="post-list">
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    <div class="post-list-forum" onclick="location.href='<?php echo the_permalink(); ?>';">
        <div class="post-list-forum-content">
            <div class="post-list-forum-title"><?php the_title(); ?></div>
            <div class="post-list-forum-excerpt"><p><?php echo get_the_excerpt() . ' <span class="read-more"> → Läs mer</span>'; ?></p></div>
        </div>
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-list-forum-thumbnail">
                <?php the_post_thumbnail('thumbnail'); // Display the square thumbnail ?>
            </div>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
</div><!--post-list-->
<?php endif; ?>
<?php wp_reset_postdata(); ?>