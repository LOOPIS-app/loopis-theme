<?php
/**
 * Show count for pending members in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get users with pending member role
$users = get_users([
    'role__in' => ['member_pending']
]);

$count = count($users);

// Output
if ($count == 0) {
    echo '✅ 0 nya medlemmar';
} else {
    echo '⚠ ' . $count . ' ny';
    if ($count != 1) {
        echo 'a';
    }
    echo ' medlem';
    if ($count != 1) {
        echo 'mar!';
    }
}