<?php
/**
 * Show link to admin area.
 *
 * Used in admin blocks.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Output
echo '<p><span class="rounded"><a href="'.esc_url(home_url("/admin/")).'">🐙 Admin</a></span></p>';