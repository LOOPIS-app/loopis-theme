<?php
/**
 * User search for managers.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🔍 Sök medlemmar</h1>
<hr>
<p class="small">💡 Sök på namn och/eller ort.</p>

<?php
$search_term = isset($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
$search_terms = preg_split('/\s+/', trim($search_term), -1, PREG_SPLIT_NO_EMPTY);
$selected_city = isset($_GET['city']) ? sanitize_text_field(wp_unslash($_GET['city'])) : '';
$available_cities = array();
$blog_user_ids = get_users(array(
    'blog_id' => get_current_blog_id(),
    'fields' => 'ID',
));

foreach ($blog_user_ids as $blog_user_id) {
    $city = get_user_meta((int) $blog_user_id, 'wpum_postarea', true);
    if (is_scalar($city)) {
        $city = trim((string) $city);
        if ($city !== '') {
            $available_cities[$city] = $city;
        }
    }
}
natcasesort($available_cities);
if (!isset($available_cities[$selected_city])) {
    $selected_city = '';
}

$search_results = array();
$has_search = !empty($search_terms) || $selected_city !== '';

if ($has_search) {
    $name_search = array('relation' => 'AND');
    foreach ($search_terms as $search_word) {
        $name_search[] = array(
            'relation' => 'OR',
            array(
                'key' => 'first_name',
                'value' => $search_word,
                'compare' => 'LIKE',
            ),
            array(
                'key' => 'last_name',
                'value' => $search_word,
                'compare' => 'LIKE',
            ),
        );
    }
    if ($selected_city !== '') {
        $name_search[] = array(
            'key' => 'wpum_postarea',
            'value' => $selected_city,
            'compare' => '=',
        );
    }

    $search_results = get_users(array(
        'blog_id' => get_current_blog_id(),
        'orderby' => 'registered',
        'order' => 'DESC',
        'meta_query' => $name_search,
    ));
}
$count = count($search_results);
?>

<!-- Search box -->
<form class="loopis-form" id="search-form"  method="get" action="">
    <input type="hidden" name="view" value="<?php echo esc_attr(isset($_GET['view']) ? wp_unslash($_GET['view']) : ''); ?>">
<div class="search-row">
    <input type="search" name="search" value="<?php echo esc_attr($search_term); ?>" placeholder="🔍 Skriv namn">
    <select name="city" id="city">
        <option value="" <?php selected($selected_city, ''); ?>>Alla orter</option>
        <?php foreach ($available_cities as $city) : ?>
            <option value="<?php echo esc_attr($city); ?>" <?php selected($selected_city, $city); ?>>
                <?php echo esc_html($city); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="green small">Sök</button>
</div>
</form>

<!-- Search result -->
<h3>📋 Sökresultat</h3>
<div class="columns">
    <div class="column1">
        ↓ <?php echo (int) $count; ?> användare
    </div>
    <div class="column2 small">💡 Senaste överst</div>
</div>
<hr>

<div class="post-list">
    <?php if (!$has_search) : ?>
        <p>💡 Skriv ett namn eller välj en ort.</p>
    <?php elseif (!empty($search_results)) : ?>
        <?php foreach ($search_results as $user) : ?>
          
          <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-card.php'; ?>

        <?php endforeach; ?>
    <?php else : ?>
        <p>💢 Inga användare hittades.</p>
    <?php endif; ?>
</div>
