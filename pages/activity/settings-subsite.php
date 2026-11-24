<?php
/**
 * Form for subsite settings (activity/area)
 * 
 * So far only contains mail settings
 * 
 * Dynamic content of page-activity.php
 * Reached on /activity/?view=settings
 */

if (!defined('ABSPATH')) {
    exit;
}

$user_id = get_current_user_id();


$unsubscribed_ids = get_option( 'unsubscribed_mail_ids', [] );
if ( ! is_array( $unsubscribed_ids ) ) {
    $unsubscribed_ids = [];
}

$wants_tips = ! in_array( $user_id, array_map( 'absint', $unsubscribed_ids ), true );

$settings_url = add_query_arg(
    'view',
    'settings-subsite',
    get_permalink()
);
?>
<h1>✉️ Mailutskick</h1>
<hr>
<p class="small">💡 Inställningar för mailutskick från LOOPIS.</p>

<h3>Jag vill ha:</h3>
<hr>

<div class="loopis-form-wrapper">
  <form class="loopis-form"
      method="post"
      action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

    <input type="hidden" name="action" value="loopis_save_mail_settings">
    <?php wp_nonce_field( 'loopis_mail_settings', 'loopis_mail_settings_nonce' ); ?>
    <div class="form-row">
       <label for="tips"> <input type="checkbox" id="tips" name="tips" value="yes" <?php checked( $wants_tips ); ?> > 🎁 Tips på nya annonser</label>
        <p class="description" style="margin-left: 27px;">💡 Skickas av admin i ditt område.</p>
    </div>

    <div class="form-row">
      <label for="pings"><input type="checkbox" id="pings" name="pings" value="yes" checked disabled>🔔 Notifikationer för annonser</label>
      <input type="hidden" name="pings" value="yes">
      <p class="description" style="margin-left: 27px;">💡 Alltid aktivt, för att du ska kunna loopa.</p>
    </div>

    <div class="form-row">
      <label for="news"><input type="checkbox" id="news" name="news" value="yes" checked disabled>🌈 Information från föreningen</label>
      <input type="hidden" name="news" value="yes">
      <p class="description" style="margin-left: 27px;">💡 Alltid aktivt, för att du ska kunna ta emot viktig information.</p>
    </div>

		<input type="submit" value="Spara">
	</form>
</div>
