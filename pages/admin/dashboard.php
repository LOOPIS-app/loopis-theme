<?php
/**
 * ADMIN DASHBOARD (page-admin.php)
 * Overview with statistics, tools, and quick links
 */

if (!defined('ABSPATH')) {
    exit;
}
$admin_url = home_url('/admin/');

?>

<div class="columns">
    <div class="column1">
        <h1>🐙 Admin</h1>
    </div>
    <div class="column2 bottom"></div>
</div>
<hr>
<p class="small">💡 Visar verktyg tillgängliga för <span class="small-link"><a href="<?php echo esc_url( home_url('/profile/') ); ?>">👤<?php echo wp_get_current_user()->user_login; ?></a></span></p>

<!-- Dashboard Cards -->

<!-- Statistics -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'stats', $admin_url) ); ?>'">
    <h5>📊 Statistik</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/gift-stats.php'; ?>
    </p>
</div>

<!-- Raffle results -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'raffle', $admin_url) ); ?>'">
    <h5>🎲 Lottning</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/raffle-results.php'; ?>
    </p>
</div>

<!-- Reminders -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'traffic-gifts', $admin_url) ); ?>'">
    <h5>⏰ Påminnelser</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/traffic-gifts.php'; ?>
    </p>
</div>

<!-- Locker traffic -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'traffic-locker', $admin_url) ); ?>'">
    <h5>🔐 Trafik i skåp</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/traffic-locker.php'; ?>
    </p>
</div>

<!-- App traffic -->
<div class="wrapped">
    <h5>📲 Trafik i app</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/traffic-app.php'; ?>
    </p>
</div>

<!-- Archive count -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'archive', $admin_url) ); ?>'">
    <h5>🕸 Arkivet</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/archive-stats.php'; ?>
    </p>
</div>

<!-- Comment count -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'comments', $admin_url) ); ?>'">
    <h5>🗨 Kommentarer</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/comment-stats.php'; ?>
    </p>
</div>

<!-- Pending members count -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_admin')) : ?>
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'activation', $admin_url) ); ?>'">
        <h5>👥 Nya medlemmar</h5>
        <hr>
        <p class="small">
            <?php include __DIR__ . '/dashboard-blocks/members-pending.php'; ?>
        </p>
    </div>
<?php endif; ?>

<!-- Active support count -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_admin')) : ?>
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'support', $admin_url) ); ?>'">
        <h5>🛟 Support</h5>
        <hr>
        <p class="small">
            <?php include __DIR__ . '/dashboard-blocks/support-active.php'; ?>
        </p>
    </div>
<?php endif; ?>

<!-- Settings Section -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_admin')) : ?>
    <div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'settings', $admin_url) ); ?>'">
        <h5>⚙ Inställningar</h5>
        <hr>
        <p class="small">
            <?php include __DIR__ . '/dashboard-blocks/settings-status.php'; ?>
        </p>
    </div>
<?php endif; ?>

<!-- Economy Section -->
<?php if (current_user_can('loopis_economy')) : ?>
    <h3>💰 Ekonomi</h3>
    <hr>
    <div>
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'economy/payments', $admin_url) ); ?>">📒 Alla köp</a></span>&nbsp;
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'economy/coins', $admin_url) ); ?>">🪙 Köp av mynt</a></span>&nbsp;
    </div>
<?php endif; ?>

<!-- Manager Section -->
<h3>🤓 Admin (manager)</h3>
<hr>
<div>
    <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'manager/inventory', $admin_url) ); ?>">📋 Inventering</a></span>&nbsp;
    <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'manager/post-search', $admin_url) ); ?>">🎁 Sök annons</a></span>&nbsp;
</div>

<!-- Member Info Section -->
<?php if (current_user_can('board') ) : ?>
    <h3>👤 Medlemsinfo</h3>
    <hr>
    <div>
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'members/registry', $admin_url) ); ?>">🗃 Medlemsregister</a></span>&nbsp;
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'members/email-list', $admin_url) ); ?>">✉ Epost-adresser</a></span>&nbsp;
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'members/reward', $admin_url) ); ?>">🙏 Belöna</a></span>&nbsp;
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'members/rewards', $admin_url) ); ?>">🌟 Belöningar</a></span>&nbsp;
    </div>
<?php endif; ?>

<!-- Special Section -->
<h3>📡 Special</h3>
<hr>
<div>
    <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'special/collage', $admin_url) ); ?>">🖼 Kollage</a></span>&nbsp;
    <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'special/uncategorized', $admin_url) ); ?>">❤️‍🩹 Annonser utan kategori</a></span>&nbsp;
</div>

<!-- Webmaster Section -->
<?php if (current_user_can('manage_options') || current_user_can('develooper')) : ?>
    <h3>👽 Wordpress administrator</h3>
    <hr>
    <div>
        <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'webmaster/test', $admin_url) ); ?>">💣 Testsida</a></span>&nbsp;
        <span class="big-link"><a href="<?php echo esc_url( admin_url() )?>">👩‍💻 Gå till WP-admin</a></span>&nbsp;
        <span class="big-link"><a href="<?php echo esc_url( home_url('/profile/') ); ?>">👤 Gå till profilsida</a></span>&nbsp;
        <span class="big-link"><a href="<?php echo esc_url( wp_logout_url( home_url('/') ) ); ?>">🚪 Logga ut</a></span>
    </div>
<?php endif; ?>

<!-- Access List -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_admin')) : ?>
    <p>&nbsp;</p>
    <div class="wrapped">
        <h5>🚧 Vilka har tillgång?</h5>
        <hr>
        <?php include __DIR__ . '/dashboard-blocks/access.php'; ?>
    </div>
<?php endif; ?>