<?php
/**
 * Statistics for members.
 * 
 * Will be improved to use custom database table.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📊 Medlemmar</h1>
<hr>
<p class="small">💡 Statistik för medlemmar.</p>

<?php
// Set current year
$current_year = date('Y');

// Render dropdown and get the selected year
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/stats_select_year.php';
$selected_year = stats_select_year();

// Calculate days passed and output message
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/stats_days_passed.php';
$days_passed = stats_days_passed($selected_year); 

global $wpdb;

// Determine the year condition for SQL queries
$year_condition = ($selected_year === 'all') ? '' : $wpdb->prepare('AND YEAR(user_registered) = %d', $selected_year);

// Define a date range
$date_query = [];

// Set the date range if a specific year is selected
if ($selected_year !== 'all') {
    $date_query = [
        'before'    => "{$selected_year}-12-31",
        'inclusive' => true,
    ];
}

// COUNT MEMBERS
$member_args = [
    'role'       => 'member',
    'fields'     => 'ID',
    'date_query' => $date_query,
];
$member_count = count(get_users($member_args));

// Count earlier members
$member_earlier_args = [
    'role'       => 'member_earlier',
    'fields'     => 'ID',
    'date_query' => $date_query,
];
$member_earlier_count = count(get_users($member_earlier_args));

// Count members outside
$member_outside_args = [
    'role'       => 'member_outside',
    'fields'     => 'ID',
    'date_query' => $date_query,
];
$member_outside_count = count(get_users($member_outside_args));

// Calculate total members
$member_total_count = $member_count + $member_earlier_count + $member_outside_count;

// Query to count posters
$poster_year_condition = ($selected_year === 'all') ? '' : $wpdb->prepare('AND YEAR(post_date) = %d', $selected_year);
$poster_query = "
    SELECT COUNT(DISTINCT post_author)
    FROM {$wpdb->prefix}posts
    WHERE post_type = 'post' AND post_status = 'publish' $poster_year_condition
";
$poster_count = $wpdb->get_var($poster_query);
$poster_percentage = ($member_count > 0) ? round(($poster_count / $member_count) * 100) : 0;

// Determine the conditions for givers
$fetch_date_condition = '';
if ($selected_year !== 'all') {
    $fetch_date_start = "{$selected_year}-01-01 00:00:00";
    $fetch_date_end = "{$selected_year}-12-31 23:59:59";
    $fetch_date_condition = $wpdb->prepare('AND pm.meta_key = %s AND pm.meta_value BETWEEN %s AND %s', 'fetch_date', $fetch_date_start, $fetch_date_end);
}

// Query to count givers
$giver_year_condition = ($selected_year === 'all') ? '' : 'AND YEAR(pm.meta_value) = ' . intval($selected_year);
$giver_query = "
    SELECT COUNT(DISTINCT p.post_author)
    FROM {$wpdb->prefix}posts p
    JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id
    WHERE p.post_type = 'post' 
    AND p.post_status = 'publish'
    AND pm.meta_key = 'book_date' -- Meta key for book_date
    $giver_year_condition
";
$giver_count = $wpdb->get_var($giver_query);
$giver_percentage = ($member_count > 0) ? round(($giver_count / $member_count) * 100) : 0;
$success_percentage = ($poster_count > 0) ? round(($giver_count / $poster_count) * 100) : 0;

// Query to count only_givers (users who are Givers but NOT Fetchers)
$only_givers_year_condition = ($selected_year === 'all') ? '' : 'AND YEAR(pm_book_date.meta_value) = ' . intval($selected_year);
$only_fetchers_year_condition = ($selected_year === 'all') ? '' : 'AND YEAR(pm_date.meta_value) = ' . intval($selected_year);
$only_givers_query = "
    SELECT COUNT(DISTINCT givers.user_id)
    FROM (
        -- Givers: Users who created a post with book_date in the selected year
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}postmeta pm_book_date 
            ON p.ID = pm_book_date.post_id
        WHERE p.post_type = 'post' 
        AND p.post_status = 'publish'
        AND pm_book_date.meta_key = 'book_date' -- Meta key for book_date
        $only_givers_year_condition
    ) AS givers
    WHERE givers.user_id NOT IN (
        -- Fetchers: Users who are fetchers with a fetch_date in the selected year
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date 
            ON pm_fetcher.post_id = pm_date.post_id
        WHERE pm_fetcher.meta_key = 'fetcher'
        $only_fetchers_year_condition
    )
";

$only_givers_count = $wpdb->get_var($only_givers_query);
$only_givers_percentage = ($member_count > 0) ? round(($only_givers_count / $member_count) * 100) : 0;

// Determine the conditions for fetchers
$fetch_date_condition = '';
if ($selected_year !== 'all') {
    $fetch_date_start = "{$selected_year}-01-01 00:00:00";
    $fetch_date_end = "{$selected_year}-12-31 23:59:59";
    $fetch_date_condition = $wpdb->prepare(
        'AND pm_date.meta_key = %s AND pm_date.meta_value BETWEEN %s AND %s',
        'fetch_date',
        $fetch_date_start,
        $fetch_date_end
    );
}

// Query to count fetchers
$fetcher_query = "
    SELECT COUNT(DISTINCT pm_fetcher.meta_value)
    FROM {$wpdb->prefix}postmeta pm_fetcher
    JOIN {$wpdb->prefix}postmeta pm_date ON pm_fetcher.post_id = pm_date.post_id
    JOIN {$wpdb->prefix}users u ON pm_fetcher.meta_value = u.ID
    WHERE pm_fetcher.meta_key = 'fetcher'
    $fetch_date_condition
";

$fetcher_count = $wpdb->get_var($fetcher_query);
$fetcher_percentage = ($member_count > 0) ? round(($fetcher_count / $member_count) * 100) : 0;

// Query to count only_fetchers (users who are Fetchers but NOT Givers)
$only_fetchers_query = "
    SELECT COUNT(DISTINCT fetchers.user_id)
    FROM (
        -- Fetchers: Users who are fetchers with a fetch_date in the selected year
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date 
            ON pm_fetcher.post_id = pm_date.post_id
        WHERE pm_fetcher.meta_key = 'fetcher'
        $only_fetchers_year_condition
    ) AS fetchers
    WHERE fetchers.user_id NOT IN (
        -- Givers: Users who created a post with book_date in the selected year
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}postmeta pm_book_date 
            ON p.ID = pm_book_date.post_id
        WHERE p.post_type = 'post' 
        AND p.post_status = 'publish'
        AND pm_book_date.meta_key = 'book_date' -- Meta key for book_date
        $only_givers_year_condition
    )
";

$only_fetchers_count = $wpdb->get_var($only_fetchers_query);
$only_fetchers_percentage = ($member_count > 0) ? round(($only_fetchers_count / $member_count) * 100) : 0;

// Determine the conditions for active members
$post_year_condition = '';
$fetch_date_condition = '';
if ($selected_year !== 'all') {
    $post_year_condition = $wpdb->prepare('AND YEAR(p.post_date) = %d', $selected_year);
    $fetch_date_start = "{$selected_year}-01-01 00:00:00";
    $fetch_date_end = "{$selected_year}-12-31 23:59:59";
    $fetch_date_condition = $wpdb->prepare('AND pm_date.meta_key = %s AND pm_date.meta_value BETWEEN %s AND %s', 'fetch_date', $fetch_date_start, $fetch_date_end);
}

// Query to count active members (users who are givers OR fetchers)
$active_members_query = "
    SELECT COUNT(DISTINCT user_id) 
    FROM (
        -- Givers: Users who created a post in category 41 in the selected year
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id
        JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        JOIN {$wpdb->prefix}usermeta um 
            ON p.post_author = um.user_id 
            AND um.meta_key = '{$wpdb->prefix}capabilities'
        WHERE p.post_type = 'post' 
        AND p.post_status = 'publish'
        AND tt.term_id = 41 -- Only include posts in category 41
        AND (um.meta_value NOT LIKE '%\"administrator\"%') 
        $post_year_condition

        UNION

        -- Fetchers: Users who are fetchers with a fetch_date in the selected year
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date 
            ON pm_fetcher.post_id = pm_date.post_id
        JOIN {$wpdb->prefix}usermeta um 
            ON pm_fetcher.meta_value = um.user_id 
            AND um.meta_key = '{$wpdb->prefix}capabilities'
        WHERE pm_fetcher.meta_key = 'fetcher'
        AND (um.meta_value NOT LIKE '%\"administrator\"%') 
        $fetch_date_condition
    ) AS active_users
";

$active_members = $wpdb->get_var($active_members_query);
$active_percentage = ($member_count > 0) ? round(($active_members / $member_count) * 100) : 0;

// Query to count sharing members (users who are both givers AND fetchers)
$sharing_givers_year_condition = ($selected_year === 'all') ? '' : 'AND YEAR(pm_book_date.meta_value) = ' . intval($selected_year);
$sharing_fetchers_year_condition = ($selected_year === 'all') ? '' : 'AND YEAR(pm_date.meta_value) = ' . intval($selected_year);
$sharing_members_query = "
    SELECT COUNT(DISTINCT givers.user_id) 
    FROM (
        -- Givers: Users who created a post with book_date in the selected year
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}postmeta pm_book_date 
            ON p.ID = pm_book_date.post_id
        JOIN {$wpdb->prefix}usermeta um 
            ON p.post_author = um.user_id 
            AND um.meta_key = '{$wpdb->prefix}capabilities'
        WHERE p.post_type = 'post' 
        AND p.post_status = 'publish'
        AND pm_book_date.meta_key = 'book_date' -- Meta key for book_date
        $sharing_givers_year_condition
        AND (um.meta_value NOT LIKE '%\"administrator\"%') 
    ) AS givers
    INNER JOIN (
        -- Fetchers: Users who are fetchers with a fetch_date in the selected year
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date 
            ON pm_fetcher.post_id = pm_date.post_id
        JOIN {$wpdb->prefix}usermeta um 
            ON pm_fetcher.meta_value = um.user_id 
            AND um.meta_key = '{$wpdb->prefix}capabilities'
        WHERE pm_fetcher.meta_key = 'fetcher'
        $sharing_fetchers_year_condition
        AND (um.meta_value NOT LIKE '%\"administrator\"%') 
    ) AS fetchers
    ON givers.user_id = fetchers.user_id
";

$sharing_members = $wpdb->get_var($sharing_members_query);
$sharing_percentage = ($member_count > 0) ? round(($sharing_members / $member_count) * 100) : 0;
?>

<!-- Output the counts for all members -->
<div class="columns"><div class="column1"><h7>👩‍👩‍👧‍👦 Alla medlemmar <?php if ($selected_year == 'all' || $selected_year == $current_year ) { echo "just nu"; } else { echo $selected_year; } ?></h7></div>
<div class="column2 bottom">(<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<p><span class="big-label">👤 <?php echo $member_total_count; ?> medlemmar</span> <?php if ($selected_year == 'all' || $selected_year == $current_year ) { echo "just nu"; } else { echo "vid årets slut"; } ?> ( <span class="label">🙂 <?php echo $member_count; ?> kvar</span> + <span class="label">😴 <?php echo $member_earlier_count; ?> avhoppare</span> + <span class="label">🌍 <?php echo $member_outside_count; ?> utsocknes</span> )</p>
<p><span class="big-label"><i class="fas fa-walking"></i> <?php echo $active_members; ?> aktiva medlemmar</span> = <span class="label"><?php echo $active_percentage; ?>% av medlemmarna</span></p>
<p><span class="big-label">⬆️ <?php echo $poster_count; ?> annonsörer</span> = <span class="label"><?php echo $poster_percentage; ?>% av medlemmarna</span></p>
<p><span class="big-label">✅ <?php echo $giver_count; ?> givare</span> = <span class="label"><?php echo $giver_percentage; ?>% av medlemmarna</span> = <span class="label"><?php echo $success_percentage; ?>% av annonsörerna</span></p>
<p><span class="big-label">☑ <?php echo $fetcher_count; ?> hämtare</span> = <span class="label"><?php echo $fetcher_percentage; ?>% av medlemmarna</span></p>
<p><span class="big-label">🔄 <?php echo $sharing_members; ?> loopare</span> = <span class="label"><?php echo $sharing_percentage; ?>% av medlemmarna</span></p>
<p><span class="big-label">✅ <?php echo $only_givers_count; ?> endast givare</span> = <span class="label"><?php echo $only_givers_percentage; ?>% av medlemmarna</span></p>
<p><span class="big-label">☑ <?php echo $only_fetchers_count; ?> endast hämtare</span> = <span class="label"><?php echo $only_fetchers_percentage; ?>% av medlemmarna</span></p>

<?php if ($selected_year !== 'all') { 

// Determine the date range for the selected year
$new_date_query = [];
if ($selected_year !== 'all') {
    $new_date_query = [
        'after'     => "{$selected_year}-01-01",
        'before'    => "{$selected_year}-12-31",
        'inclusive' => true,
    ];
}

// Count new members (during selected year)
$new_member_args = [
    'role'       => 'member',
    'fields'     => 'ID',
    'date_query' => $new_date_query,
];
$new_member_count = count(get_users($new_member_args));

// Count new earlier members (during selected year)
$new_member_earlier_args = [
    'role'       => 'member_earlier',
    'fields'     => 'ID',
    'date_query' => $new_date_query,
];
$new_member_earlier_count = count(get_users($new_member_earlier_args));

// Count new members outside (during selected year)
$new_member_outside_args = [
    'role'       => 'member_outside',
    'fields'     => 'ID',
    'date_query' => $new_date_query,
];
$new_member_outside_count = count(get_users($new_member_outside_args));

// Calculate new members total (during selected year)
$new_member_total_count = $new_member_count + $new_member_earlier_count + $new_member_outside_count;

// Determine the conditions for the selected year
$post_year_condition = '';
$fetch_date_condition = '';
$user_registered_condition = '';
if ($selected_year !== 'all') {
    $post_year_condition = $wpdb->prepare('AND YEAR(p.post_date) = %d', $selected_year);
    $fetch_date_start = "{$selected_year}-01-01 00:00:00";
    $fetch_date_end = "{$selected_year}-12-31 23:59:59";
    $fetch_date_condition = $wpdb->prepare('AND pm_date.meta_key = %s AND pm_date.meta_value BETWEEN %s AND %s', 'fetch_date', $fetch_date_start, $fetch_date_end);
    $user_registered_condition = $wpdb->prepare('AND YEAR(u.user_registered) = %d', $selected_year);
}

// Query to count active new members excluding administrators
$active_new_members_query = "
    SELECT COUNT(DISTINCT user_id) FROM (
        -- Users who created a post in the selected year and registered in the selected year
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}users u ON p.post_author = u.ID
        LEFT JOIN {$wpdb->prefix}usermeta um ON u.ID = um.user_id AND um.meta_key = '{$wpdb->prefix}capabilities'
        WHERE p.post_type = 'post' AND p.post_status = 'publish'
        AND (um.meta_value NOT LIKE '%\"administrator\"%') $post_year_condition $user_registered_condition

        UNION

        -- Users who are fetchers with fetch_date in the selected year and registered in the selected year
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date ON pm_fetcher.post_id = pm_date.post_id
        JOIN {$wpdb->prefix}users u ON pm_fetcher.meta_value = u.ID
        LEFT JOIN {$wpdb->prefix}usermeta um ON u.ID = um.user_id AND um.meta_key = '{$wpdb->prefix}capabilities'
        WHERE pm_fetcher.meta_key = 'fetcher'
        AND (um.meta_value NOT LIKE '%\"administrator\"%') $fetch_date_condition $user_registered_condition
    ) AS active_new_users
";

$active_new_members = $wpdb->get_var($active_new_members_query);
$active_new_percentage = ($new_member_count > 0) ? round(($active_new_members / $new_member_count) * 100) : 0;

// Determine the conditions for new posters
$poster_year_condition = ($selected_year === 'all') ? '' : $wpdb->prepare('AND YEAR(p.post_date) = %d', $selected_year);
$user_registered_condition = ($selected_year === 'all') ? '' : $wpdb->prepare('AND YEAR(u.user_registered) = %d', $selected_year);

// Query to count new posters
$new_poster_query = "
    SELECT COUNT(DISTINCT p.post_author)
    FROM {$wpdb->prefix}posts p
    JOIN {$wpdb->prefix}users u ON p.post_author = u.ID
    WHERE p.post_type = 'post' 
    AND p.post_status = 'publish' 
    $poster_year_condition
    $user_registered_condition
";

$new_poster_count = $wpdb->get_var($new_poster_query);
$new_poster_percentage = ($new_member_count > 0) ? round(($new_poster_count / $new_member_count) * 100) : 0;


// Determine the conditions for givers
$fetch_date_condition = '';
$user_registered_condition = '';
if ($selected_year !== 'all') {
    $fetch_date_start = "{$selected_year}-01-01 00:00:00";
    $fetch_date_end = "{$selected_year}-12-31 23:59:59";
    $fetch_date_condition = $wpdb->prepare(
        'AND pm.meta_key = %s AND pm.meta_value BETWEEN %s AND %s',
        'fetch_date',
        $fetch_date_start,
        $fetch_date_end
    );
    $user_registered_condition = $wpdb->prepare(
        'AND YEAR(u.user_registered) = %d',
        $selected_year
    );
}

// Query to count givers
$new_giver_query = "
    SELECT COUNT(DISTINCT p.post_author)
    FROM {$wpdb->prefix}posts p
    JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id
    JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id
    JOIN {$wpdb->prefix}users u ON p.post_author = u.ID
    WHERE p.post_type = 'post' 
    AND p.post_status = 'publish'
    AND tt.term_id = 41
    $fetch_date_condition
    $user_registered_condition
";

$new_giver_count = $wpdb->get_var($new_giver_query);
$new_giver_percentage = ($new_member_count > 0) ? round(($new_giver_count / $new_member_count) * 100) : 0;
$new_success_percentage = ($new_poster_count > 0) ? round(($new_giver_count / $new_poster_count) * 100) : 0;
	
// Determine the conditions for new fetchers
$fetch_date_condition = '';
$user_registered_condition = '';
if ($selected_year !== 'all') {
    $fetch_date_start = "{$selected_year}-01-01 00:00:00";
    $fetch_date_end = "{$selected_year}-12-31 23:59:59";
    $fetch_date_condition = $wpdb->prepare('AND pm_date.meta_key = %s AND pm_date.meta_value BETWEEN %s AND %s', 'fetch_date', $fetch_date_start, $fetch_date_end);
    $user_registered_condition = $wpdb->prepare('AND YEAR(u.user_registered) = %d', $selected_year);
}

// Query to count fetchers 
$new_fetcher_query = "
    SELECT COUNT(DISTINCT pm_fetcher.meta_value)
    FROM {$wpdb->prefix}postmeta pm_fetcher
    JOIN {$wpdb->prefix}postmeta pm_date ON pm_fetcher.post_id = pm_date.post_id
    JOIN {$wpdb->prefix}users u ON pm_fetcher.meta_value = u.ID
    WHERE pm_fetcher.meta_key = 'fetcher'
    $fetch_date_condition
    $user_registered_condition
";
$new_fetcher_count = $wpdb->get_var($new_fetcher_query);
$new_fetcher_percentage = ($new_member_count > 0) ? round(($new_fetcher_count / $new_member_count) * 100) : 0;

// Query to count sharing members (users who are both givers AND fetchers)
$new_sharing_givers_year_condition = 'AND YEAR(pm_book_date.meta_value) = ' . intval($selected_year) . ' AND YEAR(u.user_registered) = ' . intval($selected_year);
$new_sharing_fetchers_year_condition = 'AND YEAR(pm_date.meta_value) = ' . intval($selected_year) . ' AND YEAR(u.user_registered) = ' . intval($selected_year);
$new_sharing_members_query = "
    SELECT COUNT(DISTINCT givers.user_id) 
    FROM (
        -- Givers: Users who created a post with book_date in the selected year
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}postmeta pm_book_date 
            ON p.ID = pm_book_date.post_id
        JOIN {$wpdb->prefix}users u ON p.post_author = u.ID
        JOIN {$wpdb->prefix}usermeta um 
            ON p.post_author = um.user_id 
            AND um.meta_key = '{$wpdb->prefix}capabilities'
        WHERE p.post_type = 'post' 
        AND p.post_status = 'publish'
        AND pm_book_date.meta_key = 'book_date' -- Meta key for book_date
        $new_sharing_givers_year_condition
        AND (um.meta_value NOT LIKE '%\"administrator\"%') 
    ) AS givers
    INNER JOIN (
        -- Fetchers: Users who are fetchers with a fetch_date in the selected year
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date 
            ON pm_fetcher.post_id = pm_date.post_id
        JOIN {$wpdb->prefix}users u ON pm_fetcher.meta_value = u.ID
        JOIN {$wpdb->prefix}usermeta um 
            ON pm_fetcher.meta_value = um.user_id 
            AND um.meta_key = '{$wpdb->prefix}capabilities'
        WHERE pm_fetcher.meta_key = 'fetcher'
        $new_sharing_fetchers_year_condition
        AND (um.meta_value NOT LIKE '%\"administrator\"%') 
    ) AS fetchers
    ON givers.user_id = fetchers.user_id
";

$new_sharing_members = $wpdb->get_var($new_sharing_members_query);
$new_sharing_percentage = ($new_member_count > 0) ? round(($new_sharing_members / $new_member_count) * 100) : 0;
?>

<!-- Output the counts for new members (during selected year) -->
<div class="columns"><div class="column1"><h7>👶 Nya medlemmar <?php echo $selected_year; ?></h7></div>
<div class="column2 bottom">(<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<p><span class="big-label">👤 <?php echo $new_member_total_count; ?> nya medlemmar</span> under året ( <span class="label">🙂 <?php echo $new_member_count; ?> kvar</span> + <span class="label">😴 <?php echo $new_member_earlier_count; ?> avhoppare</span> + <span class="label">🌍 <?php echo $new_member_outside_count; ?> utsocknes</span> )</p>
<p><span class="big-label"><i class="fas fa-walking"></i> <?php echo $active_new_members; ?> nya aktiva</span> = <span class="label"><?php echo $active_new_percentage; ?>% av nya medlemmarna</span></p>
<p><span class="big-label">⬆️ <?php echo $new_poster_count; ?> nya annonsörer</span> = <span class="label"><?php echo $new_poster_percentage; ?>% av nya medlemmarna</span></p>
<p><span class="big-label">✅ <?php echo $new_giver_count; ?> nya givare</span> = <span class="label"><?php echo $new_giver_percentage; ?>% av nya medlemmarna</span> = <span class="label"><?php echo $new_success_percentage; ?>% av nya annonsörerna</span></p>
<p><span class="big-label">☑ <?php echo $new_fetcher_count; ?> nya hämtare</span> = <span class="label"><?php echo $new_fetcher_percentage; ?>% av nya medlemmarna</span></p>
<p><span class="big-label">🔄 <?php echo $new_sharing_members; ?> nya loopare</span> = <span class="label"><?php echo $new_sharing_percentage; ?>% av nya medlemmarna</span></p>
<?php } ?>