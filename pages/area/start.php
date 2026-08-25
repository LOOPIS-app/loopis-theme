<?php
/**
 * Overview for area pages
 *
 * Dynamic content of page-area.php
 * Reached on /area (this view is set as default)
 */

if (!defined('ABSPATH')) {
    exit;
}
// Set the base URL for area views
$area = home_url('/area/');
?>

<h1>📍 <?php echo esc_html(get_bloginfo('name')); ?></h1>
<hr>
<p class="small">💡 Information och support i ditt område.</p>
  
<h3>👇 Hitta rätt</h3>
<hr>
<p><span class="bold">1⃣ Problem med annons?</span> → <span class="big-label">🔔 pinga @admin</span> i en kommentar</p>
<p><span class="bold">2⃣ Frågor om skåpet?</span> → <span class="big-link"><a href="<?php echo home_url( '/locker' ); ?>">⏹ Skåpet</a></span></p>
<p><span class="bold">3⃣ Frågor om LOOPIS?</span> → <span class="big-link"><a href="<?php echo network_home_url( '/faq' ); ?>">💡 Frågor & svar</a></span></p>
<p><span class="bold">4⃣ Vad är på gång?</span> → <span class="big-link"><a href="<?php echo home_url( '/news' ); ?>">📡 Nyheter</a></span></p>
<p><span class="bold">5⃣ Övriga frågor och support</span> → <span class="big-link"><a href="<?php echo home_url( '/forum' ); ?>">🗣 Forum</a></span></p>
<p>&nbsp;</p>

<!-- FAQ -->
<div class="wrapped link" onclick="location.href='<?php echo network_home_url( '/faq' ); ?>'">
    <h5>💡 Frågor & svar</h5>
    <?php include LOOPIS_THEME_DIR . '/pages/area/panels/faq-examples.php'; ?>
</div>

<!-- Forum -->
<div class="wrapped link" style="max-width: 500px;" onclick="location.href='<?php echo get_home_url( null, '/forum' ); ?>'">
    <h5>🗣 Forum</h5>
    <?php include LOOPIS_THEME_DIR . '/pages/area/panels/forum-latest.php'; ?>
</div>

<!-- News -->
<div class="wrapped link" style="max-width: 500px;" onclick="location.href='<?php echo get_home_url( null, '/news' ); ?>'">
    <h5>📡 Nyheter</h5>
    <?php include LOOPIS_THEME_DIR . '/pages/area/panels/news-latest.php'; ?>
</div>

<!-- Admin -->
<div class="wrapped link small" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'admin', $area ) ); ?>'">
<h5><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/user_manager.png" alt="Admin" style="height:30px; width: auto; vertical-align: middle; margin-bottom: 4px;"> Admin</h5>
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/admins.php'; ?>
</div>

<!-- Statistics -->
<div class="wrapped small">
<h5>📊 Statistik</h5>
<hr>
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/stats.php'; ?></p>
</div>
