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
?>

<div class="columns">
    <div class="column1"><h1>🦀 Admin</h1></div>
    <div class="column2"></div>
</div>
<hr>
<p class="small">💡 Visar verktyg tillgängliga för <span class="small-label">👤<?php echo wp_get_current_user()->user_login; ?></span></p>

<!-- Statistics -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'pages/stats/overview', $admin_url) ); ?>'">
    <h5>📊 Statistik</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/stats.php'; ?>
    </p>
</div>

<!-- Raffle -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'pages/raffle', $admin_url) ); ?>'">
    <h5>🎲 Lottning</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/raffle.php'; ?>
    </p>
</div>

<!-- Locker -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'pages/locker', $admin_url) ); ?>'">
    <h5><i class="fas fa-walking"></i> Skåpet</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/locker.php'; ?>
    </p>
</div>

<!-- Reminders -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'pages/reminders', $admin_url) ); ?>'">
    <h5>⏰ Påminnelser</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/reminders.php'; ?>
    </p>
</div>

<!-- Users -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'pages/users', $admin_url) ); ?>'">
    <h5>👤 Nya användare</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/users.php'; ?>
    </p>
</div>

<!-- Support -->
<div class="wrapped link" style="min-width:250px; max-width: 500px;" onclick="location.href='<?php echo get_home_url( null, '/support' ); ?>'">
    <h5>🛟 Support</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/support.php'; ?>
    </p>
</div>

<!-- Comments-->
<h3>🗨 Kommentarer</h3>
<?php include __DIR__ . '/panels/comments.php'; ?>

<!-- Search Section -->
<h3>🔍 Sök</h3>
<hr>
<div class="span-list">
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/search-post', $admin_url) ); ?>">🎁 Sök annons</a></span>
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/search-member', $admin_url) ); ?>">👤 Sök användare</a></span>
</div>

<!-- More Section -->
<h3>🛠 Mer</h3>
<hr>
<div class="span-list">
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/settings-admin', $admin_url) ); ?>">⚙️ Inställningar</a></span>
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/inventory', $admin_url) ); ?>">📋 Inventering</a></span> 
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/collage', $admin_url) ); ?>">🖼 Kollage</a></span>
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/ledger', $admin_url) ); ?>">📕 Loggbok</a></span>
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/tips', $admin_url) ); ?>">💡 Tips</a></span>
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/mail', $admin_url) ); ?>">💌 Mail</a></span>
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/access', $admin_url) ); ?>">🚧 Tillgång</a></span>
</div>

<!-- Webmaster Section -->
<?php if ( current_user_can('manage_options') ) : ?>
<div class="admin-block">
<h5>😈 Webmaster</h5>
<hr>
<div class="span-list">
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/settings-webmaster', $admin_url) ); ?>">⚙️ Inställningar</a></span>
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'webmaster/test', $admin_url) ); ?>">💣 Testsida</a></span>
    <span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/email-list', $admin_url) ); ?>">✉ Epost-adresser</a></span>
    <span class="mega-link"><a href="https://loopis.app/12845/?p=X">🎁 URL för post_id X</a></span>
    <span class="mega-link"><a href="https://loopis.app/12845/?author=X">👤 URL för user_id X</a></span>
    <span class="mega-link"><a href="<?php echo esc_url( admin_url() )?>">👩‍💻 Gå till WP-admin</a></span>
</div>

</div>
<?php endif; ?>