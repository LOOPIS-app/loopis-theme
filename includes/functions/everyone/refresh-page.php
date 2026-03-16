<?php
/**
 * Function to refresh the page, always available 
 *
 * Included for everyone in functions.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function refresh_page() {
    echo "<meta http-equiv='refresh' content='0'>";
}