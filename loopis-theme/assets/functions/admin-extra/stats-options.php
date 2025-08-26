<?php
/**
 * Statistics options functions for LOOPIS admin/user.
 *
 * Included in... not yet
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** 
* Dropdown for selecting year
*/
if (!function_exists('stats_select_year')) {
function stats_select_year() {
    $earliest_year = 2023; // Hardcoded earliest year
    $current_year = date('Y'); // Automatically fetch the current year
    $selected_year = $current_year; // Default to the current year

    // Check if the form was submitted
    if (isset($_POST['selected_year'])) {
        // Validate and sanitize the selected year value
        if ($_POST['selected_year'] === 'all') {
            $selected_year = 'all'; // User selected "Alla år"
        } else {
            $selected_year = absint($_POST['selected_year']); // Ensure it's a valid integer
        }
    }

    // Render the dropdown menu
    ob_start(); // Start output buffering
    ?>
    <form method="post">
        <select name="selected_year" id="year" style="float:left; font-size:16px;">
            <option value="all" <?php echo ($selected_year === 'all') ? 'selected' : ''; ?>>Alla år</option>
            <?php
            // Generate options for the dropdown
            for ($year = $current_year; $year >= $earliest_year; $year--) {
                $selected = ($year == $selected_year) ? 'selected' : '';
                echo "<option value=\"$year\" $selected>$year</option>";
            }
            ?>
        </select>
        <button type="submit" class="small" style="margin:3px 0 0 10px;">Välj år</button>
    </form>
<div style="clear:both;"></div>
    <?php
    echo ob_get_clean(); // Output the dropdown menu

    return $selected_year; // Return the selected year
}
}

/** 
* Calculate days passed.
*/	
if (!function_exists('stats_days_passed')) {
function stats_days_passed($selected_year = null) {
    $earliest_year = 2023; // Set earliest year here
    $current_year = date('Y');
    $days_passed = 0;
	
	echo '<div style="clear:both;">'; // Fix this linebreak with CSS later...

    if ($selected_year === 'all') {
        // Calculate total days from the start of the earliest year to today
        $start_date = new DateTime("$earliest_year-01-01");
        $current_date = new DateTime();
        $interval = $start_date->diff($current_date);
        $days_passed = $interval->format('%a');
        $days_passed = $days_passed - 8 - 335; // Adjust for specific cases
        echo '<p class="info">⚠ Antal dagar för är anpassat (-327) pga test 2023 och öppning 2024.</p>';
    } elseif ($selected_year == $current_year) {
        $start_date = new DateTime("$selected_year-01-01");
        $current_date = new DateTime();
        $interval = $start_date->diff($current_date);
        $days_passed = $interval->format('%a');
    } elseif ($selected_year == 2024) {
        $start_date = new DateTime("$selected_year-01-01");
        $current_date = new DateTime();
        $interval = $start_date->diff($current_date);
        $days_passed = $interval->format('%a') - 8; // Adjust for 2024
        echo '<p class="info">⚠ Antal dagar för 2024 är anpassat (-8) eftersom LOOPIS öppnade 9 januari.</p>';
    } elseif ($selected_year == 2023) {
        $days_passed = 30; // Fixed for 2023
        echo '<p class="info">⚠ Antal dagar för 2023 är 30 eftersom LOOPIS bara testades 2x15 dagar.</p>';
    } else {
        $start_date = new DateTime("$selected_year-01-01");
        $end_date = new DateTime(($selected_year + 1) . '-01-01');
        $interval = $start_date->diff($end_date);
        $days_passed = $interval->format('%a');
    }

	echo '</div>'; // Fix this linebreak with CSS later...

    return $days_passed; // Return only the number of days passed
}
}