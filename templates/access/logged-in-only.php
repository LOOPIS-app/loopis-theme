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
	<p><span class="big-link"><?php get_template_part('templates/links/go-back'); ?></span></p>
</div>

<?php get_template_part('templates/links/log-in-button'); ?>