<?php
/**
 * Support form for members.
 *
 * Included in footer.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div id="support" class="feedback">
<h4>🛟 Behöver du hjälp?</h4>
	<p class="small">Skriv din fråga här så svarar admin.</p>
<?php echo do_shortcode('[wpum_post_form form_id="3"]');?>
</div>