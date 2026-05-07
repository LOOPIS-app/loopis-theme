<?php
/**
 * Statistics options: Dropdown for selecting year
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


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