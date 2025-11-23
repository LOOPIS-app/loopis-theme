<?php
/**
 * Raffle overview page
 * Shows posts ready for raffle or raffled today
 * Allows manual raffle execution + individual overrides
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🎲 Lottning</h1>
<hr>
<p class="small">💡 Här ser du resultaten av lottning idag och imorgon.</p>

<?php
// Enqueue tabs script
wp_enqueue_script('loopis-tabs', get_template_directory_uri() . '/assets/js/tabs.js', array(), '1.0.0', true);

// Calculate dates
$current_time = new DateTime(current_time('mysql'));

// Yesterday
$yesterday = clone $current_time;
$yesterday->modify('-1 day');
$yesterday_start = $yesterday->format('Y-m-d 00:00:00');
$yesterday_end = $yesterday->format('Y-m-d 23:59:59');

// Today
$today = clone $current_time;
$today_start = $today->format('Y-m-d 00:00:00');
$today_end = $today->format('Y-m-d 23:59:59');

// Check if today's raffle is completed
$complete_args = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'cat'            => loopis_cat('new'),
    'date_query'     => array(
        array(
            'after'     => $yesterday_start,
            'before'    => $yesterday_end,
            'inclusive' => true,
        ),
    ),
);

$complete_query = new WP_Query($complete_args);
$complete_count = $complete_query->found_posts;
wp_reset_postdata();
?>

<!-- Tab Navigation -->
<div class="tab-nav">
    <nav class="profile-navbar">
        <a href="#" class="tab-link" data-tab="tab-today">
            <?php echo ($complete_count == 0) ? '✅' : '⌛'; ?> Idag
        </a>
        <a href="#" class="tab-link" data-tab="tab-tomorrow">⏳ Imorgon</a>
    </nav>
</div>

<!-- Tab Content -->
<div class="tab-content">

    <!-- Today's Raffle -->
    <div id="tab-today" class="tab-panel">
        <?php
        include LOOPIS_THEME_DIR . '/pages/admin/raffle/raffle-today.php';
        ?>
    </div>

    <!-- Tomorrow's Raffle -->
    <div id="tab-tomorrow" class="tab-panel">
        <?php
        include LOOPIS_THEME_DIR . '/pages/admin/raffle/raffle-tomorrow.php';
        ?>
    </div>

</div>