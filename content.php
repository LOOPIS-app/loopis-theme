<?php 
// This file is not in use... I think :)

$format = get_post_format(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('masonry-item group'); ?>>	
	<div class="masonry-inner">
		
		<div class="entry-top">
			<a class="entry-thumbnail" href="<?php the_permalink(); ?>">
				<?php if ( has_post_thumbnail() ): ?>
					<?php the_post_thumbnail('thumbnail'); ?>
				<?php else: ?>
					<img src="<?php echo esc_url( LOOPIS_THEME_URI ); ?>/img/thumb-medium.png" alt="<?php the_title_attribute(); ?>" />
				<?php endif; ?>
			</a>
		</div>

		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>		
		<ul class="entry-meta group">
			<li class="padgin0"><?php the_category(''); ?></li>
			<li><i class="fas fa-walking"></i><?php echo get_post_meta($post->ID, 'location', true); ?></li>
			<li><i class="fas fa-hashtag"></i><?php the_tags(''); ?></li>
		</ul>
		<div class="entry-excerpt"><a href="<?php the_permalink(); ?>"><?php the_excerpt(); ?></a></div>
		
	</div>
</article>