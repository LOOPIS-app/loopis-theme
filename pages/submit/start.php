<?php
/**
 * SUBMIT START (page-submit.php)
 * 
 * Options for submitting new posts.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>💚 Ge bort</h1>
<hr>

<!-- Access? -->
<?php if ( current_user_can('member') || current_user_can('administrator') ) { ?>

<p class="small">💡 Det finns två sätt att ge bort något.</p>

<!-- Create ad -->
<p style="margin:0px;"><button type="submit"><a href="<?php echo esc_url( add_query_arg(array('option' => 'single'), home_url('/submit/')) ); ?>">🎁 Skapa annons</a></button></p>
<p class="info">Tryck här för att skapa en ny annons.</p>

<!-- Forward ad -->
<p style="margin:0px;"><button type="submit" class="blue"><a href="<?php echo esc_url(add_query_arg(array('view' => 'posts-fetched'), home_url('/activity/'))); ?>">💝 Skicka vidare</a></button></p>
<p class="info">Tryck här för att skicka vidare något du fått.</p>

<!-- Extra permissions -->
<?php if (current_user_can('loopis_storage_submit') || current_user_can('loopis_storage_book')) : ?>
<div class="admin-block">
<p>💡 Du har extra befogenheter.</p>

<!-- Create storage ad -->
<?php if (current_user_can('loopis_storage_submit')) : ?>
<p style="margin:0px;"><button type="submit" class="small orange"><a href="<?php echo esc_url( add_query_arg(array('option' => 'storage'), home_url('/submit/')) ); ?>">📦 Lägg i lager</a></button></p>
<p class="info">Tryck här för att skapa dolda annonser.</p>
<?php endif ?>

<!-- View storage -->
<?php if (current_user_can('loopis_storage_book')) : ?>
<p style="margin:0px;"><button type="submit" class="small red"><a href="<?php echo esc_url( add_query_arg(array('view' => 'storage'), home_url('/admin/')) ); ?>">❤ Visa lager</a></button></p>
<p class="info">Tryck här för att visa och paxa dolda annonser.</p>
<?php endif; ?>

</div>

<?php endif; ?>

<!-- FAQ -->
<?php include LOOPIS_THEME_DIR . '/templates/faq/questions-submit.php'; ?>

<!-- No access -->
<?php } else { 
  include LOOPIS_THEME_DIR . '/templates/access/message.php';
	include LOOPIS_THEME_DIR . '/templates/faq/questions-visitor.php';
 } ?>

  </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>