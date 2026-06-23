<?php
/**
 * Statistics with charts.
 * 
 * Will be improved to use generic functions.
 * Will be improved to use custom database table.
 * Will be improved to use reusable scripts.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📊 Diagram</h1>
<hr>
<p class="small">💡 Genererar diagram från boken</p>



<?php
global $wpdb;
$clauses = [
    'event' => 'submitted',
    'post_id' => '',
    'user_id' => '',
    'blog_id' => '',
    'location' => 'Skåpet',
    'start' => '1969-01-01 00:00:00',
    'stop' => '2100-01-01 00:00:00',
]


$query = "
    SELECT 
        DATE_FORMAT(post_date, '%Y-%u') AS week,
        COUNT(ID) AS post_count
    FROM 
        {$wpdb->base_prefix}loopis_ledger
    WHERE 
        post_date >= %s AND post_type = 'post' AND post_status = 'publish'
        AND post_author NOT IN (" . implode(',', $excluded_user_ids) . ")
    GROUP BY 
        week
    ORDER BY 
        week ASC
";

