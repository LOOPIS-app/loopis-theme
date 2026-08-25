<?php
/**
 * Block in footer.php for routing users to support
 * 
 * Improvents:
 * – Make clicking the link copy current URL to be pasted later in a potential forum post?
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div id="support-form">
<h5>🛟 Behöver du hjälp?</h5>
<hr>
<p>→ Gå till <span class="big-link white"><a href="<?php echo esc_url(home_url( '/area/' ));?>">📍 <?php echo get_bloginfo('name'); ?></a></span></p>
</div>