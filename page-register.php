<?php
/**
 * Content for page using url /register
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

<h1>🎉 Bli medlem</h1>
<hr>
<p class="small">💡 För en glad & hållbar framtid.</p>

<p>Fyll i formuläret och betala 50 kronor (engångsavgift). Du får 5 regnbågsmynt att paxa saker med.</p>

<div class="wpum-message warning">
<p>⚠ OBS! Du kan bara använda LOOPIS nära Bagarmossen! <span class="link"><a href="<?php echo esc_url( home_url('/faq/varfor-bagis/') ); ?>">📌 Varför måste jag bo i Bagis?</a></span></p>
</div>

<p style="line-height: 2;">
<span class="link"><a href="<?php echo esc_url( home_url('/faq/stadgar/') ); ?>">📜 Föreningens stadgar</a></span>&nbsp; 
<span class="link"><a href="<?php echo esc_url( home_url('/faq/hur-funkar-regnbagsmynt/') ); ?>">📌 Hur funkar regnbågsmynt?</a></span></p>

<h3>1⃣ Formulär</h3>
<hr>
<p class="small">💡 Många fält eftersom vi är en förening - men du klarar det!</p>

<?php echo do_shortcode('[wpum_register form_id="1"]'); ?>

<p class="info">💡 Problem eller frågor? Maila <a href="mailto:info@loopis.app">info@loopis.app</a></p>
    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>