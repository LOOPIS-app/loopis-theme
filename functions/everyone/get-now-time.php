<?php
/**
 * Helper functions for getting the current time.
 * Used because current_time('timestamp') did not work correctly in some cases.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function get_now_time() {
    $now_time = (new DateTime(current_time('mysql')))->getTimestamp();
    return $now_time;
}