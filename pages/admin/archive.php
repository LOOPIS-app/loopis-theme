<?php
/**
 * Archive page
 * Showings all items in archive categories
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🕸 Arkiv</h1>
<hr>
<p class="small">💡 Här visas info om inaktiva annonser.</p>

<?php
// Function to display top users
include_once LOOPIS_THEME_DIR . '/functions/admin-extra/stats/display_top_users.php';

// Get the category by slug
$category = get_category_by_slug('archived');
$category_id = $category ? $category->term_id : 0; // Use 0 if the category is invalid

// Arguments for WP_Query
$args = array(
    'category__in' => array($category_id),
    'posts_per_page' => -1,
    'fields' => 'ids',
);

// Perform the query
$posts = new WP_Query($args);
$post_ids = $posts->posts; // Array of post IDs

// Count all archived ads
$archived_count = count($post_ids);

// Collect unique user IDs
$user_ids = array();

foreach ($post_ids as $post_id) {
    $user_id = get_post_field('post_author', $post_id); // Get the post author ID
    if ($user_id && !in_array($user_id, $user_ids)) { // Avoid duplicates
        $user_ids[] = $user_id;
    }
}

// Count unique users
$unique_users_archive = count($user_ids);
?>

<div class="columns"><div class="column1"><h7>🎁 Saker att få</h7></div>
<div class="column2 bottom"></div></div>
<hr>
<p>
    <span class="big-label">⭕ <?php echo $archived_count; ?> arkiverade annonser</span> 
    hos 
    <span class="big-label">👤 <?php echo $unique_users_archive; ?> medlemmar</span>
</p>

<?php
// Get the category by slug
$category = get_category_by_slug('paused');
$category_id = $category ? $category->term_id : 0; // Use 0 if the category is invalid

// Arguments for WP_Query
$args = array(
    'category__in' => array($category_id),
    'posts_per_page' => -1,
    'fields' => 'ids',
);

// Perform the query
$posts = new WP_Query($args);
$post_ids = $posts->posts;

// Count all archived ads
$paused_count = count($post_ids);

// Collect unique user IDs
$user_ids = array();

foreach ($post_ids as $post_id) {
    $user_id = get_post_field('post_author', $post_id); // Get the post author ID
    if ($user_id && !in_array($user_id, $user_ids)) { // Avoid duplicates
        $user_ids[] = $user_id;
    }
}

// Count unique users
$unique_users_paused = count($user_ids);
?>

<p>
    <span class="big-label">😎 <?php echo $paused_count; ?> arkiverade annonser</span> 
    hos 
    <span class="big-label">👤 <?php echo $unique_users_paused; ?> medlemmar</span>
</p>


<div class="columns"><div class="column1"><h3>👤 Topp-20 med flest arkiverade</h3></div>
<div class="column2 bottom"></div></div>
<hr>

<?php
global $wpdb;

// Get the category ID for the 'archived' category
$category = get_category_by_slug('archived');
$category_id = $category ? $category->term_id : 0;

// Return early if the category does not exist
if (!$category_id) {
    echo "<p>No archived category found.</p>";
    return;
}

// SQL query to fetch the top 20 users with the most posts in the 'archived' category
$query = $wpdb->prepare("
    SELECT p.post_author AS user_id, COUNT(*) AS post_count
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
    INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    WHERE tt.term_id = %d
      AND p.post_status = 'publish'
      AND p.post_type = 'post'
    GROUP BY p.post_author
    ORDER BY post_count DESC
    LIMIT 20
", $category_id);

// Fetch results from the database
$top_archive = $wpdb->get_results($query);

// Call the reusable display function
display_top_users($top_archive, '⭕', false);
?>