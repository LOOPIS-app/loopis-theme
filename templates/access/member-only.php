<?php
/**
 * Message for visitors in member areas.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="loopis-message information">
	<p>🚧 Du behöver vara medlem för att se något här.</p>
	<p><span class="big-link">💳 <a href="<?php echo esc_url(home_url('/shop/?option=membership-stripe')); ?>">Betala medlemskap</a></span></p>
	<?php include LOOPIS_THEME_DIR . '/templates/links/go-back.php'; ?>
</div>