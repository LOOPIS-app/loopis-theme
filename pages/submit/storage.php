<?php
/**
 * SUBMIT POST TO STORAGE
 * 
 * Dynamic content of page-submit.php
 * Reached on /submit/?option=storage
 * 
 * Showing form to submit posts to storage
 * 
 * Footer is excluded to maximize space + avoid exiting when editing post content
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="columns"><div class="column1"><h1>📦 Lägg i lager</h1></div>
<div class="column2"><a href="javascript:history.back()" onclick="return confirm('Det du fyllt i försvinner.')"><i class="fas fa-times-circle"></i>Avbryt</a></div></div>
<hr>

<!-- Access? -->
<?php if (current_user_can('loopis_storage')) { ?>

<p class="small">
⚠ Denna annons kommer att kunna paxas på event eller publiceras senare.
</p>

		<!-- WPUM Frontend Posting -->
		<?php echo do_shortcode('[wpum_post_form form_id="6"]'); ?>

<!-- No access -->
<?php } else { 
  include LOOPIS_THEME_DIR . '/templates/access/no-access.php';
  } ?>

<div class="clear"></div>

</div><!--page-padding-->