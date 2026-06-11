<?php
/**
 * Content for page using url /register-welcome
 */

get_header(); ?>

<div class="page-padding">

<h1>🎉 Bli medlem</h1>
<hr>
<p class="small">💡 Dags att logga in.</p>

<h3 style="opacity: 0.5;">1⃣ Fyll i formulär</h3>
<hr style="opacity: 0.5;">
<p style="opacity: 0.5;">✅ Bra jobbat.</p>

<h3 style="opacity: 0.5;">2⃣ Verifiera e-post</h3>
<hr style="opacity: 0.5;">
<p style="opacity: 0.5;">✅ Verifierad.</p>

<h3>3⃣ Logga in</h3>
<hr>
<p>→ Logga in med e-post och lösenord.</p>

<p><button type="button" class="green" onclick="window.location.href='<?php echo esc_url(home_url('/log-in')); ?>'">Logga in</button></p>
<p class="info">💡 Problem eller frågor? Maila <a href="mailto:info@loopis.app">info@loopis.app</a></p>

<h3 style="opacity: 0.5;">4⃣ Betala medlemsavgift (50 kr)</h3>

</div><!--page-padding-->

<?php get_footer(); ?>