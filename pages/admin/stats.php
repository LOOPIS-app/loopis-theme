<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php $admin_path = home_url('/admin/'); ?>
<h1>📊 Statistik</h1>
<hr>
<p class="small">💡 Välj vilken statistik du vill se.</p>

<p><span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/posts'), $admin_path) ); ?>">🎁 Annonser</a></span>
&emsp;<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/tags'), $admin_path) ); ?>">#⃣ Kategorier</a></span>
&emsp;<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/current'), $admin_path) ); ?>">⌚ Just nu</a></span></p>

<p><span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/members'), $admin_path) ); ?>">👤 Medlemmar</a></span>
&emsp;<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/highscore'), $admin_path) ); ?>">🥇 Topplistor</a></span>
&emsp;<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/demography'), $admin_path) ); ?>">👯 Demografi</a></span>
&emsp;<span class="link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/max-murpos'), $admin_path) ); ?>">🕵 Max Murpos</a></span></p>

<p><span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/charts'), $admin_path) ); ?>">📈 Diagram</a></span></p>

<p><span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/support'), $admin_path) ); ?>">🛟 Support</a></span>

<p><span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/weekly'), $admin_path) ); ?>">✉ Veckobrev</a></span></p>

<p><span class="mega-link"><a href="<?php echo esc_url( add_query_arg(array('view' => 'stats/yearly'), $admin_path) ); ?>">🎆 Wrapped</a></span></p>