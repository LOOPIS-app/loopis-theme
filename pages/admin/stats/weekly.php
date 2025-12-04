<?php
/**
 * Statistics for a selected week.
 * 
 * Will be improved to use generic functions.
 * Will be improved to use custom database table.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📊 Vecka</h1>
<hr>
<p class="small">💡 Statistik för måndagsbrevet</p>

<?php
// Set the default year to the current year
$current_year = date('Y');
$selected_year = $current_year;

// Set the default week to the current week
$current_week = date('W');
$selected_week = $current_week;

// Set the default endadate to today

// Check if a year and week are selected from the dropdowns
if (isset($_POST['selected_year']) && isset($_POST['selected_week'])) {
    // Validate and sanitize the selected year and week values
    $selected_year = absint($_POST['selected_year']);
    $selected_week = absint($_POST['selected_week']);
}

// Calculate the number of days passed in the selected week
if ($selected_year == $current_year && $selected_week == $current_week) {
    // Current week
    $start_date = new DateTime();
    $start_date->setISODate($selected_year, $selected_week, 1); // Start from Monday of the selected week
    $current_date = new DateTime();
    $today = $current_date->format('N'); // Current day of the week (1 - Monday, 7 - Sunday)
    $days_passed = min($today, 7);
	$end_date = new DateTime(); // Set $end_date to the current date
} elseif ($selected_year == $current_year && $selected_week < $current_week) {
    // Previous week in the current year
    $start_date = new DateTime();
    $start_date->setISODate($selected_year, $selected_week, 1); // Start from Monday of the selected week
    $end_date = clone $start_date;
    $end_date->modify('+6 days');

    // Check if the week spans across different months
    if ($start_date->format('n') !== $end_date->format('n')) {
        $start_month = $start_date->format('n'); // Month of the start date
        $end_month = $end_date->format('n'); // Month of the end date

        // Calculate the number of days in the start month
        $start_month_days = (int) $start_date->format('t');

        // Calculate the number of days in the end month
        $end_month_days = (int) $end_date->format('t');

        // Calculate the number of days passed in the selected week
        $days_passed = $start_month_days - $start_date->format('j') + 1;

        // Add the days in the end month
        $days_passed += $end_date->format('j');
    } else {
        $days_passed = $end_date->format('d') - $start_date->format('d') + 1;
    }
} else {
    // Future week or a week in a previous year
    $days_passed = 0;
}

?>

<!-- Dropdown select year and week -->
<form method="post">
    <label for="year">Select a year:</label>
    <select name="selected_year" id="year">
        <?php
        // Generate options for the year dropdown
        $earliest_year = 2023;
        for ($year = $current_year; $year >= $earliest_year; $year--) {
            $selected = ($year == $selected_year) ? 'selected' : '';
            echo "<option value=\"$year\" $selected>$year</option>";
        }
        ?>
    </select>

    <label for="week">Select a week:</label>
    <select name="selected_week" id="week">
        <?php
        // Generate options for the week dropdown
        $total_weeks = 52;
        for ($week = 1; $week <= $total_weeks; $week++) {
            $selected = ($week == $selected_week) ? 'selected' : '';
            echo "<option value=\"$week\" $selected>Week $week</option>";
        }
        ?>
    </select>
    <button type="submit">Submit</button>
</form>

<?php
// Exclude the board?
$board_ids = array(2, 3, 61, 66);


// Number of new user accounts created
$new_users_args = array(
    'role'           => 'Member',
    'number'         => -1,
    'date_query'     => array(
        array(
            'year'    => $selected_year,
            'week'    => $selected_week,
            'compare' => '=',
        ),
    ),
);

$new_users_query = new WP_User_Query($new_users_args);
$new_users_count = $new_users_query->get_total(); 


// Number of support posts created
$support_args = array(
    'post_type'      => 'support',
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'year'  => $selected_year,
            'week'  => $selected_week,
            'compare' => '=',
        ), ), );

$support_query = new WP_Query($support_args);
$support_count = $support_query->found_posts;


// Number of booking posts created
$booking_args = array(
    'post_type'      => 'booking',
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'year'  => $selected_year,
            'week'  => $selected_week,
            'compare' => '=',
        ), ), );

$booking_query = new WP_Query($booking_args);
$booking_count = $booking_query->found_posts;


// Number of posts created (board excluded)
$total_noboard_args = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'year'  => $selected_year,
            'week'  => $selected_week,
            'compare' => '=',
        ), ),
    'author__not_in' => $board_ids, );

$total_noboard_query = new WP_Query($total_noboard_args);
$total_noboard_count = $total_noboard_query->found_posts;


// Number of posts booked (board excluded)
$booked_noboard_args = array(
    'post_type'      => 'post',
    'category__in'   => array(41, 57, 104, 147),
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'year'  => $selected_year,
            'week'  => $selected_week,
            'compare' => '=',
        ), ), 
    'author__not_in' => $board_ids, );

$booked_noboard_query = new WP_Query($booked_noboard_args);
$booked_noboard_count = $booked_noboard_query->found_posts;


// Number of posts removed (board excluded)
$removed_noboard_args = array(
    'post_type'      => 'post',
    'category__in'   => 58,
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'year'  => $selected_year,
            'week'  => $selected_week,
            'compare' => '=',
        ), ), 
	'author__not_in' => $board_ids, );

$removed_noboard_query = new WP_Query($removed_noboard_args);
$removed_noboard_count = $removed_noboard_query->found_posts;


// Statistics for posts removed
if ( $removed_noboard_count > 0 ) { $removed_noboard_percentage = round(($removed_noboard_count / $total_noboard_count) * 100); } else { $removed_noboard_percentage = 0; }
if ( $removed_noboard_count > 0 ) { $removed_per_day_noboard = round($removed_noboard_count / $days_passed, 2); } else { $removed_per_day_noboard = 0; }

// Statistics for posts created
if ( $total_noboard_count > 0 ) { $posts_per_day_noboard = round($total_noboard_count / $days_passed, 2); } else { $posts_per_day_noboard = 0; }

// Statistics for posts booked
if ( $booked_noboard_count > 0 ) { $booked_per_day_noboard = round($booked_noboard_count / $days_passed, 2); } else { $booked_per_day_noboard = 0; }
if ( $booked_noboard_count > 0 ) { $booked_noboard_percentage = round(($booked_noboard_count / $total_noboard_count) * 100); } else { $booked_noboard_percentage = 0; }
?>

<!-- Output the counts -->

<div class="columns"><div class="column1"><h2>📊 Statistik v.<?php echo $selected_week; ?></h2></div>
<div class="column2 bottom"><?php echo $days_passed; ?> dagar</div></div>
<hr>
<p class="small">💡 Statistik för perioden <?php echo date("Y-m-d", strtotime($start_date->format('Y-m-d'))); ?> till <?php echo date("Y-m-d", strtotime($end_date->format('Y-m-d'))); ?> – utan styrelsens annonser</p>

<div class="columns"><div class="column1"><h3>🎁 Annonser</h3></div>
<div class="column2 bottom"><?php echo $days_passed; ?> dagar</div></div>
<hr>
<p><span class="big-label">💚 <?php echo $total_noboard_count; ?> skapade</span> = <span class="label"><?php echo number_format($posts_per_day_noboard, 2); ?> per dag</span></p>
<p><span class="big-label">❤ <?php echo $booked_noboard_count; ?> paxade</span> = <span class="label"><?php echo number_format($booked_per_day_noboard, 2); ?> per dag</span> = <span class="label"><?php echo $booked_noboard_percentage; ?>% av de skapade</span></p>
<p><span class="big-label">❌ <?php echo $removed_noboard_count; ?> borttagna</span> = <span class="label"><?php echo number_format($removed_per_day_noboard, 2); ?> per dag</span> = <span class="label"><?php echo $removed_noboard_percentage; ?>% av de skapade</span></p>

<div class="columns"><div class="column1"><h3>🗓 Bokningar av lån</h3></div>
<div class="column2 bottom"><?php echo $days_passed; ?> dagar</div></div>
<hr>
<p><span class="big-label">🔄 <?php echo $booking_count; ?> nya bokningar</span></p>

<div class="columns"><div class="column1"><h3>🛠 Support & feedback</h3></div>
<div class="column2 bottom"><?php echo $days_passed; ?> dagar</div></div>
<hr>
<p><span class="big-label">⚠ <?php echo $support_count; ?> nya ärenden</span></p>

<div class="columns"><div class="column1"><h3>👤 Medlemmar</h3></div>
<div class="column2 bottom"><?php echo $days_passed; ?> dagar</div></div>
<hr>
<p><span class="big-label">🎉 <?php echo $new_users_count; ?> nya medlemmar</span></p>