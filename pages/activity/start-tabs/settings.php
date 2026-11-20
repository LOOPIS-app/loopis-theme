<?php
/**
 * Settings tab.
 * 
 * Links to available settings for the current user.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<h7>⚙️ Mina inställningar</h7>
<hr>
<div class="span-list">

<span class="mega-link">
	<a href="<?php echo esc_url( add_query_arg(array([
            'view' => 'posts-submitted',
            'status' => 'paused',
        ]), $activity_url) ); ?>">😎 Pausa annonser</a>
		</span> om du t.ex. ska resa bort<br>

<span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array([
            'view' => 'settings-subsite',
        ]), $activity_url) ); ?>">✉️ Mailutskick</a></span> för olika typer av meddelanden<br>

<span class="mega-link"><a href="<?php echo esc_url(network_home_url('/user')); ?>">📋 Medlemskap</a></span> på LOOPIS startsida<br>

<span class="mega-link"><a href="<?php echo esc_url(get_loopis_logout_url()); ?>">🚪 Logga ut</a></span> från LOOPIS.app<br>
</div>