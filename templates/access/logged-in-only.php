<?php
/**
 * Message for visitors in member areas.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="loopis-message information">
	<p>🚧 Du behöver vara inloggad för att se något här.</p>
	<?php include LOOPIS_THEME_DIR . '/templates/links/go-back.php'; ?>
</div>

<?php include LOOPIS_THEME_DIR . '/templates/links/log-in-button.php'; ?>