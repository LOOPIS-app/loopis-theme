<?php
/**
 * Generic message for visitors or users where they do not have access.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="loopis-message information">
	<p>🚧 Du har inte behörighet att se denna sida.</p>
	<p><span class="big-link"><?php get_template_part('templates/links/go-back'); ?></span></p>
</div>