<?php
/**
 * Overview for admin pages
 *
 * Dynamic content of page-admin.php
 * Reached on /admin (this view is set as default)
 */

if (!defined('ABSPATH')) {
    exit;
}

// Set the base URL for admin views
$admin_url = home_url('/admin/');
?>

<div class="columns">
    <div class="column1">
        <h1>🦀 Admin</h1>
    </div>
    <div class="column2"></div>
</div>
<hr>
<p class="small">💡 Du är inloggad som <span class="small-link"><a href="<?php echo esc_url( home_url('/user/') ); ?>">👤<?php echo wp_get_current_user()->user_login; ?></a></span></p>

<!-- Statistics -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'stats', $admin_url) ); ?>'">
    <h5>📊 Statistik</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/panels/gift-stats.php'; ?>
    </p>
</div>
<!-- The book -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'ledger', $admin_url) ); ?>'">
    <h5>📕 Lokala boken</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/panels/ledger.php'; ?>
    </p>
</div>

<!-- Raffle results -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'raffle', $admin_url) ); ?>'">
    <h5>🎲 Lottning</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/panels/raffle-results.php'; ?>
    </p>
</div>

<!-- Reminders -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'traffic-gifts', $admin_url) ); ?>'">
    <h5>⏰ Påminnelser</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/panels/traffic-gifts.php'; ?>
    </p>
</div>

<!-- Locker traffic -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'traffic-locker', $admin_url) ); ?>'">
    <h5>🔐 Trafik i skåp</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/panels/traffic-locker.php'; ?>
    </p>
</div>

<!-- App traffic -->
<div class="wrapped">
    <h5>📲 Trafik i app</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/panels/traffic-app.php'; ?>
    </p>
</div>

<!-- Archive count -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'archive', $admin_url) ); ?>'">
    <h5>🕸 Arkivet</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/panels/archive-stats.php'; ?>
    </p>
</div>

<!-- Comment count -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'comments', $admin_url) ); ?>'">
    <h5>🗨 Kommentarer</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/panels/comment-stats.php'; ?>
    </p>
</div>

<!-- Active support count -->
    <div class="wrapped link" onclick="location.href='<?php echo get_post_type_archive_link('support'); ?>'">
        <h5>🛟 Support</h5>
        <hr>
        <p class="small">
            <?php include __DIR__ . '/panels/support-active.php'; ?>
        </p>
    </div>

<!-- Settings Section -->
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'settings', $admin_url) ); ?>'">
        <h5>⚙ Inställningar</h5>
        <hr>
        <p class="small">
            <?php include __DIR__ . '/panels/settings-status.php'; ?>
        </p>
    </div>


<!-- More Section -->
<h3>🛠 Mer</h3>
<hr>
<div>
    <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'more/post-search', $admin_url) ); ?>">🔍 Alla annonser</a></span>&nbsp;
    <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'more/inventory', $admin_url) ); ?>">📋 Inventering i skåpet</a></span>&nbsp;
    <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'more/collage', $admin_url) ); ?>">🖼 Kollage</a></span>&nbsp;
    <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'more/uncategorized', $admin_url) ); ?>">❤️‍🩹 Annonser utan kategori</a></span>&nbsp;
    <span class="big-link"><a href="<?php echo esc_url( admin_url() )?>">👩‍💻 Gå till WP-admin</a></span>&nbsp;
</div>

<!-- Webmaster Section -->
<?php if ( current_user_can('manage_options') ) : ?>
<h3>😈 Webmaster</h3>
<hr>
<div>
    <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'webmaster/test', $admin_url) ); ?>">💣 Testsida</a></span>&nbsp;
</div>
<?php endif; ?>

<!-- Access List -->
    <p>&nbsp;</p>
    <div class="wrapped">
        <h5>🚧 Vilka har tillgång?</h5>
        <hr>
        <?php include __DIR__ . '/panels/access.php'; ?>
    </div>