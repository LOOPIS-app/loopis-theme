<?php
/**
 * Activity page alert for member.
 *
 * Used in:
 * locker-leave.php
 * comments.php
 * gift-admin.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get time
$now_time = (new DateTime(current_time('mysql')))->getTimestamp();
$expiration = strtotime(get_field('book_date')) + 24 * 3600; // Calculate expiration time in seconds
$remaining = $expiration - $now_time; // Calculate remaining time in seconds

// Output
    if ($remaining > 0) {
        $hours_left = ceil($remaining / 3600); // Calculate remaining time in hours (rounded up)
        if ($hours_left === 1) {
            echo "⌛ 1 timme kvar";
        } else {
            echo "⌛ $hours_left timmar kvar";
        }
		
    } else {
        $hours_ago = -1 * floor($remaining / 3600); // Calculate elapsed time in hours (rounded down)
        if ($hours_ago === 1) {
            echo "⚠ 1 timme sen!";
        } else {
            echo "⚠ $hours_ago timmar sen!";
        }
    }