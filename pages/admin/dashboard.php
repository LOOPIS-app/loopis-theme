<?php
/**
 * ADMIN DASHBOARD (page-admin.php)
 * Overview with statistics, tools, and quick links
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="columns">
    <div class="column1">
        <h1>🐙 Admin</h1>
    </div>
    <div class="column2 bottom"></div>
</div>
<hr>
<p class="small">💡 Visar verktyg tillgängliga för <span class="small-link"><a href="/profile/">👤<?php echo wp_get_current_user()->user_login; ?></a></span></p>

<!-- Dashboard Cards -->

<!-- Statistics -->
<div class="wrapped link" onclick="location.href='/admin/?view=stats'">
    <h5>📊 Statistik</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/gift-stats.php'; ?>
    </p>
</div>

<!-- Raffle results -->
<div class="wrapped link" onclick="location.href='/admin/?view=raffle'">
    <h5>🎲 Lottning</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/raffle-results.php'; ?>
    </p>
</div>

<!-- Reminders -->
<div class="wrapped link" onclick="location.href='/admin/?view=traffic-gifts'">
    <h5>⏰ Påminnelser</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/traffic-gifts.php'; ?>
    </p>
</div>

<!-- Locker traffic -->
<div class="wrapped link" onclick="location.href='/admin/?view=traffic-locker'">
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
<div class="wrapped link" onclick="location.href='/admin/?view=archive'">
    <h5>🕸 Arkivet</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/archive-stats.php'; ?>
    </p>
</div>

<!-- Comment count -->
<div class="wrapped link" onclick="location.href='/admin/?view=comments'">
    <h5>🗨 Kommentarer</h5>
    <hr>
    <p class="small">
        <?php include __DIR__ . '/dashboard-blocks/comment-stats.php'; ?>
    </p>
</div>

<!-- Pending members count -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_admin')) : ?>
    <div class="wrapped link" onclick="location.href='/admin/?view=activation'">
        <h5>👥 Nya medlemmar</h5>
        <hr>
        <p class="small">
            <?php include __DIR__ . '/dashboard-blocks/members-pending.php'; ?>
        </p>
    </div>
<?php endif; ?>

<!-- Active support count -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_admin')) : ?>
    <div class="wrapped link" onclick="location.href='/admin/?view=support'">
        <h5>🛟 Support</h5>
        <hr>
        <p class="small">
            <?php include __DIR__ . '/dashboard-blocks/support-active.php'; ?>
        </p>
    </div>
<?php endif; ?>

<!-- Settings Section -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_admin')) : ?>
    <div class="wrapped link" onclick="location.href='/admin/?view=settings'">
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
        <span class="big-link"><a href="/admin/?view=economy/payments">📒 Alla köp</a></span>&nbsp;
        <span class="big-link"><a href="/admin/?view=economy/coins">🪙 Köp av mynt</a></span>&nbsp;
    </div>
<?php endif; ?>

<!-- Manager Section -->
<h3>🤓 Admin (manager)</h3>
<hr>
<div>
    <span class="big-link"><a href="/admin/?view=manager/inventory">📋 Inventering</a></span>&nbsp;
    <span class="big-link"><a href="/admin/?view=manager/post-search">🎁 Sök annons</a></span>&nbsp;
</div>

<!-- Member Info Section -->
<?php if (current_user_can('board') ) : ?>
    <h3>👤 Medlemsinfo</h3>
    <hr>
    <div>
        <span class="big-link"><a href="/admin/?view=members/registry">🗃 Medlemsregister</a></span>&nbsp;
        <span class="big-link"><a href="/admin/?view=members/email-list">✉ Epost-adresser</a></span>&nbsp;
        <span class="big-link"><a href="/admin/?view=members/reward">🙏 Belöna</a></span>&nbsp;
        <span class="big-link"><a href="/admin/?view=members/rewards">🌟 Belöningar</a></span>&nbsp;
    </div>
<?php endif; ?>

<!-- Special Section -->
<h3>📡 Special</h3>
<hr>
<div>
    <span class="big-link"><a href="/admin/?view=special/collage">🖼 Kollage</a></span>&nbsp;
    <span class="big-link"><a href="/admin/?view=special/uncategorized">❤️‍🩹 Annonser utan kategori</a></span>&nbsp;
</div>

<!-- Webmaster Section -->
<?php if (current_user_can('manage_options') || current_user_can('develooper')) : ?>
    <h3>👽 Wordpress administrator</h3>
    <hr>
    <div>
        <span class="big-link"><a href="/admin/?view=webmaster/test">💣 Testsida</a></span>&nbsp;
        <span class="big-link"><a href="/wp-admin">👩‍💻 Gå till WP-admin</a></span>&nbsp;
        <span class="big-link"><a href="/profile/">👤 Gå till profilsida</a></span>&nbsp;
        <span class="big-link"><a href="../../wp-login.php?action=logout">🚪 Logga ut</a></span>
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