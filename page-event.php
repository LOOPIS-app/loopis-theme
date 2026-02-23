<?php
/**
 * Content for page using url /event
 * 
 * Will be dynamically managed later.
 */

get_header(); ?>

<div class="content">
	<div class="page-padding">

<h1>🎉 Event</h1>						
<hr>
<p class="small">💡 Information om kommande event</p>

<p>Idag klockan 11-16 är LOOPIS på klimatfestivalen i Folkets Hus!
Bli medlem idag så kan du ta hem fem saker från vårt LOOPIS-bord.</p>

<p>Här nedanför ser du vad som fortfarande finns att hämta. Kom gärna förbi och ta en titt!</p>

<p><span class="big-link"><a href="../register">📋 Bli medlem!</a></span> - det kostar bara 50 kronor!</p>
<p><span class="big-link"><a href="/faq/-hur-funkar-loopis">📌 Hur funkar LOOPIS?</a></span> - svaret finns här.</p>

<p class="info">PS. Som medlem kan du såklart också titta på <span class="small-link"><a href="/things">🎁 Saker att få</a></span> där XXX saker just nu finns att få på vanligt LOOPIS-sätt.</p>

<?php
// Arguments
$args = array(
    'post_type' => 'post',
    'cat'   	=> '155', // Category 'tips'
    'post_status' => 'publish',
    'posts_per_page' => -1, // Output all posts
);

// Query
$the_query = new WP_Query($args);
$count = $the_query->found_posts;
?>

<!--Output-->
<h7>🎁 Saker att hämta</h7>
<div class="columns"><div class="column1">↓ <?php echo $count; ?> sak<?php if ($count !== 1) { echo "er"; } ?> kvar</div>
<div class="column2"></div></div>
<hr>

<div class="post-list">

<?php if ( $the_query->have_posts() ) : ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	<div class="post-list-post-big" onclick="location.href='<?php the_permalink(); ?>';">
		<div class="post-list-post-thumbnail-big"><?php the_post_thumbnail('thumbnail'); ?></div>
		<div class="post-list-post-title-big"><?php the_title(); ?></div>
		<div class="post-list-post-meta">
			<p>🟢 Fortfarande kvar</p>
			<p><i class="fas fa-walking"></i>Folkets Hus</p>
			<p><i class="fas fa-hashtag"></i><?php the_tags(''); ?></p>		
		</div>
	</div>
<?php
    endwhile;
    wp_reset_postdata();
	else : ?>
    <p>💢 Det finns inga saker kvar att hämta.</p>
<?php endif; ?>
</div><!--post-list-->

<?php wp_reset_postdata(); ?>

</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>