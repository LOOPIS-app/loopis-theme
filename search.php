<?php get_header(); ?>

<div class="content">
<div class="page-padding">
				
	<?php get_template_part('assets/page-title'); ?>

<!--Search-->
<div>
<?php echo do_shortcode( '[searchandfilter fields="search,post_tag" show_count=1 search_placeholder="🔍 Skriv sökord" submit_label="Sök"  all_items_labels=",Kategori"]' ); ?>
</div>

<p>Visar både <span class="label">🎁Saker att få</span> och <span class="label">🗓Saker att låna</span></p>

<!--Post count-->
<?php $hits=$wp_query->found_posts; ?>

<!-- List header -->
<div class="columns"><div class="column1">
↓ <?php echo $hits; ?> sökträff<?php if ( $hits != 1 ) { echo 'ar'; } ?>
</div>
<div class="column2">
<?php if ( $hits > 1 ) { echo '(Senaste överst)'; } ?>
</div></div>
<hr>

<!--No hits-->
<?php if ( !have_posts() ): ?>
<p>💢 Inga träffar för "<?php echo get_search_query(); ?>" tyvärr. Pröva något annat!</p>
<?php endif; ?>

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
		
<?php get_template_part('assets/output/general/pagination'); ?>

<?php endif; ?>
		
</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>