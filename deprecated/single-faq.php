<?php get_header(); ?>

<!-- POST CONTENT -->

<div class="page-padding">
    
<p><span class="rounded" style="background: #f5f5f5;"><a href="<?php echo get_post_type_archive_link('faq'); ?>">💡 Frågor & svar</a></span>
<!-- Copy link -->
<a href="#" id="copy_url" class="option">🔗 Kopiera länk</a>

<?php the_content(); ?>
<div class="clear"></div>

<!-- More questions? -->
<?php include LOOPIS_THEME_DIR . '/templates/faq/questions-faq.php'; ?>

        </div><!--page-padding-->


<?php get_footer(); ?>