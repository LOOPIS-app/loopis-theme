<?php
/* Template Name: Gifts-random Template */
?>

<?php get_header(); ?>

<div class="content">
	<div class="page-padding">

<h1>🤹 Fyndhörnan</h1>						
<hr>
<p class="small">💡 Här visas tre slumpade saker som är <span class="small-label">🟢 Först till kvarn</span></p>
<?php
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 3,
    'cat'   		 => '37',
 	'orderby' => 'rand',
);

$the_query = new WP_Query( $args );
$count = $the_query->found_posts; ?>

<div class="columns"><div class="column1">↓ 3 av <?php echo $count; ?> annonser</div>
<div class="column2"></div></div>
<hr>
<div class="post-list">

<?php if ( $the_query->have_posts() ) : ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	<div class="post-list-post-big" onclick="location.href='<?php the_permalink(); ?>';">
		<div class="post-list-post-thumbnail-big"><?php the_post_thumbnail('thumbnail'); ?></div>
		<div class="post-list-post-title-big"><?php the_title(); ?></div>
		<div class="post-list-post-meta">
			<p><?php the_category(' '); if (in_category( 'new' )) { echo raffle_time(); } ?></p>
			<p><i class="fas fa-walking"></i><?php echo get_field('location'); ?></p>
			<p><i class="fas fa-hashtag"></i><?php the_tags(''); ?></p>		
		</div>
	</div>
	<?php endwhile; ?>

<p><a href="/gifts-random"><button type="button">🪄 Visa tre andra!</button></a></p>

<p class="info">Tryck på knappen för att hitta saker du inte visste att du behövde – eller en oväntad present till din vän. Alla saker är först till kvarn och kan paxas direkt!</p>

<h3>Letar du efter något särskilt?</h3>
<hr>
<p>Ta en titt på våra <span class="link"><a href="/kategorier/"><i class="fas fa-hashtag"></i>Kategorier</a></span> eller <span class="link"><a href="/search/">🔍</i> Sök</a></span></p>

<?php else : ?>
    <p>💢 Det finns inga aktuella annonser</p>
<?php endif; ?>

</div><!--post-list-->

<?php wp_reset_postdata(); ?>

</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>