<?php
/**
 * SUBMIT POST
 * 
 * Dynamic content of page-submit.php
 * Reached on /submit/?option=single
 * 
 * Showing form to submit post
 * 
 * Footer is excluded to maximize space + avoid exiting when editing post content
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="columns"><div class="column1"><h1>🎁 Ge bort en sak</h1></div>
<div class="column2 bottom"><a href="javascript:history.back()" onclick="return confirm('Det du fyllt i försvinner.')">❌ Avbryt</a></div></div>
<hr>

<p class="small">
💡 Lägg bara upp <u>en sak i varje annons</u><br>
💡 Fota gärna med ren bakgrund<br>
💡 Lägg inte upp <a href="<?php esc_url( home_url('/faq/restriktioner'));?>">otillåtna annonser</a>
</p>

<!-- WPUM Frontend Posting -->
<!--?php echo do_shortcode('[wpum_post_form form_id="1"]'); ?-->

<!-- Work in progress! -->
<?php get_template_part('templates/forms/gift-form'); ?>

</div><!--page-padding-->

<?php wp_footer(); ?>