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
<?php echo do_shortcode('[loopis_support_post]');?>
</div>