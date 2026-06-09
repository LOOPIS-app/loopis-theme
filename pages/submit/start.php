<?php
/**
 * SUBMIT OPTIONS
 * 
 * Dynamic content of page-submit.php
 * Reached on /submit
 * 
 * Options for submitting new posts.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>💚 Ge bort</h1>
<hr>

<p class="small">💡 Det finns två sätt att ge bort något.</p>

<!-- Create ad -->
<p style="margin:0px;"><button type="button" class="green" onclick="window.location.href='<?php echo esc_url( add_query_arg(array('option' => 'single'), home_url('/submit/')) ); ?>'">🎁 Skapa annons</button></p>
<p class="info">Tryck här för att skapa en ny annons.</p>

<!-- Forward ad -->
<p style="margin:0px;"><button type="button" class="blue" onclick="window.location.href='<?php echo esc_url(add_query_arg(array('view' => 'posts-fetched'), home_url('/activity/'))); ?>'">💝 Skicka vidare</button></p>
<p class="info">Tryck här för att skicka vidare något du fått.</p>

<!-- Storage -->
<?php if (current_user_can('manage_options') || current_user_can('loopis_storage')) : ?>
<div class="admin-block">
<p>💡 Du har behörighet att lägga annonser i lager.</p>

  <?php if (current_user_can('loopis_admin')) : ?>
  <button type="button" class="orange small" onclick="window.location.href='<?php echo esc_url( add_query_arg(array('view' => 'storage'), home_url('/admin/')) ); ?>'">📦 Visa lager</button>
  <p class="info">Tryck här för att visa lager och paxa annonser.</p>
  </div>
  <?php endif; ?>
<?php endif; ?>

<!-- FAQ -->
<?php include LOOPIS_THEME_DIR . '/templates/faq/questions-submit.php'; ?>

</div><!--page-padding-->

<?php get_footer(); ?>