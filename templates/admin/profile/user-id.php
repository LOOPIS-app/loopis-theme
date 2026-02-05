<?php
/**
 * Show user email (with link to edit for admins)
 *
 * Used in author.php & admin area
 * $user_id has to be passed from context!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Output
$label = '🔧 ID: ' . esc_html($user_id);

if (current_user_can('administrator')) {
    echo '<a href="' . esc_url(admin_url('user-edit.php?user_id=' . $user_id)) . '" onclick="return confirm(\'Vill du redigera i användaren i WP Admin?\')">' . $label . '</a>';
} else {
    echo $label;
}