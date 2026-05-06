<?php
/**
 * Content for page using url /register-welcome
 * 
 * IMPROVEMENTS:
 * Check if email adress is verified (currently using "WPUM User verification") and fade out "Verifiera e-postadress" step if so.
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

<h1>💚 Välkommen!</h1>
<hr>
<p class="small">💡 Nu är du medlem i LOOPIS!</p>

<h3 style="opacity: 0.5;">1⃣ Fyll i formulär</h3>
<hr style="opacity: 0.5;">
<p style="opacity: 0.5;">✅ Bra jobbat.</p>

<h3 style="opacity: 0.5;">2⃣ Betala medlemskap</h3>
<hr style="opacity: 0.5;">
<p style="opacity: 0.5;">✅ Betalat.</p>

<h3>3⃣ Verifiera e-postadress</h3>
<hr>
<p><strong>→ Kolla din inkorg och tryck på länken i vårt mail.</strong></p>

<?php insert_spacer(20); ?>

<p><button type="submit"><a href="<?php echo esc_url(home_url('/'));?>">Logga in!</a></button></p>
<p class="info">💡 Problem eller frågor? Maila <a href="mailto:info@loopis.org">info@loopis.org</a></p>