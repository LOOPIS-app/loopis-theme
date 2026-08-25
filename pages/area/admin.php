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
<p class="small">💡 Information om admin i ditt område.</p>
 
<p>Admins är medlemmar som jobbar volontärt med att hålla ordning i app och skåp.</p>

<h3>🗒 Admins uppgifter</h3>
<hr>
<ul>
    <li>Svara på frågor och hjälpa till där det behövs.</li>
    <li>Hålla ordning och städa i skåpet.</li>
    <li>Skicka sms-påminnelser när saker inte har hämtats efter 5 dagar.</li>
    <li>Försöka hitta saker som hamnat fel → <span class="big-link"><a href="<?php echo get_home_url( null, '/category/disappeared//' ); ?>">💢 Försvunnen</a></span></li>
    <li>Ta bort saker ur skåpet som inte har paxats → <span class="big-link"><a href="<?php echo get_home_url( null, '/category/extracted/' ); ?>">🧹 Bortplockad</a></span></li>
    <li>Ta bort olämpliga annonser → <span class="big-link"><a href="<?php echo network_home_url( '/faq/restriktioner' ); ?>">📌 Restriktioner</a></span></li>
</ul>

<h3>🔔 Kontakta admin</h3>
<hr>
<p>Om du behöver hjälp med en annons: pinga @admin i kommentarsfältet!</p>
<p>Om du har andra frågor, funderingar och feedback: skapa en tråd i <span class="big-link"><a href="<?php echo home_url( '/support' ); ?>">🗣 Forum</a></span></p>
<p>Om ditt ärende är privat kan du maila: <span class="big-link">✉ <a href="mailto:<?php echo get_bloginfo('admin_email'); ?>"><?php echo get_bloginfo('admin_email'); ?></a></span></p>

<h3>📍 Vilka är admin?</h3>
<hr>
<p>Just nu är följande medlemmar admins i ditt område:</p>

<div class="wrapped">
<h5><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/user_manager.png" alt="Admin" style="height:30px; width: auto; vertical-align: middle; margin-bottom: 4px;"> Admin</h5>
<?php include LOOPIS_THEME_DIR . '/pages/area/panels/admins.php'; ?>
</div>