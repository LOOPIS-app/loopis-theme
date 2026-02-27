<?php
/**
 * Block for routing user to FAQ on faq post
 *
 * Included in single-faq.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="wrapped">
<h5>⚠ Fler frågor?</h5>
<hr>
<p>→ Titta på sidan <a href="/faqs">Frågor & svar</a></p>
<?php if ( is_user_logged_in() ) { ?>
<p>→ Fråga i medlemmarnas <a rel="noreferrer noopener" href="https://web.facebook.com/groups/loopis.medlemmar" target="_blank">Facebook-grupp</a></p>
<?php } ?>
<p>→ Maila styrelsen på <a rel="noreferrer noopener" href="mailto:info@loopis.org" target="_blank">info@loopis.org</a></p>
</div>