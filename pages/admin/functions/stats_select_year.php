<?php
/**
 * Render the statistics year selector and return its selected year and day count.
 *
 * @return array{selected_year: int|string, days_passed: int}
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


function stats_select_year(): array {
    global $wpdb;

    // Get the current year
    $current_year = (int) current_time('Y');

    // Get the registration date of current blog
    $registration = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT registered FROM {$wpdb->blogs} WHERE blog_id = %d",
            get_current_blog_id()
        )
    );
    $registration_date = $registration
        ? new DateTimeImmutable(substr($registration, 0, 10))
        : new DateTimeImmutable('2023-01-01');
    $registration_year = (int) $registration_date->format('Y');
    
    // Use registration year if available, otherwise default to 2023
    $earliest_year = $registration_year > 0 ? $registration_year : 2023;
    $selected_year = $current_year; // Default selected year to current year

    // Check if the form was submitted
    if (isset($_POST['selected_year']) && is_scalar($_POST['selected_year'])) {
        // Validate and sanitize the selected year value
        $requested_year = sanitize_text_field(wp_unslash($_POST['selected_year']));
        if ($requested_year === 'all') {
            $selected_year = 'all'; // User selected "Alla år"
        } else {
            $requested_year = absint($requested_year);
            if ($requested_year >= $earliest_year && $requested_year <= $current_year) {
                $selected_year = $requested_year;
            }
        }
    }

    // Count days passed for selected period
    $today = new DateTimeImmutable(current_time('Y-m-d'));
    if ($selected_year === 'all') {
        $start_date = $registration_date;
        $end_date = $today;
    } else {
        $start_date = new DateTimeImmutable(sprintf('%04d-01-01', $selected_year));
        if ($selected_year === $registration_year && $registration_date > $start_date) {
            $start_date = $registration_date;
        }
        $end_date = ($selected_year === $current_year)
            ? $today
            : new DateTimeImmutable(sprintf('%04d-01-01', $selected_year + 1));
    }

    $days_passed = (int) $start_date->diff($end_date)->format('%a');

    // Account for test periods in Bagarmossen.
    $days_info = '';
    if ($selected_year === 'all' && get_current_blog_id() === 2) {
        $days_passed = max(0, $days_passed - 327);
        $days_info = '⚠ Antal dagar för "Alla år" i Bagarmossen är anpassat (-327) eftersom det var ett pilotprojekt.';
    } elseif ($selected_year === 2024 && get_current_blog_id() === 2) {
        $days_passed = 357;
        $days_info = '⚠ Antal dagar för 2024 i Bagarmossen är anpassat (-8) eftersom LOOPIS öppnade 9 januari.';
    } elseif ($selected_year === 2023) {
        $days_passed = 30;
        $days_info = '⚠ Antal dagar för 2023 i Bagarmossen är anpassat (30) eftersom LOOPIS bara testades 2x15 dagar.';
    } elseif ($selected_year === 'all') {
        $days_info = '💡 Antal dagar för "Alla år" räknas från områdets start ' .
            esc_html($registration_date->format('Y-m-d')) . '.';
    }

    // Render the dropdown menu
    ?>
    <div class="loopis-form loopis-filter">
    <form method="post">
        <select name="selected_year" id="year" style="float:left; font-size:16px;">
            <option value="all" <?php selected($selected_year, 'all'); ?>>Alla år</option>
            <?php
            // Generate options for the dropdown
            for ($year = $current_year; $year >= $earliest_year; $year--) {
                ?>
                <option value="<?php echo esc_attr($year); ?>" <?php selected($selected_year, $year); ?>>
                    <?php echo esc_html($year); ?>
                </option>
                <?php
            }
            ?>
        </select>
        <button type="submit" class="small" style="margin:3px 0 0 10px;">Välj år</button>
    </form>
    </div>
    <?php

    // Output information about adjustments
    if ($days_info !== '') {
        echo '<p class="info">' . $days_info . '</p>';
    }

    // Return the selected year and the number of days passed for the period
    return array(
        'selected_year' => $selected_year,
        'days_passed'   => $days_passed,
    );
}