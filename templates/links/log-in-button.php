<?php
/**
 * Show button to log in.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<p><button type="button" class="green" onclick="window.location.href='<?php echo esc_url(home_url('/log-in')); ?>'">Logga in</button></p>