<?php
/**
 * Content for page using url /register
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

<h1>💚 Välkommen!</h1>
<hr>
<p class="small">💡 Nu är du medlem i LOOPIS!</p>

<h3 style="opacity: 0.5;">1⃣ Formulär ifyllt</h3>
<hr style="opacity: 0.5;">
<p style="opacity: 0.5;">✅ Bra jobbat.</p>

<h3>2⃣ Verifiera e-postadress</h3>
<hr>
<p><strong>→ Har du tryckt på länken i det mail vi skickat? Då kan du logga in!</strong></p>

<h3 style="opacity: 0.5;">3⃣ Betala medlemskap</h3>
<hr style="opacity: 0.5;">
<p style="opacity: 0.5;">✅ Betalat.</p>

<?php insert_spacer(20); ?>
/* What the heck? */
<p><button type="submit"><a href="<?php echo esc_url(home_url('/'));?>">Logga in!</a></button></p>
<p class="info">💡 Problem eller frågor? Maila <a href="mailto:info@loopis.org">info@loopis.org</a></p>