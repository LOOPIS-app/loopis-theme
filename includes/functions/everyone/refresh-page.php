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
    $current_url = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '/';

    if (!headers_sent()) {
        wp_safe_redirect($current_url);
        exit;
    }

    // Fallback when headers are already sent: navigate again via GET.
    echo '<script>window.location.replace(' . wp_json_encode($current_url) . ');</script>';
    echo "<meta http-equiv='refresh' content='0'>";
}