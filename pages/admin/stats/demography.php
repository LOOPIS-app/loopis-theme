<?php
/**
 * Statistics for members.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📊 Demografi</h1>
<hr>
<p class="small">💡 Statistik för våra medlemmar</p>

<p>Användare med följande roller ingår:<br>
<span class="label">member</span> <span class="label">member_earlier</span> <span class="label">member_archived</span> <span class="label">member_outside</span></p>

<h4>🗓 Välj period</h4>
<hr>
<?php
// Check if the current user can update the database.
$can_populate_postarea = is_multisite()
    ? current_user_can('manage_network_users')
    : current_user_can('manage_options');

$postarea_update_output = '';

// Run the postarea population script only after an authorized form submission.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loopis_populate_postarea'])) {
    if (!$can_populate_postarea) {
        wp_die('Du saknar behörighet att uppdatera postområden.');
    }

    check_admin_referer('loopis_populate_postarea');

    ob_start();
    require __DIR__ . '/scripts/loopis-populate-postarea.php';
    $postarea_update_output = ob_get_clean();
}
?>

<?php
global $wpdb;

// Set the current year
$current_year = date('Y');

// Include only users with one of the member roles in the statistics.
$included_roles = [
    'member',
    'member_earlier',
    'member_archived',
    'member_outside',
];

$is_included_user = function ($user_id) use ($included_roles) {
    $user = get_userdata($user_id);

    return $user && !empty(array_intersect($included_roles, (array) $user->roles));
};

// Render dropdown and get the selected year
include_once LOOPIS_THEME_DIR . '/includes/functions/admin-extra/stats/stats_select_year.php';
$selected_year = stats_select_year();

// Build gender rows from the users' raw wpum_gender values.
$gender_labels = ['unspecified' => 'Uppgift saknas'];
$gender_translations = [
    'secret'    => 'Vill ej uppge',
    'female'    => 'Kvinna',
    'male'      => 'Man',
    'nonbinary' => 'Icke-binär',
];

// Determine the date range based on the selected year
if ($selected_year === 'all') {
    $fetch_date_start = "2023-01-01 00:00:00"; // Adjust this to the earliest year you want to include
    $fetch_date_end = "{$current_year}-12-31 23:59:59";
} else {
    $fetch_date_start = "{$selected_year}-01-01 00:00:00";
    $fetch_date_end = "{$selected_year}-12-31 23:59:59";
}
$fetcher_cat = loopis_cat('fetched');

// Query to fetch active members (user IDs)
$active_members_query = "
    SELECT DISTINCT user_id 
    FROM (
        -- Givers: Users who created a post in category fetched in the selected year(s)
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id
        JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE p.post_type = 'post' AND p.post_status = 'publish'
        AND tt.term_id = {$fetcher_cat} AND p.post_date BETWEEN %s AND %s

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

// Initialize gender counts.
$gender_counts = ['unspecified' => 0];
$total_gender_counts = ['unspecified' => 0];

// Fetch gender for each active member
foreach ($active_members as $user_id) {
    if (!$is_included_user($user_id)) {
        // Skip users outside the included member roles.
        continue;
    }

    $gender = trim((string) get_user_meta($user_id, 'wpum_gender', true));
    $gender_key = $gender !== '' ? strtolower($gender) : 'unspecified';
    $gender_labels[$gender_key] = $gender !== ''
        ? ($gender_translations[$gender_key] ?? $gender)
        : 'Uppgift saknas';
    $gender_counts[$gender_key] = ($gender_counts[$gender_key] ?? 0) + 1;
}

// Fetch all members created before or within the selected year
$registration_date_limit = ($selected_year === 'all') ? "{$current_year}-12-31 23:59:59" : "{$selected_year}-12-31 23:59:59";

$all_users = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT ID FROM {$wpdb->base_prefix}users WHERE user_registered <= %s",
        $registration_date_limit
    )
);

$count_all_users = 0;

foreach ($all_users as $user) {
    if (!$is_included_user($user->ID)) {
        continue;
    }

    $count_all_users++;
    $gender = trim((string) get_user_meta($user->ID, 'wpum_gender', true));
    $gender_key = $gender !== '' ? strtolower($gender) : 'unspecified';
    $gender_labels[$gender_key] = $gender !== ''
        ? ($gender_translations[$gender_key] ?? $gender)
        : 'Uppgift saknas';
    $total_gender_counts[$gender_key] = ($total_gender_counts[$gender_key] ?? 0) + 1;
}

uksort($gender_labels, function ($first_key, $second_key) use ($total_gender_counts) {
    $total_comparison = ($total_gender_counts[$second_key] ?? 0) <=> ($total_gender_counts[$first_key] ?? 0);

    return $total_comparison !== 0 ? $total_comparison : strcasecmp($first_key, $second_key);
});
?>
	
<!-- Output the Gender Counts -->
<div class="columns">
    <div class="column1"><h3>⚧ Kön</h3></div>
    <div class="column2"><?php echo $count_all_users; ?> medlemmar (<?php echo ($selected_year === 'all') ? 'Alla år' : $selected_year; ?>)</div>
</div>
<hr>

<table class="admin-table">
    <thead>
        <tr>
            <th>Kön</th>
            <th>Totalt</th>
            <th>Aktiva</th>
            <th>Inaktiva</th>
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
                <td><?php echo $total_count; ?></td>
                <td><?php echo $active_count; ?></td>
                <td><?php echo $inactive_count; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
global $wpdb;

// Build area rows from the users' raw wpum_postarea values.
$area_labels = ['unspecified' => 'Uppgift saknas'];

// Initialize area counts.
$area_counts = ['unspecified' => 0];
$total_area_counts = ['unspecified' => 0];

// Fetch all users created before or within the selected year
$registration_date_limit = ($selected_year === 'all') ? "{$current_year}-12-31 23:59:59" : "{$selected_year}-12-31 23:59:59";

$all_users_area = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT ID FROM {$wpdb->base_prefix}users WHERE user_registered <= %s",
        $registration_date_limit
    )
);

// Count total users by area
foreach ($all_users_area as $user) {
    if (!$is_included_user($user->ID)) {
        continue;
    }

    $area = trim((string) get_user_meta($user->ID, 'wpum_postarea', true));
    $area_key = $area !== '' ? $area : 'unspecified';
    $area_labels[$area_key] = $area !== '' ? $area : 'Uppgift saknas';
    $total_area_counts[$area_key] = ($total_area_counts[$area_key] ?? 0) + 1;
}

// Fetch active members for the area
$active_members_area_query = "
    SELECT DISTINCT user_id 
    FROM (
        -- Givers: Users who created a post in category fetched in the selected year(s)
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id
        JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE p.post_type = 'post' AND p.post_status = 'publish'
        AND tt.term_id = {$fetcher_cat} AND p.post_date BETWEEN %s AND %s

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
    if (!$is_included_user($user_id)) {
        continue;
    }

    $area = trim((string) get_user_meta($user_id, 'wpum_postarea', true));
    $area_key = $area !== '' ? $area : 'unspecified';
    $area_labels[$area_key] = $area !== '' ? $area : 'Uppgift saknas';
    $area_counts[$area_key] = ($area_counts[$area_key] ?? 0) + 1;
}

uksort($area_labels, function ($first_key, $second_key) use ($total_area_counts) {
    $total_comparison = ($total_area_counts[$second_key] ?? 0) <=> ($total_area_counts[$first_key] ?? 0);

    return $total_comparison !== 0 ? $total_comparison : strcasecmp($first_key, $second_key);
});
?>

<!-- Output the City Counts -->
<div class="columns">
    <div class="column1"><h3>📍 Postområden</h3></div>
    <div class="column2"><?php echo $count_all_users; ?> medlemmar (<?php echo ($selected_year === 'all') ? 'Alla år' : $selected_year; ?>)</div>
</div>
<hr>

<table class="admin-table">
    <thead>
        <tr>
            <th>Postområde</th>
            <th>Totalt</th>
            <th>Aktiva</th>
            <th>Inaktiva</th>
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
                <td><?php echo $total_count; ?></td>
                <td><?php echo $active_count; ?></td>
                <td><?php echo $inactive_count; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($can_populate_postarea) : ?>
<h4>😈 Uppdatera postområden</h4>
<hr>
<p class="small">💡 Endast for webmaster.</p>

    <form method="post">
        <?php wp_nonce_field('loopis_populate_postarea'); ?>
        <!-- This button fills only missing wpum_postarea values. -->
        <button type="submit" class="orange small" name="loopis_populate_postarea" value="1" onclick="return confirm('Detta kompletterar saknade postområden. Vill du fortsätta?');">
            Komplettera saknade postområden
        </button>
        <!-- This button recalculates and overwrites existing values. -->
        <button
            type="submit"
            class="red small"
            name="loopis_populate_postarea"
            value="force"
            onclick="return confirm('Detta skriver över befintliga postområden. Vill du fortsätta?');"
        >
            Uppdatera alla postområden
        </button>
    </form>
<?php endif; ?>

<?php if ($postarea_update_output !== '') : ?>
    <pre><?php echo esc_html($postarea_update_output); ?></pre>
<?php endif; ?>