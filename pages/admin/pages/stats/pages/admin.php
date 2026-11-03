<?php
/**
 * Statistics for admin tasks.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/admin.png" alt="Admin" class="symbol"> Admin</h1>
<hr>
<p class="small">💡 Statistik för admin-uppgifter</p>

<?php
// Render dropdown and get the selected year
include_once LOOPIS_THEME_DIR . '/pages/admin/functions/stats_select_year.php';
$stats_selection = stats_select_year();
$selected_year = $stats_selection['selected_year'];
$days_passed = $stats_selection['days_passed'];

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
    'category__in'   => loopis_cat('disappeared'),
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$disappeared_query = new WP_Query($disappeared_args);
$disappeared_count = $disappeared_query->found_posts;

// Number of gifts extracted 
$extracted_args = array(
    'post_type'      => 'post',
    'category__in'   => loopis_cat('extracted'),
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$extracted_query = new WP_Query($extracted_args);
$extracted_count = $extracted_query->found_posts;

// Number of gifts complaints
$complaint_args = array(
    'post_type'      => 'post',
    'category__in'   => loopis_cat('complaint'),
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$complaint_query = new WP_Query($complaint_args);
$complaint_count = $complaint_query->found_posts;


// Statistics for support posts
$support_per_day = ($days_passed > 0) ? round($support_count / $days_passed, 2) : 0;

// Stats for disappeared
$disappeared_percentage = ($total_count > 0) ? round(($disappeared_count / $total_count) * 100) : 0;
$disappeared_per_day = ($days_passed > 0) ? round($disappeared_count / $days_passed, 2) : 0;

// Stats for extracted
$extracted_percentage = ($total_count > 0) ? round(($extracted_count / $total_count) * 100) : 0;
$extracted_per_day = ($days_passed > 0) ? round($extracted_count / $days_passed, 2) : 0;

// Stats for complaint
$complaint_percentage = ($total_count > 0) ? round(($complaint_count / $total_count) * 100) : 0;
$complaint_per_day = ($days_passed > 0) ? round($complaint_count / $days_passed, 2) : 0;


// Adjust output of all years
if ($selected_year === 'all') { $selected_year = "Alla år"; }
?>


<!-- Output the counts -->
<div class="columns"><div class="column1"><h3>🛟 Support-ärenden</h3></div>
<div class="column2"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>

<p><span class="big-label">🙋 <?php echo $support_count; ?> skapade</span> = <span class="big-label"><?php echo number_format($support_per_day, 2); ?> per dag</span></p>


<!-- Output the counts -->
<div class="columns"><div class="column1"><h3>❤️‍🩹 Saker på vift</h3></div>
<div class="column2"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>

<p><span class="big-label">💢 <?php echo $disappeared_count; ?> försvunna</span> = <span class="big-label"><?php echo number_format($disappeared_per_day, 2); ?> per dag = <span class="big-label"><?php echo $disappeared_percentage; ?>% av alla skapade</span></p>
<p><span class="big-label">🧹 <?php echo $extracted_count; ?> bortplockade</span> = <span class="big-label"><?php echo number_format($extracted_per_day, 2); ?> per dag = <span class="big-label"><?php echo $extracted_percentage; ?>% av alla skapade</span></p>
<p><span class="big-label">💩 <?php echo $complaint_count; ?> klagomål</span> = <span class="big-label"><?php echo number_format($complaint_per_day, 2); ?> per dag = <span class="big-label"><?php echo $complaint_percentage; ?>% av alla skapade</span></p>
