<?php
/**
 * Discover: three random posts.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🤹 Fyndhörnan</h1>
<hr>
<p class="small">💡 Här visas tre slumpade saker som är <span class="small-label">🟢 Först till kvarn</span></p>

<?php
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 3,
    'cat'   		 => loopis_cat('old'),
 	'orderby' => 'rand',
);

$the_query = new WP_Query( $args );
$count = $the_query->found_posts; ?>

<div class="columns"><div class="column1">↓ 3 av <?php echo $count; ?> annonser</div>
<div class="column2"></div></div>
<hr>
 <!-- Posts output -->
<div class="post-list">
        <?php if ($the_query->have_posts()) : ?>
            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                <?php get_template_part('templates/post-list/big-posts'); ?>
            <?php endwhile; ?>
</div><!--post-list-->

<p><a href="/discover/?view=random-posts"><button type="button">🪄 Visa tre andra!</button></a></p>

<p class="info">Tryck på knappen för att hitta saker du inte visste att du behövde - eller en oväntad present till din vän. Alla sakerna är först till kvarn och kan paxas direkt!</p>

<h3>Letar du efter något särskilt?</h3>
<hr>
<p>Ta en titt på våra <span class="link"><a href="/discover/?view=categories"><i class="fas fa-hashtag"></i>Kategorier</a></span> eller <span class="link"><a href="/?s=">🔍</i> Sök</a></span></p>

<?php else : ?>
    <p>💢 Det finns inga aktuella annonser</p>
<?php endif; ?>


<?php wp_reset_postdata(); ?>