<?php
/**
 * Statistics for posts.
 * 
 * Will be improved with more data/options.
 * Will be improved to use custom database table.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📊 Just nu</h1>
<hr>
<p class="small">💡 Aktuella siffror.</p>

<?php 
$current_year = date('Y');

global $wpdb;

// Count current members
$member_args = [
    'role'       => 'member',
    'fields'     => 'ID',
];
$member_count = count(get_users($member_args));

// Define category IDs for the query
$category_ids = [1, 37]; // Replace with dynamic values if needed

// Count current posters
$current_query = "
    SELECT COUNT(DISTINCT p.post_author)
    FROM {$wpdb->prefix}posts p
    JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id
    JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    WHERE p.post_type = 'post' AND p.post_status = 'publish'
    AND tt.term_id IN (%d, %d)
";

// Prepare and execute the query
$current_count = $wpdb->get_var($wpdb->prepare($current_query, $category_ids[0], $category_ids[1]));

// Calculate percentage
$current_percentage = ($member_count > 0) ? round(($current_count / $member_count) * 100) : 0;
?>

<!-- Output the counts -->
<div class="columns"><div class="column1"><h7>🎁 Aktuella annonser</h7></div>
<div class="column2 bottom">Just nu</div></div>
<hr>

<p>
    <span class="big-label">👤 <?php echo number_format($current_count); ?> medlemmar</span> har aktuella annonser = 
	<span class="label"><?php echo $current_percentage; ?>%</span> av alla <?php echo number_format($member_count); ?> medlemmar
</p>