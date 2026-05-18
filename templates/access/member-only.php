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
	<?php include LOOPIS_THEME_DIR . '/templates/links/go-back.php'; ?>
</div>

<a href="<?php echo esc_url( wp_login_url(home_url())) ; ?>"><button name="log-in" type="submit" class="green">Logga in</button></a>