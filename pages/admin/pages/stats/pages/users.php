<?php
/**
 * Statistics for users.
 * 
 * Will be improved to use custom database table.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>📊 Användare</h1>
<hr>
<p class="small">💡 Statistik för områdets användare.</p>

<?php
$current_year = date('Y');

// Render dropdown and get the selected year
include_once LOOPIS_THEME_DIR . '/pages/admin/functions/stats_select_year.php';
$stats_selection = stats_select_year();
$selected_year = $stats_selection['selected_year'];
$days_passed = $stats_selection['days_passed'];

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

// COUNT USERS
$user_args = [
    'role'       => 'member',
    'fields'     => 'ID',
    'date_query' => $date_query,
];
$user_count = count(get_users($user_args));

// Count earlier members
$user_earlier_args = [
    'role'       => 'member_earlier',
    'fields'     => 'ID',
    'date_query' => $date_query,
];
$user_earlier_count = count(get_users($user_earlier_args));

// Query to count posters
$poster_year_condition = ($selected_year === 'all') ? '' : $wpdb->prepare('AND YEAR(post_date) = %d', $selected_year);
$poster_query = "
    SELECT COUNT(DISTINCT post_author)
    FROM {$wpdb->prefix}posts
    WHERE post_type = 'post' AND post_status = 'publish' $poster_year_condition
";
$poster_count = $wpdb->get_var($poster_query);
$poster_percentage = ($user_count > 0) ? round(($poster_count / $user_count) * 100) : 0;

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
$giver_percentage = ($user_count > 0) ? round(($giver_count / $user_count) * 100) : 0;
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
$only_givers_percentage = ($user_count > 0) ? round(($only_givers_count / $user_count) * 100) : 0;

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
    JOIN {$wpdb->users} u ON pm_fetcher.meta_value = u.ID
    WHERE pm_fetcher.meta_key = 'fetcher'
    $fetch_date_condition
";

$fetcher_count = $wpdb->get_var($fetcher_query);
$fetcher_percentage = ($user_count > 0) ? round(($fetcher_count / $user_count) * 100) : 0;

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
$only_fetchers_percentage = ($user_count > 0) ? round(($only_fetchers_count / $user_count) * 100) : 0;
$fetcher_cat = loopis_cat('fetched');
// Determine the conditions for active users
$post_year_condition = '';
$fetch_date_condition = '';
if ($selected_year !== 'all') {
    $post_year_condition = $wpdb->prepare('AND YEAR(p.post_date) = %d', $selected_year);
    $fetch_date_start = "{$selected_year}-01-01 00:00:00";
    $fetch_date_end = "{$selected_year}-12-31 23:59:59";
    $fetch_date_condition = $wpdb->prepare('AND pm_date.meta_key = %s AND pm_date.meta_value BETWEEN %s AND %s', 'fetch_date', $fetch_date_start, $fetch_date_end);
}

// Query to count active users (givers OR fetchers)
$active_users_query = "
    SELECT COUNT(DISTINCT user_id) 
    FROM (
        -- Givers: Users who created a post in category fetched in the selected year
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}term_relationships tr ON p.ID = tr.object_id
        JOIN {$wpdb->prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE p.post_type = 'post' 
        AND p.post_status = 'publish'
        AND tt.term_id = {$fetcher_cat} -- Only include posts in category fetched
        $post_year_condition

        UNION

        -- Fetchers: Users who are fetchers with a fetch_date in the selected year
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date 
            ON pm_fetcher.post_id = pm_date.post_id
        WHERE pm_fetcher.meta_key = 'fetcher'
        $fetch_date_condition
    ) AS active_users
";

$active_users = $wpdb->get_var($active_users_query);
$active_percentage = ($user_count > 0) ? round(($active_users / $user_count) * 100) : 0;

// Query to count sharing users (givers AND fetchers)
$sharing_givers_year_condition = ($selected_year === 'all') ? '' : 'AND YEAR(pm_book_date.meta_value) = ' . intval($selected_year);
$sharing_fetchers_year_condition = ($selected_year === 'all') ? '' : 'AND YEAR(pm_date.meta_value) = ' . intval($selected_year);
$sharing_users_query = "
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
        $sharing_givers_year_condition
    ) AS givers
    INNER JOIN (
        -- Fetchers: Users who are fetchers with a fetch_date in the selected year
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date 
            ON pm_fetcher.post_id = pm_date.post_id
        WHERE pm_fetcher.meta_key = 'fetcher'
        $sharing_fetchers_year_condition
    ) AS fetchers
    ON givers.user_id = fetchers.user_id
";

$sharing_users = $wpdb->get_var($sharing_users_query);
$sharing_percentage = ($user_count > 0) ? round(($sharing_users / $user_count) * 100) : 0;
?>

<!-- Output counts for users in selected timespan -->
<div class="columns"><div class="column1"><h7>👩‍👩‍👧‍👦 Användare <?php if ($selected_year == 'all' || $selected_year == $current_year ) { echo "just nu"; } else { echo "vid slutet av " . $selected_year; } ?></h7></div>
<div class="column2">(<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<div class="span-list">
<span class="mega-label">👤 <?php echo $user_count; ?> användare</span> + <span class="label">🚛 <?php echo $user_earlier_count; ?> tidigare</span><br>
<span class="mega-label"><i class="fas fa-walking"></i> <?php echo $active_users; ?> aktiva användare</span> = <span class="label"><?php echo $active_percentage; ?>% av användarna</span><br>
<span class="mega-label">⬆️ <?php echo $poster_count; ?> annonsörer</span> = <span class="label"><?php echo $poster_percentage; ?>% av användarna</span><br>
<span class="mega-label">✅ <?php echo $giver_count; ?> givare</span> = <span class="label"><?php echo $giver_percentage; ?>% av användarna</span> = <span class="label"><?php echo $success_percentage; ?>% av annonsörerna</span><br>
<span class="mega-label">☑ <?php echo $fetcher_count; ?> hämtare</span> = <span class="label"><?php echo $fetcher_percentage; ?>% av användarna</span><br>
<span class="mega-label">🔄 <?php echo $sharing_users; ?> loopare</span> = <span class="label"><?php echo $sharing_percentage; ?>% av användarna</span><br>
<span class="mega-label">✅ <?php echo $only_givers_count; ?> endast givare</span> = <span class="label"><?php echo $only_givers_percentage; ?>% av användarna</span><br>
<span class="mega-label">☑ <?php echo $only_fetchers_count; ?> endast hämtare</span> = <span class="label"><?php echo $only_fetchers_percentage; ?>% av användarna</span><br>
</div>

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

// Count new users (becoming members during selected year)
$new_user_args = [
    'role'       => 'member',
    'fields'     => 'ID',
    'date_query' => $new_date_query,
];
$new_user_count = count(get_users($new_user_args));

// Count new users now "earlier" (becoming members during selected year but moved to other blog)
$new_user_earlier_args = [
    'role'       => 'member_earlier',
    'fields'     => 'ID',
    'date_query' => $new_date_query,
];
$new_user_earlier_count = count(get_users($new_user_earlier_args));

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

// Query to count active new members
$active_new_users_query = "
    SELECT COUNT(DISTINCT user_id) FROM (
        -- Users who created a post in the selected year and registered in the selected year
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->users} u ON p.post_author = u.ID
        WHERE p.post_type = 'post' AND p.post_status = 'publish'
        $post_year_condition $user_registered_condition

        UNION

        -- Users who are fetchers with fetch_date in the selected year and registered in the selected year
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date ON pm_fetcher.post_id = pm_date.post_id
        JOIN {$wpdb->users} u ON pm_fetcher.meta_value = u.ID
        WHERE pm_fetcher.meta_key = 'fetcher'
        $fetch_date_condition $user_registered_condition
    ) AS active_new_users
";

$active_new_users = $wpdb->get_var($active_new_users_query);
$active_new_percentage = ($new_user_count > 0) ? round(($active_new_users / $new_user_count) * 100) : 0;

// Determine the conditions for new posters
$poster_year_condition = ($selected_year === 'all') ? '' : $wpdb->prepare('AND YEAR(p.post_date) = %d', $selected_year);
$user_registered_condition = ($selected_year === 'all') ? '' : $wpdb->prepare('AND YEAR(u.user_registered) = %d', $selected_year);

// Query to count new posters
$new_poster_query = "
    SELECT COUNT(DISTINCT p.post_author)
    FROM {$wpdb->prefix}posts p
    JOIN {$wpdb->users} u ON p.post_author = u.ID
    WHERE p.post_type = 'post' 
    AND p.post_status = 'publish' 
    $poster_year_condition
    $user_registered_condition
";

$new_poster_count = $wpdb->get_var($new_poster_query);
$new_poster_percentage = ($new_user_count > 0) ? round(($new_poster_count / $new_user_count) * 100) : 0;


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
    JOIN {$wpdb->users} u ON p.post_author = u.ID
    WHERE p.post_type = 'post' 
    AND p.post_status = 'publish'
    AND tt.term_id = {$fetcher_cat}
    $fetch_date_condition
    $user_registered_condition
";

$new_giver_count = $wpdb->get_var($new_giver_query);
$new_giver_percentage = ($new_user_count > 0) ? round(($new_giver_count / $new_user_count) * 100) : 0;
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
    JOIN {$wpdb->users} u ON pm_fetcher.meta_value = u.ID
    WHERE pm_fetcher.meta_key = 'fetcher'
    $fetch_date_condition
    $user_registered_condition
";
$new_fetcher_count = $wpdb->get_var($new_fetcher_query);
$new_fetcher_percentage = ($new_user_count > 0) ? round(($new_fetcher_count / $new_user_count) * 100) : 0;

// Query to count new sharing users (givers AND fetchers)
$new_sharing_givers_year_condition = 'AND YEAR(pm_book_date.meta_value) = ' . intval($selected_year) . ' AND YEAR(u.user_registered) = ' . intval($selected_year);
$new_sharing_fetchers_year_condition = 'AND YEAR(pm_date.meta_value) = ' . intval($selected_year) . ' AND YEAR(u.user_registered) = ' . intval($selected_year);
$new_sharing_users_query = "
    SELECT COUNT(DISTINCT givers.user_id) 
    FROM (
        -- Givers: Users who created a post with book_date in the selected year
        SELECT DISTINCT p.post_author AS user_id
        FROM {$wpdb->prefix}posts p
        JOIN {$wpdb->prefix}postmeta pm_book_date 
            ON p.ID = pm_book_date.post_id
        JOIN {$wpdb->users} u ON p.post_author = u.ID
        WHERE p.post_type = 'post' 
        AND p.post_status = 'publish'
        AND pm_book_date.meta_key = 'book_date' -- Meta key for book_date
        $new_sharing_givers_year_condition
    ) AS givers
    INNER JOIN (
        -- Fetchers: Users who are fetchers with a fetch_date in the selected year
        SELECT DISTINCT pm_fetcher.meta_value AS user_id
        FROM {$wpdb->prefix}postmeta pm_fetcher
        JOIN {$wpdb->prefix}postmeta pm_date 
            ON pm_fetcher.post_id = pm_date.post_id
        JOIN {$wpdb->users} u ON pm_fetcher.meta_value = u.ID
        WHERE pm_fetcher.meta_key = 'fetcher'
        $new_sharing_fetchers_year_condition
    ) AS fetchers
    ON givers.user_id = fetchers.user_id
";

$new_sharing_users = $wpdb->get_var($new_sharing_users_query);
$new_sharing_percentage = ($new_user_count > 0) ? round(($new_sharing_users / $new_user_count) * 100) : 0;
?>

<!-- Output counts for new users (during selected year) -->
<div class="columns"><div class="column1"><h7>👶 Nya användare <?php echo $selected_year; ?></h7></div>
<div class="column2">(<?php echo $days_passed; ?> dagar)</div></div>
<hr>
<div class="span-list">
<span class="mega-label">👤 <?php echo $new_user_count; ?> nya användare</span> under året + <span class="label">🚛 <?php echo $new_user_earlier_count; ?> tidigare</span><br>
<span class="mega-label"><i class="fas fa-walking"></i> <?php echo $active_new_users; ?> nya aktiva</span> = <span class="label"><?php echo $active_new_percentage; ?>% av nya användarna</span><br>
<span class="mega-label">⬆️ <?php echo $new_poster_count; ?> nya annonsörer</span> = <span class="label"><?php echo $new_poster_percentage; ?>% av nya användarna</span><br>
<span class="mega-label">✅ <?php echo $new_giver_count; ?> nya givare</span> = <span class="label"><?php echo $new_giver_percentage; ?>% av nya användarna</span> = <span class="label"><?php echo $new_success_percentage; ?>% av nya annonsörerna</span><br>
<span class="mega-label">☑ <?php echo $new_fetcher_count; ?> nya hämtare</span> = <span class="label"><?php echo $new_fetcher_percentage; ?>% av nya användarna</span><br>
<span class="mega-label">🔄 <?php echo $new_sharing_users; ?> nya loopare</span> = <span class="label"><?php echo $new_sharing_percentage; ?>% av nya användarna</span><br>
</div>
<?php } ?>