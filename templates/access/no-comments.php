<?php
/**
 * Message for visitors in comment section.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Set URL for link in message
$faq_url = home_url( '/faq/' );
?>
<div class="columns"><div class="column1"><h3><i class="far fa-comment"></i></h3></div>
<div class="column2">Senaste överst ↓</div></div>
<hr>
<div class="loopis-message information">
	<p>🤐 Du behöver vara medlem för att läsa och skriva kommentarer här.</p>
	<p><span class="big-link"><a href="<?php echo esc_url($faq_url . 'varfor-medlemskap/');?>">📌 Varför måste jag vara medlem?</a></span></p>
</div>