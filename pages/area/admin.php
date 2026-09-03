<?php
/**
 * Admin information for area
 * 
 * Dynamic content of page-area.php
 * Reached on /area/?view=admin
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/user_manager.png" alt="Admin" style="height:40px; width: auto; vertical-align: middle; margin-bottom: 6px;"> Admin </h1>
<hr>
<p class="small">💡 Information om admin.</p>
 
<p>Admins är volontärer i ditt område som hjälper till att hålla ordning i app och skåp.</p>

<h3>🗒 Uppgifter för admin</h3>
<hr>
<div class="wrapped small">
<ul>
    <li>Svara på frågor och hjälpa till där det behövs.</li>
    <li>Hålla ordning och städa i skåpet.</li>
    <li>Skicka sms-påminnelser när saker inte har hämtats efter 5 dagar.</li>
    <li>Försöka hitta saker som hamnat fel → <span class="link white"><a href="<?php echo get_home_url( null, '/category/disappeared//' ); ?>">💢 Försvunnen</a></span></li>
    <li>Ta bort saker ur skåpet som inte har paxats → <span class="link white"><a href="<?php echo get_home_url( null, '/category/extracted/' ); ?>">🧹 Bortplockad</a></span></li>
    <li>Ta bort olämpliga annonser → <span class="link white"><a href="<?php echo network_home_url( '/faq/restriktioner' ); ?>">📌 Restriktioner</a></span></li>
</ul>
</div>

<h3>💞 Kontakta admin</h3>
<hr>
<div class="wrapped small">
<ul>
    <li>Du kan alltid <span class="label white">🔔 pinga @admin</span> i ett kommentarsfält.</li>
    <li>För frågor som inte gäller en annons: <span class="link white"><a href="<?php echo home_url( '/support' ); ?>">🛟 Supportforum</a></span></li>
    <li>För privata frågor, maila <span class="link white">✉ <a href="mailto:<?php echo get_bloginfo('admin_email'); ?>"><?php echo get_bloginfo('admin_email'); ?></a></span></li>  
</ul>
</div>

<h3>📍 Vilka är admin?</h3>
<hr>
<p>Du känner igen en admin i kommentarsfältet på glasögonen. Följande medlemmar är admins i ditt område:</p>
<?php
// Get all users with role "manager"
$users = get_users(array('role' => 'manager'));
$count = count($users); ?>

<!-- Output -->
<?php
foreach ($users as $user) {
    $user_first_name = get_user_meta($user->ID, 'first_name', true);
    $user_last_name = get_user_meta($user->ID, 'last_name', true);
    $author_link = get_author_posts_url($user->ID);
	echo '<p><span class="big-link"><a href="' . esc_url($author_link) . '">👤 ' . esc_html($user_first_name . ' ' . $user_last_name) . '</a></p>';
}
?>

<!-- Lost items -->
<h3>🪂 Saker på vift</h3>
<hr>
<!-- Things disappeared -->
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/disappeared-latest.php'; ?>

<!-- Things cleaned out -->
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/extracted-latest.php'; ?>

<!-- Things complained about -->
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/complaint-latest.php'; ?>
