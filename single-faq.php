<?php get_header(); ?>

<div class="content">
<div class="page-padding">

<?php the_content(); ?>
<div class="clear"></div>

<!-- More questions? -->
<?php include LOOPIS_THEME_DIR . '/templates/faq/questions-faq.php'; ?>

</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>