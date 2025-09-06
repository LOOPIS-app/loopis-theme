<?php get_header(); ?>

<div class="content">
<div class="page-padding">
<?php get_template_part('assets/page-title'); ?>
<hr>
<p><?php esc_html_e('Den sidan finns inte.','gridzone'); ?></p>
<p><span><a href="javascript:history.back()"><i class="fas fa-chevron-left"></i> Gå tillbaka</a></span></p>

<div class="clear"></div>
</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>