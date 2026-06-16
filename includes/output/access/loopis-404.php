<?php
/**
 * Error message for 404 page.
 * 
 * This template is used when a user tries to access a page that doesn't exist.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get the full requested URL for display
$scheme = is_ssl() ? 'https://' : 'http://';
$host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
$request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
$full_request_url = $scheme . $host . $request_uri;
?>
        <p class="small">💡 Något gick fel...</p>
        <p>Den här sidan finns inte: <span class="link">🔗 <?php echo esc_url($full_request_url); ?></span></p>
		
		<p><span class="big-link"><?php get_template_part('templates/links/go-back'); ?></span></p>
        
        <?php if (is_user_logged_in()) : ?>
            <div class="loopis-message information">
                <h5>🪰 Hittat en bugg?</h5>
                <hr>
                <p>Berätta gärna hur du hamnade här i formuläret längst ner.</p>
            </div>
            
        <?php endif; ?>