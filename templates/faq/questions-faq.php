<?php
/**
 * Block for routing user to FAQ on faq post
 *
 * Included in single-faq.php and archive-faq.php
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="wrapped">
<h5>⚠ Fler frågor?</h5>
<hr>
<p>→ Titta först på sidan <a href="<?php echo esc_url(home_url('/faq/'));?>">Frågor & svar</a>!</p>
<?php if ( is_user_logged_in() ) { ?>
<p>→ Fråga admin i rutan längst ner på sidan det handlar om.</p>
<p>→ Fråga i medlemmarnas <a rel="noreferrer noopener" href="https://web.facebook.com/groups/loopis.medlemmar" target="_blank">Facebook-grupp</a> eller <a rel="noreferrer noopener" href="https://discord.com/channels/1480883243740954626/1480883244449927231" target="_blank">Discord-server</a></p>
<?php } else { ?>
<p>→ Maila admin på <a rel="noreferrer noopener" href="mailto:info@loopis.app" target="_blank">info@loopis.app</a></p>
<?php } ?>
</div>