<?php get_header(); ?>

<div class="content">
<div class="page-padding">
						
    <?php if (is_category()) : ?>
        <h1><?php single_cat_title(); ?></h1>
    <?php elseif (is_tag()) : ?>
        <h1><i class="fas fa-hashtag"></i><?php single_tag_title(); ?></h1>
    <?php else : ?>
        <h1>Arkiv</h1>
    <?php endif; ?>

<!--Search-->
<div>
<?php echo do_shortcode( '[searchandfilter fields="search,post_tag" show_count=1 search_placeholder="🔍 Skriv sökord" submit_label="Sök"  all_items_labels=",Kategori"]' ); ?>
</div>

<!--Post count-->
	<?php $count = $GLOBALS['wp_query']->found_posts; ?>
	
<!-- List header -->
<div class="columns"><div class="column1">
↓ <?php echo $count ?> aktuella annonser
</div>
<div class="column2">
<a href="../../faq/hur-far-jag-saker/">📌 Hur får jag saker?</a>
</div></div>
<hr>

<!--POSTS-->
<div class="post-list">
<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ): the_post(); ?>
		<div class="post-list-post-big" onclick="location.href='<?php the_permalink(); ?>';">
			<div class="post-list-post-thumbnail-big"><?php the_post_thumbnail('thumbnail'); ?></div>
			<div class="post-list-post-title-big"><?php the_title(); ?></div>
			<div class="post-list-post-meta">
				<p><?php the_category(' '); if (in_category( 'new' )) { echo raffle_time(); } if (in_category( 'borrow' )) { include LOOPIS_THEME_DIR . '/assets/output/post/borrow/borrow-days.php'; } ?></p>
				<p><i class="fas fa-walking"></i><?php echo get_field('location'); ?></p>
				<p><i class="fas fa-hashtag"></i><?php the_tags(''); ?></p>		
			</div>
		</div>
	<?php endwhile; ?>
</div><!--post-list-->

<?php if ($count > 50) { get_template_part('assets/output/general/pagination'); } ?>

	<?php endif; ?>
	
</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>