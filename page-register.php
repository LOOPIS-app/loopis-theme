<?php
/**
 * Content for page using url /register
 */

get_header(); ?>

<div class="content">
    <div class="page-padding">

<h1>🎉 Bli medlem</h1>
<hr>
<p>Swisha 50 kronor och fyll i formuläret för att bli medlem.</p>

<?php include_once LOOPIS_THEME_DIR . '/templates/general/swish-membership.php'; ?>

<div class="wpum-message warning">
<p>⚠ OBS! Du kan bara använda LOOPIS nära Bagarmossen!</p>
<p><span class="link"><a href="/varfor-bagis">📌 Varför måste jag bo i Bagis?</a></span></p>
</div>

<h3>📋 Formulär</h3>
<hr>
<p class="small">💡 Många fält eftersom vi är en förening - men du klarar det!</p>

<?php echo do_shortcode('[wpum_register form_id="1"]'); ?>

    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>