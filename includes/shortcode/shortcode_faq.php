<?php
/**
 * Function for fetching and displaying posts relevant to festival event.
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */


/**
 *  Shortcode: [loopis_faq_foot]
 * 
 *  Displays the faq page end!
 * 
 * @return string HTML output
 */

add_shortcode( 'loopis_faq_foot', function () {
	ob_start();
	?>

	<div style="height:25px" aria-hidden="true" class="wp-block-spacer"></div>
	
	<div class="wrapped faq">
	<h5>⚠ Fler frågor?</h5>
	<hr>
	<p>→ Titta på sidan <a href="/faq">Frågor &amp; svar</a></p>
	<?php if ( is_user_logged_in() ) { ?>
	<p>→ Fråga i medlemmarnas <a rel="noreferrer noopener" href="https://web.facebook.com/groups/loopis.medlemmar" target="_blank">Facebook-grupp</a></p>
	<?php } ?>
	<p>→ Maila styrelsen på <a rel="noreferrer noopener" href="mailto:info@loopis.org" target="_blank">info@loopis.org</a></p>
	</div>

	<?php
	return ob_get_clean();
} );
