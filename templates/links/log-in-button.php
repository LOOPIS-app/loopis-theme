<?php
/**
 * Output button for main site log in with redirect.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<p><button type="button" class="green" onclick="window.location.href='<?php echo esc_url(get_login_url()); ?>'">Logga in</button></p>