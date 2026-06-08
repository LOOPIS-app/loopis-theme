<?php
/**
 * Content for page using url /register
 */

get_header(); ?>

<div class="page-padding">

<h1>🎉 Bli medlem</h1>
<hr>
<p class="small">💡 Förening för en glad & hållbar framtid.</p>

<p>1⃣ Fyll i formulär.<br>
<span style="opacity: 0.5;">2⃣ Verifiera e-post</span><br>
<span style="opacity: 0.5;">3⃣ Logga in</span><br>
<span style="opacity: 0.5;">4⃣ Betala medlemsavgift (50 kr)</span></p>

<div class="loopis-message warning">
<p>⚠ OBS! Du kan i nuläget bara använda LOOPIS nära Bagarmossen! <span class="link"><a href="<?php echo esc_url( home_url('/faq/varfor-bagis/') ); ?>">📌 Varför måste jag bo i Bagis?</a></span></p>
</div>

<h3>1⃣ Formulär</h3>
<hr>
<p class="small">💡 Många fält eftersom vi är en förening, men du klarar det!</p>

<div class="loopis-form">
<?php echo do_shortcode('[wpum_register form_id="1"]'); ?>
</div>

<p class="info">💡 Problem eller frågor? Maila <a href="mailto:info@loopis.app">info@loopis.app</a></p>
<p style="line-height: 2;">
<span class="link"><a href="<?php echo esc_url( home_url('/faq/stadgar/') ); ?>">📜 Föreningens stadgar</a></span>&nbsp; 
</p>

</div><!--page-padding-->

<?php get_footer(); ?>