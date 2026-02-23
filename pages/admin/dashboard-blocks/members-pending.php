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
    echo '💢 0 väntande medlemmar';
} else {
    echo '⏳ ' . $count . ' som ej har betalat';
}