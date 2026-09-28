<?php
/**
 * Show count for pending members in admin dashboard
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get users with pending member role
$users = get_users([
    'role__in' => ['member_pending'],
    'blog_id' => get_current_blog_id(),
]);

$count = count($users);

// Output
if ($count == 0) {
    echo '💢 0 ej aktiverade';
} else {
    echo '⏳ ' . $count . ' ej aktiverade';
}