<?php
/**
 * Page for webmaster testing!
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>💣 Test</h1>
<hr>
<p class="small">💡 Test av nya funktioner</p>

<p><b>⚠ VARNING!</b> Kontrollera koden i test.php</p>

<!--Test button-->
<?php if (current_user_can('administrator')) { ?>
<div class="wrapped admin-block">
		<?php if(isset($_POST['start_test'])) { test_function(); } ?>
		<form method="post" class="arb" action=""><button name="start_test" type="submit" class="purple small" onclick="return confirm('Vill du testa funktionen?')">🤖 Testa funktion...</button></form>
		<p class="info">Tryck på knappen för att testa funktionen.</p>
</div>
<?php } ?>

<?php
function test_function() {
    echo "<p>Funktionen har körts!</p>";
}