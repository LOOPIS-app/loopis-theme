<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
} ?>

<!--HEADER-->
<div class="columns"><div class="column1"><h1>🐙 Admin</h1></div>
	<div class="column2 bottom"></div></div> 
    <hr>
<p class="small">💡 Visar verktyg tillgängliga för <span class="small-link"><a href="/profile/">👤<?php echo wp_get_current_user()->user_login; ?></a></span></p>

<!--CONTENT-->
<!-- Statistics -->
<div class="wrapped link" onclick="location.href='/admin/stats'">
<h5>📊 Statistik</h5>
<hr>
<p class="small">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/dashboard/gift-stats.php'; ?>
</p>
</div>

<!-- Raffle results -->
<div class="wrapped link" onclick="location.href='/admin/raffle'">
<h5>🎲 Lottning</h5>
<hr>
<p class="small">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/dashboard/raffle-results.php'; ?>
</p>
</div>

<!-- Reminders -->
<div class="wrapped link" onclick="location.href='/admin/reminders'">
<h5>⏰ Påminnelser</h5>
<hr>
<p class="small">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/dashboard/gift-traffic.php'; ?>
</p>
</div>

<!-- Locker traffic -->
<div class="wrapped link" onclick="location.href='/admin/locker-traffic '">
<h5>🔐 Trafik i skåp</h5>
<hr>
<p class="small">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/dashboard/locker-traffic.php'; ?>
</p>
</div>

<!-- App traffic -->
<div class="wrapped">
<h5>📲 Trafik i app</h5>
<hr>
<p class="small">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/dashboard/app-traffic.php'; ?>
</p>
</div>

<!-- Gift archive count -->
<div class="wrapped link" onclick="location.href='/admin/archive '">
<h5>🕸 Arkivet</h5>
<hr>
<p class="small">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/dashboard/gift-archive-stats.php'; ?>
</p>
</div>

<!-- Comment count -->
<div class="wrapped link" onclick="location.href='/admin/comments'">
<h5>🗨 Kommentarer</h5>
<hr>
<p class="small">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/dashboard/comment-stats.php'; ?>
</p>
</div>

<!-- Pending members count -->
<?php if (current_user_can('manager') || current_user_can('administrator')) { ?>
<div class="wrapped link" onclick="location.href='/admin/activation'">
<h5>⚡ Nya konton</h5>
<hr>
<p class="small">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/dashboard/members-pending.php'; ?>
</p>
</div>
<?php } ?>

<!-- Active support count -->
<?php if (current_user_can('loopis_support')) { ?>
<div class="wrapped link" onclick="location.href='/admin/support'">
<h5>🛟 Support</h5>
<hr>
<p class="small">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/dashboard/support-active.php'; ?>
</p>
</div>
<?php } ?>

<!-- Economy -->
<?php if (current_user_can('loopis_economy')) { ?>
<h3>💰 Ekonomi</h3>
<hr>
<div>
	<span class="big-link"><a href="/admin/payments">📒 Alla köp</a></span>&nbsp;
	<span class="big-link"><a href="/admin/coins">🪙 Köp av mynt</a></span>&nbsp;
</div>
<?php } ?>

<!-- Member info -->
<?php if (current_user_can('board_member') || current_user_can('administrator')) { ?>
<h3>👤 Medlemsinfo</h3>
<hr>
<div>
	<span class="big-link"><a href="/admin/registry">🗃 Medlemsregister</a></span>&nbsp;
	<span class="big-link"><a href="/admin/members/email">✉ Epost-adresser</a></span>&nbsp;
	<span class="big-link"><a href="/admin/members/reward">🙏 Belöna</a></span>&nbsp;
	<span class="big-link"><a href="/admin/members/rewards">🌟 Belöningar</a></span>&nbsp;
</div>
<?php } ?>

<!-- Communication -->
<h3>📡 Kommunikation</h3>
<hr>
<div>
	<span class="big-link"><a href="/admin/collage">🖼 Kollage</a></span>&nbsp;
</div>

<!-- Super-admin -->
<?php if (current_user_can('administrator')) { ?>
<h3>👽 Superadmin</h3>
<hr>
<div>
	<span class="big-link"><a href="/admin/test">💣 Test</a></span>&nbsp;
	<span class="big-link"><a href="/admin/custom-location">📍 Annan adress</a></span>&nbsp;
	<span class="big-link"><a href="/admin/metadata">🧮 Metadata</a></span>&nbsp;
	<span class="big-link"><a href="/wp-admin">👩‍💻 WP-admin</a></span>&nbsp;
	<span class="big-link"><a href="/profile/">👤 Profil</a></span>&nbsp;
	<span class="big-link"><a href="../../wp-login.php?action=logout">🚪 Logga ut</a></span>
</div>
<?php } ?> 

<!-- Access list -->
<?php if (current_user_can('administrator')) { ?>
<p>&nbsp;</p><div class="wrapped">
<h5>🚧 Vilka har tillgång?</h5>
<hr>
<p class="small">Superadmin: 
<?php $users = get_users( array( 'role' => 'administrator' ) );
foreach ( $users as $user ) {
    $user_first_name = get_user_meta( $user->ID, 'first_name', true );
    $user_last_name = get_user_meta( $user->ID, 'last_name', true );
    $author_link = get_author_posts_url( $user->ID );

    echo '<a href="' . $author_link . '">👽' . $user_first_name . '</a> &nbsp;';
}
?>
</p>
<p class="small">Managers: 
<?php $users = get_users( array( 'role' => 'manager' ) );
foreach ( $users as $user ) {
    $user_first_name = get_user_meta( $user->ID, 'first_name', true );
    $user_last_name = get_user_meta( $user->ID, 'last_name', true );
    $author_link = get_author_posts_url( $user->ID );

    echo '<a href="' . $author_link . '">👤' . $user_first_name . ' ' . $user_last_name . '</a> &nbsp;';
}
?>
</p>
<p class="small">Styrelsen: 
<?php $users = get_users( array( 'role' => 'board_member' ) );
foreach ( $users as $user ) {
    $user_first_name = get_user_meta( $user->ID, 'first_name', true );
    $user_last_name = get_user_meta( $user->ID, 'last_name', true );
    $author_link = get_author_posts_url( $user->ID );

    echo '<a href="' . $author_link . '">👤' . $user_first_name . ' ' . $user_last_name . '</a> &nbsp;';
}
?>
</p>
</div>
<?php } ?> 