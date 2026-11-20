<?php
/**
 * Form for subsite settings (activity/area)
 * 
 * So far only contains mail settings
 * 
 * Dynamic content of page-activity.php
 * Reached on /activity/?view=settings
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>✉️ Mailutskick</h1>
<hr>
<p class="small">💡 Inställningar för mailutskick från LOOPIS.</p>

<h3>Jag vill ha:</h3>
<hr>
<div class="loopis-form-wrapper">
    <form class="loopis-form" method="post" action="" novalidate>

    <div class="form-row">
		<label for="tips"><input type="checkbox" id="tips" name="tips" value="yes">🎁 Tips på nya annonser</label>
        <p class="description" style="margin-left: 27px;">💡 Skickas av admin i ditt område.</p>
    </div>

    <div class="form-row">
      <label for="pings"><input type="checkbox" id="pings" name="pings" value="yes" checked disabled>🔔 Notifikationer för annonser</label>
      <input type="hidden" name="pings" value="yes">
      <p class="description" style="margin-left: 27px;">💡 Alltid aktivt, för att du ska kunna loopa.</p>
    </div>

    <div class="form-row">
      <label for="news"><input type="checkbox" id="news" name="news" value="yes" checked disabled>🌈 Information från föreningen</label>
      <input type="hidden" name="news" value="yes">
      <p class="description" style="margin-left: 27px;">💡 Alltid aktivt, för att du ska kunna ta emot viktig information.</p>
    </div>

		<input type="submit" value="Spara">
	</form>
</div>