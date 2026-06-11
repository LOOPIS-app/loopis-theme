<?php
/**
 * Output link for main site log in with redirect.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<a href="<?php echo esc_url(get_login_url()); ?>">👤 Logga in</a>