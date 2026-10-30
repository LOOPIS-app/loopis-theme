<?php
/**
 * Post count for all categories + uncategorized.
 * 
 * Used for overview, debugging and support.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🚦 Kategorier</h1>
<hr>
<p class="small">💡 Antal annonser i alla kategorier.</p>

<?php
// Retrieve ALL registered categories (including empty ones)
$categories = get_categories(array(
    'hide_empty' => false,
    'orderby'    => 'name',
));

$rows = array();
foreach ($categories as $category) {
    $posts = get_posts(array(
        'category__in'     => array($category->term_id),
        'posts_per_page'   => -1,
        'suppress_filters' => true,
        'no_found_rows'    => true,
    ));

    // Unique authors in this category
    $user_ids = array_unique(wp_list_pluck($posts, 'post_author'));

    $rows[] = array(
        'name'  => $category->name,
        'slug'  => $category->slug,
        'posts' => count($posts),
        'users' => count($user_ids),
    );
}

// Posts without any category
$uncategorized = get_posts(array(
    'tax_query'        => array(array(
        'taxonomy' => 'category',
        'operator' => 'NOT EXISTS',
    )),
    'posts_per_page'   => -1,
    'suppress_filters' => true,
    'no_found_rows'    => true,
));

$rows[] = array(
    'name'  => '💢 Okategoriserad',
    'posts' => count($uncategorized),
    'users' => count(array_unique(wp_list_pluck($uncategorized, 'post_author'))),
);

// Sort by post count, highest first
usort($rows, function ($a, $b) {
    return $b['posts'] <=> $a['posts'];
});
?>

<div class="columns"><div class="column1"><h7>🎁 Saker att få</h7></div>
<div class="column2"></div></div>
<hr>
<?php foreach ($rows as $row) : ?>
<?php
// First grapheme of the category name (the emoji, incl. any variation selector)
preg_match('/^\X/u', $row['name'], $first);
$icon = $first[0] ?? '';
?>
<p><span class="big-label"><?php echo esc_html($icon); ?> <?php echo (int) $row['posts']; ?> annonser</span> i kategori <span class="big-label"><?php if (!empty($row['slug'])) : ?><a href="<?php echo esc_url(home_url('/category/' . $row['slug'])); ?>"><?php echo esc_html($row['name']); ?></a><?php else : ?><?php echo esc_html($row['name']); ?><?php endif; ?></span> hos <span class="big-label">👤 <?php echo (int) $row['users']; ?> användare</span></p>
<?php endforeach; ?>
