<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php $admin_url = home_url('/admin/'); ?>

<h1>📊 Statistik</h1>
<hr>
<p class="small">💡 Välj vilken statistik du vill se.</p>

<!-- Last week -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'pages/stats/pages/posts', $admin_url) ); ?>'">
    <h5>🗓️ Senaste veckan</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/../../panels/stats.php'; ?>
    </p>
</div>

<!-- Locker traffic -->
<div class="wrapped link" onclick="location.href='<?php echo esc_url( add_query_arg('view', 'pages/stats/pages/traffic-locker', $admin_url) ); ?>'">
    <h5>🔐 Trafik i skåp</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/traffic-locker.php'; ?>
    </p>
</div>

<!-- App traffic -->
<div class="wrapped">
    <h5>📲 Trafik i app</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/traffic-app.php'; ?>
    </p>
</div>

<!-- Support -->
<div class="wrapped link" onclick="location.href='<?php echo get_post_type_archive_link('support'); ?>'">
    <h5>🛟 Supportforum</h5>
    <hr>
    <p>
        <?php include __DIR__ . '/panels/support.php'; ?>
    </p>
</div>

<div class="span-list">
<span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/posts'), $admin_url) ); ?>">🎁 Annonser</a></span>
<span class="link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/stats/pages/categories', $admin_url) ); ?>">🚦 Kategorier (status)</a></span>
<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/tags'), $admin_url) ); ?>">#⃣ Taggar (kategorier)</a></span>
<span class="link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/stats/pages/archived', $admin_url) ); ?>">🕸 Inaktiva</a></span>
<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/current'), $admin_url) ); ?>">⌚ Just nu</a></span>
<br>
<span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/users'), $admin_url) ); ?>">👤 Användare</a></span>
<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/highscore'), $admin_url) ); ?>">🥇 Topplistor</a></span>
<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/demography'), $admin_url) ); ?>">👯 Demografi</a></span>
<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/max-murpos'), $admin_url) ); ?>">🕵 Max Murpos</a></span>
<br>
<span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/charts'), $admin_url) ); ?>">📈 Diagram</a></span>
<br>
<span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/admin'), $admin_url) ); ?>"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/admin.png" alt="Admin" class="symbol"> Admin</a></span>
<br>
<span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/weekly'), $admin_url) ); ?>">✉ Veckobrev</a></span>
<br>
<span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'pages/stats/pages/yearly'), $admin_url) ); ?>">🎆 Wrapped</a></span>
</div>
