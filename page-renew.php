<?php
/**
 * Content for page using url /renew
 * 
 * Improvements:
 * - Revise to work with WordPress multisite and membership on different sites.
 */

get_header(); ?>

<div class="page-padding">

<h1>🌈 Förnya medlemskap</h1>
<hr>
<p class="small">💡 För en glad & hållbar framtid.</p>

<!-- ARCHIVED MEMBER -->
<?php if ( current_user_can('member_earlier')) { ?>
<p>Ditt medlemskap i föreningen LOOPIS behöver förnyas varje år.</p>

<div class="loopis-message information">
<h5>⚠ Kontrollera dina uppgifter!</h5>
<p>Justera om det behövs och tryck sedan på spara.</p>
</div>

<h3>Mina uppgifter</h3>
<hr>
<?php echo do_shortcode('[wpum_register form_id="2"]'); ?>

<p class="info">Genom att vara medlem godkänner du föreningens väldigt snälla <span class="link"><a href="<?php echo esc_url( home_url('/faq/stadgar/') ); ?>">📜 Stadgar</a></span> och <span class="link"><a href="<?php echo esc_url( home_url('/privacy/') ); ?>">🗄 Integritetspolicy</a></span></p>

<!-- ACTIVE MEMBER -->
<?php } elseif ( current_user_can('member')) { ?>
<div class="loopis-message information">
    <p>Ditt medlemskap är redan aktivt.</p>
</div>

<!-- NO ACCESS MESSAGE -->
<?php } else { ?>
			<?php include_once LOOPIS_THEME_DIR . '/templates/access/member-only.php'; ?>	
<?php } ?>

</div><!--page-padding-->

<?php get_footer(); ?>