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

<h1>📊 Demografi</h1>
<hr>
<p class="small">💡 Statistik för våra medlemmar</p>

<?php
global $wpdb;

// Set the current year
$current_year = date('Y');

// Render dropdown and get the selected year
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/stats_select_year.php';
$selected_year = stats_select_year();

// Set the field ID and meta key for the gender dropdown field
$field_id = 27; // The field ID for the gender dropdown
$dropdown_meta_key = 'dropdown_options';

// Query the database to get the dropdown options
$dropdown_options = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->prefix}wpum_fieldmeta WHERE wpum_field_id = %d AND meta_key = %s",
        $field_id,
        $dropdown_meta_key
    )
);

// Initialize the gender labels mapping array
$gender_labels = [];

// If dropdown options are found, deserialize and build the mapping
if (!empty($dropdown_options)) {
    $options = maybe_unserialize($dropdown_options);
    if (is_array($options)) {
        foreach ($options as $option) {
            if (isset($option['value']) && isset($option['label'])) {
                $gender_labels[$option['value']] = $option['label'];
            }
        }
    }
}

// Add a default label for unspecified genders
$gender_labels['unspecified'] = 'Uppgift saknas';

// Determine the date range based on the selected year
if ($selected_year === 'all') {
    $fetch_date_start = "2023-01-01 00:00:00"; // Adjust this to the earliest year you want to include
    $fetch_date_end = "{$current_year}-12-31 23:59:59";
} else {
    $fetch_date_start = "{$selected_year}-01-01 00:00:00";
    $fetch_date_end = "{$selected_year}-12-31 23:59:59";
}

// Query to fetch active members (user IDs)
$active_members_query = "
    SELECT DISTINCT user_id 
    FROM (
        -- Givers: Users who created a post in category 41 in the selected year(s)
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id
        JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE p.post_type = 'post' AND p.post_status = 'publish'
        AND tt.term_id = 41 AND p.post_date BETWEEN %s AND %s

        UNION

        -- Fetchers: Users who are fetchers with a fetch_date in the selected year(s)
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date ON pm_fetcher.post_id = pm_date.post_id
        WHERE pm_fetcher.meta_key = 'fetcher'
        AND pm_date.meta_key = 'fetch_date'
        AND pm_date.meta_value BETWEEN %s AND %s
    ) AS active_users
";
$active_members = $wpdb->get_col($wpdb->prepare($active_members_query, $fetch_date_start, $fetch_date_end, $fetch_date_start, $fetch_date_end));

// Initialize gender counts
$gender_counts = array_fill_keys(array_keys($gender_labels), 0);
$total_gender_counts = array_fill_keys(array_keys($gender_labels), 0);

// Initialize an array to store users with unspecified gender
$unspecified_users = [];

// Fetch gender for each active member
foreach ($active_members as $user_id) {
    // Check if the user exists in the wp_users table
    $user_exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->prefix}users WHERE ID = %d",
            $user_id
        )
    );

    if (!$user_exists) {
        // Skip this user if they don't exist
        continue;
    }

    // Fetch the gender meta value
    $gender = get_user_meta($user_id, 'wpum_gender', true);
    $gender = strtolower($gender); // Normalize the gender value

    // Check if the gender exists in the mapping
    if (isset($gender_counts[$gender])) {
        $gender_counts[$gender]++;
    } else {
        // If gender is not specified or invalid, count as unspecified
        $gender_counts['unspecified']++;
        $unspecified_users[] = $user_id; // Add user ID to the unspecified list
    }
}

// Fetch all members created before or within the selected year
$registration_date_limit = ($selected_year === 'all') ? "{$current_year}-12-31 23:59:59" : "{$selected_year}-12-31 23:59:59";

$all_users = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT ID FROM {$wpdb->prefix}users WHERE user_registered <= %s",
        $registration_date_limit
    )
);

// Store the count of all users
$count_all_users = count($all_users);

foreach ($all_users as $user) {
    $gender = get_user_meta($user->ID, 'wpum_gender', true);
    $gender = strtolower($gender); // Normalize the gender value

    if (isset($total_gender_counts[$gender])) {
        $total_gender_counts[$gender]++;
    } else {
        $total_gender_counts['unspecified']++;
    }
}
?>
	
<!-- Output the Gender Counts -->
<div class="columns">
    <div class="column1"><h3>⚧ Kön</h3></div>
    <div class="column2 bottom"><?php echo $count_all_users; ?> medlemmar (<?php echo ($selected_year === 'all') ? 'Alla år' : $selected_year; ?>)</div>
</div>
<hr>

<table class="admin-table">
    <thead>
        <tr>
            <th>Gender</th>
            <th>Active</th>
            <th>Inactive</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($gender_labels as $gender_key => $gender_label) : ?>
            <?php
            // Calculate inactive members for each gender
            $active_count = isset($gender_counts[$gender_key]) ? $gender_counts[$gender_key] : 0;
            $total_count = isset($total_gender_counts[$gender_key]) ? $total_gender_counts[$gender_key] : 0;
            $inactive_count = $total_count - $active_count;
            ?>
            <tr>
                <td><?php echo $gender_label; ?></td>
                <td><?php echo $active_count; ?></td>
                <td><?php echo $inactive_count; ?></td>
                <td><?php echo $total_count; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
global $wpdb;

// Set the field ID and meta key for the area dropdown field
$field_id_area = 30; // The field ID for the area dropdown
$dropdown_meta_key_area = 'dropdown_options';

// Query the database to get the dropdown options for the area
$dropdown_options_area = $wpdb->get_var(
    $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->prefix}wpum_fieldmeta WHERE wpum_field_id = %d AND meta_key = %s",
        $field_id_area,
        $dropdown_meta_key_area
    )
);

// Initialize the area labels mapping array
$area_labels = [];

// If dropdown options are found, deserialize and build the mapping
if (!empty($dropdown_options_area)) {
    $options_area = maybe_unserialize($dropdown_options_area);
    if (is_array($options_area)) {
        foreach ($options_area as $option) {
            if (isset($option['value']) && isset($option['label'])) {
                $area_labels[$option['value']] = $option['label'];
            }
        }
    }
}

// Add a default label for unspecified areas
$area_labels['unspecified'] = 'Uppgift saknas';

// Initialize area counts
$area_counts = array_fill_keys(array_keys($area_labels), 0);
$total_area_counts = array_fill_keys(array_keys($area_labels), 0);

// Fetch all users created before or within the selected year
$registration_date_limit = ($selected_year === 'all') ? "{$current_year}-12-31 23:59:59" : "{$selected_year}-12-31 23:59:59";

$all_users_area = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT ID FROM {$wpdb->prefix}users WHERE user_registered <= %s",
        $registration_date_limit
    )
);

// Store the count of all users
$count_all_users = count($all_users_area);

// Count total users by area
foreach ($all_users_area as $user) {
    $area = get_user_meta($user->ID, 'wpum_area', true);
    $area = strtolower($area); // Normalize the area value

    if (isset($total_area_counts[$area])) {
        $total_area_counts[$area]++;
    } else {
        $total_area_counts['unspecified']++;
    }
}

// Fetch active members for the area
$active_members_area_query = "
    SELECT DISTINCT user_id 
    FROM (
        -- Givers: Users who created a post in category 41 in the selected year(s)
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id
        JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE p.post_type = 'post' AND p.post_status = 'publish'
        AND tt.term_id = 41 AND p.post_date BETWEEN %s AND %s

        UNION

        -- Fetchers: Users who are fetchers with a fetch_date in the selected year(s)
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date ON pm_fetcher.post_id = pm_date.post_id
        WHERE pm_fetcher.meta_key = 'fetcher'
        AND pm_date.meta_key = 'fetch_date'
        AND pm_date.meta_value BETWEEN %s AND %s
    ) AS active_users
";
$active_members_area = $wpdb->get_col($wpdb->prepare($active_members_area_query, $fetch_date_start, $fetch_date_end, $fetch_date_start, $fetch_date_end));

// Count active users by area
foreach ($active_members_area as $user_id) {
    $area = get_user_meta($user_id, 'wpum_area', true);
    $area = strtolower($area); // Normalize the area value

    if (isset($area_counts[$area])) {
        $area_counts[$area]++;
    } else {
        $area_counts['unspecified']++;
    }
}
?>

<!-- Output the Area Counts -->
<div class="columns">
    <div class="column1"><h3>📍 Områden</h3></div>
    <div class="column2 bottom"><?php echo $count_all_users; ?> medlemmar (<?php echo ($selected_year === 'all') ? 'Alla år' : $selected_year; ?>)</div>
</div>
<hr>

<table class="admin-table">
    <thead>
        <tr>
            <th>Area</th>
            <th>Active</th>
            <th>Inactive</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($area_labels as $area_key => $area_label) : ?>
            <?php
            // Calculate inactive members for each area
            $active_count = isset($area_counts[$area_key]) ? $area_counts[$area_key] : 0;
            $total_count = isset($total_area_counts[$area_key]) ? $total_area_counts[$area_key] : 0;
            $inactive_count = $total_count - $active_count;
            ?>
            <tr>
                <td><?php echo $area_label; ?></td>
                <td><?php echo $active_count; ?></td>
                <td><?php echo $inactive_count; ?></td>
                <td><?php echo $total_count; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>