<?php
/**
 * Message for visitors in member areas.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="wpum-message information">
	<p>🚧 Du behöver vara medlem för att se något här.</p>
	<p><a href="javascript:history.back()"><i class="fas fa-chevron-left"></i>Gå tillbaka</a></p>
</div>

<a href="/login"><button name="log-in" type="submit" class="green">Logga in</button></a>&nbsp;&nbsp;
<a href="/register"><button name="register" type="submit" class="blue">Bli medlem</button></a>