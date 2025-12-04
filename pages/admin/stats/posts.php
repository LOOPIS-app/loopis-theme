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

<h1>📊 Annonser</h1>
<hr>
<p class="small">💡 Statistik för upplagda annonser</p>

<?php
// Set the default year to the current year
$current_year = date('Y');
$selected_year = $current_year;

// Check if a year is selected from the dropdown
if (isset($_POST['selected_year'])) {
    // Validate and sanitize the selected year value
    $selected_year = $_POST['selected_year'] === 'all' ? 'all' : absint($_POST['selected_year']);
}

// Calculate the number of days passed
if ($selected_year === 'all') {
    // Calculate total days from the start of 2023 to today
    $start_date = new DateTime('2023-01-01');
    $current_date = new DateTime();
    $interval = $start_date->diff($current_date);
    $days_passed = $interval->format('%a');
} elseif ($selected_year == $current_year) {
    $start_date = new DateTime($selected_year . '-01-01');
    $current_date = new DateTime();
    $interval = $start_date->diff($current_date);
    $days_passed = $interval->format('%a');
} else {
    $start_date = new DateTime($selected_year . '-01-01');
    $end_date = new DateTime(($selected_year + 1) . '-01-01');
    $interval = $start_date->diff($end_date);
    $days_passed = $interval->format('%a');
}

?>

<!-- Dropdown select year -->
<form method="post">
    <select name="selected_year" id="year" style="float:left; font-size:16px;">
        <option value="all" <?php echo ($selected_year === 'all') ? 'selected' : ''; ?>>Alla år</option>
        <?php
        // Generate options for the dropdown
        $earliest_year = 2023;
        for ($year = $current_year; $year >= $earliest_year; $year--) {
            $selected = ($year == $selected_year) ? 'selected' : '';
            echo "<option value=\"$year\" $selected>$year</option>";
        }
        ?>
    </select>
    <button type="submit" class="small" style="margin:3px 0 0 10px;">Visa</button>
</form>

<div style="clear:both;">
<?php
// Justeringar av antal dagar
    if ($selected_year == 2024) {
        $days_passed -= 8;
        echo '<p class="info">⚠ Antal dagar för 2024 är anpassat (-8) eftersom LOOPIS öppnade 9 januari.</p>';
    }
    elseif ($selected_year == 2023) {
        $days_passed = 30;
        echo '<p class="info">⚠ Antal dagar för 2023 är 30 eftersom LOOPIS bara testades 2x15 dagar.</p>';
    }
	elseif ($selected_year === 'all') {
        $days_passed = $days_passed - 8 - 335;
        echo '<p class="info">⚠ Antal dagar för är anpassat (-327) pga test 2023 och öppning 2024.</p>';
    }
?>
</div>

<!--?php
// Output (for testing)
echo "Selected Year: $selected_year<br>";
echo "Days Passed in Selected Year: $days_passed<br>";
?-->

<?php
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

// Number of posts created
$total_args = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$total_query = new WP_Query($total_args);
$total_count = $total_query->found_posts;

// Number of posts created (board excluded)
$total_noboard_args = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
    'author__not_in' => $board_ids,
);

$total_noboard_query = new WP_Query($total_noboard_args);
$total_noboard_count = $total_noboard_query->found_posts;

// Number of forwarded posts
$forwarded_args = array(
    'post_type'      => 'post',
    'meta_key'       => 'previous_post',
    'meta_query'     => array(
        array(
            'key'     => 'previous_post',
            'value'   => '',
            'compare' => '!=',
        ),
    ),
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$forwarded_query = new WP_Query($forwarded_args);
$forwarded_count = $forwarded_query->found_posts;

// Number of forwarded posts fetched
$forwarded_fetched_args = array(
    'post_type'      => 'post',
    'category__in'   => 41,
    'meta_key'       => 'previous_post',
    'meta_query'     => array(
        array(
            'key'     => 'previous_post',
            'value'   => '',
            'compare' => '!=',
        ),
    ),
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$forwarded_fetched_query = new WP_Query($forwarded_fetched_args);
$forwarded_fetched_count = $forwarded_fetched_query->found_posts;

// Number of posts booked
$booked_args = array(
    'post_type'      => 'post',
    'category__in'   => array(41, 57, 106, 104),
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$booked_query = new WP_Query($booked_args);
$booked_count = $booked_query->found_posts;

// Number of posts booked (board excluded)
$booked_noboard_args = array(
    'post_type'      => 'post',
    'category__in'   => array(41, 57, 106, 104),
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
    'author__not_in' => $board_ids,
);

$booked_noboard_query = new WP_Query($booked_noboard_args);
$booked_noboard_count = $booked_noboard_query->found_posts;

// Number of posts fetched
$fetched_args = array(
    'post_type'      => 'post',
    'category__in'   => 41,
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$fetched_query = new WP_Query($fetched_args);
$fetched_count = $fetched_query->found_posts;

// Number of posts fetched (board excluded)
$fetched_noboard_args = array(
    'post_type'      => 'post',
    'category__in'   => 41,
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
    'author__not_in' => $board_ids,
);

$fetched_noboard_query = new WP_Query($fetched_noboard_args);
$fetched_noboard_count = $fetched_noboard_query->found_posts;

// Number of posts removed
$removed_args = array(
    'post_type'      => 'post',
    'category__in'   => 58,
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$removed_query = new WP_Query($removed_args);
$removed_count = $removed_query->found_posts;

// Number of booked posts with raffle_date
$raffle_args = array(
    'post_type'      => 'post',
    'category__in'   => array(41, 57, 106, 104),
    'meta_key'       => 'raffle_date',
    'meta_value'     => '',
    'meta_compare'   => 'EXISTS',
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$raffle_query = new WP_Query($raffle_args);
$raffle_count = $raffle_query->found_posts;

// Number of fetched posts with location 'Skåpet'
$locker_args = array(
    'post_type'      => 'post',
    'category__in'   => 41,
    'meta_key'       => 'location',
    'meta_value'     => 'Skåpet',
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$locker_query = new WP_Query($locker_args);
$locker_count = $locker_query->found_posts;

// Number of fetched posts with location 'LOOPIS-bord'
$event_args = array(
    'post_type'      => 'post',
    'category__in'   => 41,
    'meta_key'       => 'location',
    'meta_value'     => 'LOOPIS-bord',
    'posts_per_page' => -1,
    'date_query'     => build_date_query($selected_year),
);

$event_query = new WP_Query($event_args);
$event_count = $event_query->found_posts;

// Statistics for posts forwarded
$forwarded_percentage = ($total_count > 0) ? round(($forwarded_count / $total_count) * 100) : 0;
$forwarded_per_day = ($days_passed > 0) ? round($forwarded_count / $days_passed, 2) : 0;

// Statistics for posts forwarded and fetched
$forwarded_fetched_percentage = ($forwarded_count > 0) ? round(($forwarded_fetched_count / $forwarded_count) * 100) : 0;
$forwarded_fetched_per_day = ($days_passed > 0) ? round($forwarded_fetched_count / $days_passed, 2) : 0;

// Statistics for posts removed
$removed_percentage = ($total_count > 0) ? round(($removed_count / $total_count) * 100) : 0;
$removed_per_day = ($days_passed > 0) ? round($removed_count / $days_passed, 2) : 0;

// Statistics for posts created
$total_noboard_percentage = ($total_count - $removed_count > 0) ? round((($total_noboard_count - $removed_count) / ($total_count - $removed_count)) * 100) : 0;
$posts_per_day = ($days_passed > 0) ? round($total_count / $days_passed, 2) : 0;
$posts_per_day_noboard = ($days_passed > 0) ? round($total_noboard_count / $days_passed, 2) : 0;

// Statistics for posts booked
$booked_percentage = ($total_count > 0) ? round(($booked_count / $total_count) * 100) : 0;
$booked_per_day = ($days_passed > 0) ? round($booked_count / $days_passed, 2) : 0;
$booked_per_day_noboard = ($days_passed > 0) ? round($booked_noboard_count / $days_passed, 2) : 0;
$booked_noboard_percentage = ($total_noboard_count > 0) ? round(($booked_noboard_count / $total_noboard_count) * 100) : 0;

// Statistics for posts fetched
$fetched_percentage = ($total_count > 0) ? round(($fetched_count / $total_count) * 100) : 0;
$fetched_per_day = ($days_passed > 0) ? round($fetched_count / $days_passed, 2) : 0;
$fetched_per_day_noboard = ($days_passed > 0) ? round($fetched_noboard_count / $days_passed, 2) : 0;
$fetched_noboard_percentage = ($total_noboard_count > 0) ? round(($fetched_noboard_count / $total_noboard_count) * 100) : 0;

// Statistics for posts fetched in locker
$locker_percentage = ($fetched_count > 0) ? round(($locker_count / $fetched_count) * 100) : 0;
$locker_per_day = ($days_passed > 0) ? round($locker_count / $days_passed, 2) : 0;

// Statistics for posts fetched in locker
$event_percentage = ($fetched_count > 0) ? round(($event_count / $fetched_count) * 100) : 0;

// Statistics for posts fetched on custom location
$custom_count = ($fetched_count - $locker_count - $event_count);
$custom_percentage = ($fetched_count > 0) ? round(($custom_count / $fetched_count) * 100) : 0;
$custom_per_day = ($days_passed > 0) ? round($custom_count / $days_passed, 2) : 0;

// Statistics for posts booked
$raffle_percentage = ($booked_count > 0) ? round(($raffle_count / $booked_count) * 100) : 0;
$raffle_per_day = ($days_passed > 0) ? round($raffle_count / $days_passed, 2) : 0;
$first_count = ($booked_count - $raffle_count);
$first_percentage = ($booked_count > 0) ? round(($first_count / $booked_count) * 100) : 0;
$first_per_day = ($days_passed > 0) ? round($first_count / $days_passed, 2) : 0;

// Adjust output of all years
if ($selected_year === 'all') { $selected_year = "Alla år"; }
?>


<!-- Output the counts -->
<div class="columns"><div class="column1"><h3>🎁 Annonser</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>

<p><span class="big-label">⬆ <?php echo $total_count; ?> skapade</span> = <span class="big-label"><?php echo number_format($posts_per_day, 2); ?> per dag</span></p>

<p class="small"><i class="fas fa-share" style="color: #999;"></i> <?php echo $forwarded_count; ?> återskapade = <?php echo number_format($forwarded_per_day, 2); ?> per dag = <?php echo $forwarded_percentage; ?>% av alla skapade</p>
<p class="small">❌ <?php echo $removed_count; ?> borttagna = <?php echo number_format($removed_per_day, 2); ?> per dag = <?php echo $removed_percentage; ?>% av alla skapade</p>

<div class="columns"><div class="column1"><h3>🙋‍♀️ Paxningar</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<p><span class="big-label">❤ <?php echo $booked_count; ?> paxade</span> = <span class="big-label"><?php echo number_format($booked_per_day, 2); ?> per dag</span> = <span class="big-label"><?php echo $booked_percentage; ?>% av alla skapade</span></p>
<p class="small">🎲 <?php echo $raffle_count; ?> paxade genom lottning = <?php echo number_format($raffle_per_day, 2); ?> per dag = <?php echo $raffle_percentage; ?>% av alla paxade</p>
<p class="small">🟢 <?php echo $first_count; ?> paxade först till kvarn = <?php echo number_format($first_per_day, 2); ?> per dag = <?php echo $first_percentage; ?>% av alla paxade</p>

<div class="columns"><div class="column1"><h3>♻ Hämtningar</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<p><span class="big-label">☑ <?php echo $fetched_count; ?> hämtade</span> = <span class="big-label"><?php echo number_format($fetched_per_day, 2); ?> per dag</span> = <span class="big-label"><?php echo $fetched_percentage; ?>% av alla skapade</span></p>
<p class="small"><i class="fas fa-share" style="color: #999;"></i> <?php echo $forwarded_fetched_count; ?> återskapade = <?php echo number_format($forwarded_fetched_per_day, 2); ?> per dag = <?php echo $forwarded_fetched_percentage; ?>% av alla återskapade</p>
<p class="small">⏹️ <?php echo $locker_count; ?> hämtade i skåpet = <?php echo number_format($locker_per_day, 2); ?> per dag = <?php echo $locker_percentage; ?>% av alla hämtade</p>
<p class="small">📍<?php echo $custom_count; ?> hämtade på annan adress = <?php echo number_format($custom_per_day, 2); ?> per dag = <?php echo $custom_percentage; ?>% av alla hämtade</p>
<p class="small">🌳 <?php echo $event_count; ?> hämtade på LOOPIS-bord = <?php echo $event_percentage; ?>% av alla hämtade</p>

<div class="columns"><div class="column1"><h3>👤 Utan styrelsen</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<p><span class="big-label">⬆ <?php echo $total_noboard_count; ?> skapade</span> = <span class="big-label"><?php echo number_format($posts_per_day_noboard, 2); ?> per dag</span> = <span class="big-label"><?php echo $total_noboard_percentage; ?>% av alla skapade</span></p>
<p><span class="big-label">❤ <?php echo $booked_noboard_count; ?> paxade</span> = <span class="big-label"><?php echo number_format($booked_per_day_noboard, 2); ?> per dag</span> = <span class="big-label"><?php echo $booked_noboard_percentage; ?>% av de skapade</span></p>
<p><span class="big-label">☑ <?php echo $fetched_noboard_count; ?> hämtade</span> = <span class="big-label"><?php echo number_format($fetched_per_day_noboard, 2); ?> per dag</span> = <span class="big-label"><?php echo $fetched_noboard_percentage; ?>% av de skapade</span></p>