<?php
/**
 * Statistics for posts.
 * 
 * Will be improved to use generic functions.
 * Will be improved to use custom database table.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🛟 Support</h1>
<hr>
<p class="small">💡 Statistik för support & problem</p>

<?php
// Set current year (to avoid undefined variable)
$current_year = date('Y');

// Render dropdown and get the selected year
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/stats_select_year.php';
$selected_year = stats_select_year();

// Calculate days passed and output message
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/stats_days_passed.php';
$days_passed = stats_days_passed($selected_year); 

// Exclude the board?
$board_ids = array(2, 3, 66);

// Function to build date query
function build_date_query($selected_year) {
    if ($selected_year === 'all') {
        return array(); // No date query for all years
    }
    return array(
        array(
            'year' => $selected_year,
        ),
    );
}

// Number of support posts created
$support_args = array(
    'post_type'      => 'support',
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$support_query = new WP_Query($support_args);
$support_count = $support_query->found_posts;

// Number of posts created
$total_args = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$total_query = new WP_Query($total_args);
$total_count = $total_query->found_posts;

// Number of gifts disappeared 
$disappeared_args = array(
    'post_type'      => 'post',
    'category__in'   => 156,
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$disappeared_query = new WP_Query($disappeared_args);
$disappeared_count = $disappeared_query->found_posts;


// Statistics for support posts created
$support_per_day = ($days_passed > 0) ? round($support_count / $days_passed, 2) : 0;

// Statistics for posts in disappeared category
$disappeared_percentage = ($total_count > 0) ? round(($disappeared_count / $total_count) * 100) : 0;
$disappeared_per_day = ($days_passed > 0) ? round($disappeared_count / $days_passed, 2) : 0;

// Adjust output of all years
if ($selected_year === 'all') { $selected_year = "Alla år"; }
?>


<!-- Output the counts -->
<div class="columns"><div class="column1"><h3>🛟 Support-ärenden</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>

<p><span class="big-label">🙋 <?php echo $support_count; ?> skapade</span> = <span class="big-label"><?php echo number_format($support_per_day, 2); ?> per dag</span></p>


<!-- Output the counts -->
<div class="columns"><div class="column1"><h3>❤️‍🩹 Försvunna saker</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>

<p><span class="big-label">💢 <?php echo $disappeared_count; ?> försvunna</span> = <span class="big-label"><?php echo number_format($disappeared_per_day, 2); ?> per dag = <span class="big-label"><?php echo $disappeared_percentage; ?>% av alla skapade</span></p> </span></p>