<?php
/**
 * Recurring functions.
 *
 * Included for everyone in functions.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** 
* Refresh page
*/	
function refresh_page() {
    echo "<meta http-equiv='refresh' content='0'>";
}