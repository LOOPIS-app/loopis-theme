<?php
/* Template Name: Submit Template */
?>

<?php get_header(); ?>

<div class="content">
    <div class="page-padding">

        <h1>💚 Ge bort</h1>
        <hr>

<!-- Access? -->
<?php if ( current_user_can('member') || current_user_can('administrator') ) { ?>

<p class="small">💡 Det finns två sätt att ge bort något.</p>

<!-- Create ad -->
<p style="margin:0px;"><button type="submit"><a href="/submit-single">🎁 Skapa annons</a></button></p>
<p class="info">Tryck här för att skapa en ny annons.</p>

<!-- Forward ad -->
<p style="margin:0px;"><button type="submit" class="blue"><a href="<?php echo esc_url(home_url() . '/profil/fetched'); ?>">💝 Skicka vidare</a></button></p>
<p class="info">Tryck här för att skicka vidare något du fått.</p>

<!-- Extra permissions -->
<?php if (current_user_can('loopis_storage_submit') || current_user_can('loopis_storage_book')) : ?>
<div class="admin-block">
<p>💡 Du har extra befogenheter.</p>

<!-- Create storage ad -->
<?php if (current_user_can('loopis_storage_submit')) : ?>
<p style="margin:0px;"><button type="submit" class="small orange"><a href="/submit-storage">📦 Lägg i lager</a></button></p>
<p class="info">Tryck här för att skapa dolda annonser.</p>
<?php endif ?>

<!-- View storage -->
<?php if (current_user_can('loopis_storage_book')) : ?>
<p style="margin:0px;"><button type="submit" class="small red"><a href="/storage">❤ Visa lager</a></button></p>
<p class="info">Tryck här för att visa och paxa dolda annonser.</p>
<?php endif; ?>
</div>

<?php endif; ?>

<!-- Frågor & svar -->
<div class="columns"><div class="column1"><h3>Frågor & svar</h3></div>
<div class="column2 bottom"><a href="faq">→ Visa fler</a></div></div>
<hr>
<p><span class="big-link"><a href="/faq/hur-ger-jag-saker/">📌 Hur ger jag saker?</a></span></p>
<p><span class="big-link"><a href="/faq/hur-funkar-skapet">📌 Hur funkar skåpet?</a></span></p>
<p><span class="big-link"><a href="/faq/saker-som-inte-ryms-i-skapet">📌 Saker som inte ryms i skåpet?</a></span></p>

<!-- No access -->
<?php } else { 
  include LOOPIS_THEME_DIR . '/assets/output/access/message.php';
	include LOOPIS_THEME_DIR . '/assets/output/visitor/faq-single.php';
 } ?>

 <?php get_footer(); ?>