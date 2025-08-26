<?php
/**
 * Borrow days.
 *
 * Used in:
 * archive.php
 * search.php
 * single-borrow.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get field
$days = get_post_meta(get_the_ID(), 'days', true);
if ($days) { 
    echo " {$days} dygn";
    } else { 
        echo "? dygn"; 
    }
