<?php
/**
 * Statistics for members.
 * 
 * Will be improved to use generic functions.
 * Will be improved to use custom database table.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📊 Topplistor</h1>
<hr>
<p class="small">💡 Här ser du topplistor för våra medlemmar.</p>

<?php
// Function to display top users
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/display_top_users.php';

// Define constants for fixed values
define('LOOPIS_FETCHED_CATEGORY_ID', 41); // Renamed for better clarity
define('LOOPIS_EARLIEST_YEAR', 2023);

// Set the default year to the current year
$current_year = date('Y');
$selected_year = $current_year;

// Check if a year is selected from the dropdown
if (isset($_POST['selected_year'])) {
    // Verify the nonce for security
    if (!isset($_POST['select_year_nonce']) || !wp_verify_nonce($_POST['select_year_nonce'], 'select_year_action')) {
        die('Security check failed.');
    }

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

// Prepare correction messages for days passed
$correction_message = '';
if ($selected_year == 2024) {
    $days_passed -= 8;
    $correction_message = '⚠ Antal dagar för 2024 är anpassat (-8) eftersom LOOPIS öppnade 9 januari.';
} elseif ($selected_year == 2023) {
    $days_passed = 30;
    $correction_message = '⚠ Antal dagar för 2023 är 30 eftersom LOOPIS bara testades 2x15 dagar.';
} elseif ($selected_year === 'all') {
    $days_passed = $days_passed - 8 - 335;
    $correction_message = '⚠ Antal dagar för är anpassat (-327) pga test 2023 och öppning 2024.';
}
?>

<!-- Dropdown select year -->
<form method="post">
    <?php wp_nonce_field('select_year_action', 'select_year_nonce'); ?>
    <select name="selected_year" id="year">
        <option value="all" <?php echo ($selected_year === 'all') ? 'selected' : ''; ?>>Alla år</option>
        <?php
        // Generate options for the dropdown
        for ($year = $current_year; $year >= LOOPIS_EARLIEST_YEAR; $year--) {
            $selected = ($year == $selected_year) ? 'selected' : '';
            echo "<option value=\"$year\" $selected>$year</option>";
        }
        ?>
    </select>
    <button type="submit" class="small">Visa</button>
</form>
<div style="clear:both;"></div>

<!-- Display correction message below the dropdown -->
<?php if (!empty($correction_message)) : ?>
    <p class="info"><?php echo $correction_message; ?></p>
<?php endif; ?>

<?php
global $wpdb;

// Cache key based on selected year
$cache_key = 'top_users_' . $selected_year . '_v5'; // Add versioning to the cache key

// Try to get cached results
$top_users_data = get_transient($cache_key);

if ($top_users_data === false) {
    // Determine the year condition
    $year_condition = ($selected_year === 'all') ? '' : $wpdb->prepare('AND YEAR(post_date) = %d', $selected_year);

    // Get top posters
    $top_posters_query = "
        SELECT post_author, COUNT(*) as post_count
        FROM {$wpdb->prefix}posts
        WHERE post_type = 'post' AND post_status = 'publish' $year_condition
        GROUP BY post_author
        ORDER BY post_count DESC
        LIMIT 20
    ";
    $top_posters = $wpdb->get_results($top_posters_query);

    // Get top givers
    $top_givers_query = "
        SELECT p.post_author, COUNT(DISTINCT p.ID) as post_count
        FROM {$wpdb->prefix}posts p
        INNER JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id
        INNER JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE p.post_type = 'post' AND p.post_status = 'publish' $year_condition AND tt.taxonomy = 'category' AND tt.term_id = %d
        GROUP BY p.post_author
        ORDER BY post_count DESC
        LIMIT 20
    ";
    $top_givers = $wpdb->get_results($wpdb->prepare($top_givers_query, LOOPIS_FETCHED_CATEGORY_ID));

    // Get top fetchers
    $top_fetchers_query = "
        SELECT pm.meta_value AS fetcher_id, COUNT(*) as fetcher_count
        FROM {$wpdb->prefix}postmeta pm
        JOIN {$wpdb->prefix}posts p ON pm.post_id = p.ID
        WHERE pm.meta_key = 'fetcher' AND p.post_type = 'post' AND p.post_status = 'publish' $year_condition
        GROUP BY pm.meta_value
        ORDER BY fetcher_count DESC
        LIMIT 20
    ";
    $top_fetchers = $wpdb->get_results($top_fetchers_query);

    // Combine fetchers and givers into "loopare"
    $loopare = [];
    foreach ($top_givers as $giver) {
        $loopare[$giver->post_author] = (object)[
            'user_id' => $giver->post_author,
            'combined_count' => $giver->post_count,
        ];
    }

    foreach ($top_fetchers as $fetcher) {
        $user_id = $fetcher->fetcher_id;
        if (isset($loopare[$user_id])) {
            $loopare[$user_id]->combined_count += $fetcher->fetcher_count;
        } else {
            $loopare[$user_id] = (object)[
                'user_id' => $user_id,
                'combined_count' => $fetcher->fetcher_count,
            ];
        }
    }

    // Sort loopare by combined count in descending order
    usort($loopare, function ($a, $b) {
        return $b->combined_count - $a->combined_count;
    });

    // Limit to top 20
    $loopare = array_slice($loopare, 0, 20);

// Combine data for "annonsörer" (givers/posters ratio)
$annonsorer = [];
foreach ($top_givers as $giver) {
    $user_id = $giver->post_author;
    $giver_count = $giver->post_count;

    // Find the corresponding poster count
    $poster_count = 0;
    foreach ($top_posters as $poster) {
        if ($poster->post_author == $user_id) {
            $poster_count = $poster->post_count;
            break;
        }
    }

    // Calculate the percentage (givers / posters) only if poster_count is greater than 0
    if ($poster_count > 0) {
        $percentage = ($giver_count / $poster_count) * 100;
        $annonsorer[] = (object)[
            'user_id' => $user_id,
            'percentage' => $percentage,
        ];
    } else {
        // Optionally handle the case where poster_count is 0 (e.g., skip or set percentage to 0)
        $annonsorer[] = (object)[
            'user_id' => $user_id,
            'percentage' => 0, // Default to 0% if no posters are found
        ];
    }
}

    // Sort annonsorer by percentage in descending order
    usort($annonsorer, function ($a, $b) {
        return $b->percentage - $a->percentage;
    });

    // Limit to top 20
    $annonsorer = array_slice($annonsorer, 0, 20);

    // Cache the results for 1 hour
    $top_users_data = [
        'top_posters' => $top_posters,
        'top_givers' => $top_givers,
        'top_fetchers' => $top_fetchers,
        'top_loopare' => $loopare,
        'top_annonsorer' => $annonsorer,
    ];
    set_transient($cache_key, $top_users_data, HOUR_IN_SECONDS);
}

// Use the cached data
$top_posters = $top_users_data['top_posters'];
$top_givers = $top_users_data['top_givers'];
$top_fetchers = $top_users_data['top_fetchers'];
$top_loopare = $top_users_data['top_loopare'];
$top_annonsorer = $top_users_data['top_annonsorer'];
?>

<!-- Output the counts -->
<div class="columns"><div class="column1"><h3>🌈 Topp-20 loopare</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<p class="small">💡 Medlemmar med högst antal hanterade saker (gett+fått).</p>
<?php display_top_users($top_loopare, '🌈'); ?>

<div class="columns"><div class="column1"><h3>⬆ Topp-20 skapare</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<p class="small">💡 Medlemmar med högst antal publicerade annonser.</p>
<?php display_top_users($top_posters, '⬆'); ?>

<div class="columns"><div class="column1"><h3>♻ Topp-20 annonsörer</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<p class="small">💡 Medlemmar med högst andel paxade annonser.</p>
<?php display_top_users($top_annonsorer, '♻', true); ?>

<div class="columns"><div class="column1"><h3>✅ Topp-20 givare</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<p class="small">💡 Medlemmar med högst antal paxade annonser.</p>
<?php display_top_users($top_givers, '✅'); ?>

<div class="columns"><div class="column1"><h3>☑ Topp-20 hämtare</h3></div>
<div class="column2 bottom"><?php echo $selected_year; ?> (<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<p class="small">💡 Medlemmar med högst antal hämtade saker.</p>
<?php display_top_users($top_fetchers, '☑'); ?>


<style>
/* CSS for consistent styling */
select#year {
    float: left;
    font-size: 16px;
}
button.small {
    margin: 3px 0 0 10px;
}
span.label {
    float: right;
}
</style>