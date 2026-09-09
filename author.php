<?php
/**
 * Author template
 *
 * Displays author profile and stats + extra info for admin.
 * 
 * TODO: Keep author pages on sub sites but make them different on main site? Both can have them.
 */

get_header(); ?>

<div class="page-padding">

<!-- INLOGGAD? -->
<?php if (is_user_logged_in()) { 

// Get author info
$user = get_queried_object();
$user_id = get_queried_object_id();

// Get separate names
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
?>

<h1>👤 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-names.php'; ?></h1>
<hr>

<?php
// Skip for administrator
if (!in_array('administrator', $user->roles)):

// Get profile economy
$profile_economy = loopis_ledger_economy($user_id);
$payments_membership = $profile_economy['payments_membership'];
$payments_coins = $profile_economy['payments_coins'];
$membership_coins = $profile_economy['membership_coins'];
$bought_coins = $profile_economy['bought_coins'];
$count_given = $profile_economy['count_given'];
$count_booked = $profile_economy['count_booked'];
$count_submitted = $profile_economy['count_submitted'];
$count_deleted = $profile_economy['count_deleted'];
$stars = $profile_economy['stars'];
$star_coins = $profile_economy['star_coins'];
$clovers = $profile_economy['clovers'];
$clover_coins = $profile_economy['clover_coins'];
$coins = $profile_economy['coins'];
$joined_date = date('Y-m-d',strtotime($profile_economy['joined_date']));
if ($count_submitted !== 0) { $given_percentage = round(($count_given / $count_submitted) * 100); } else { $given_percentage = 0; }
?>

<p>Blev medlem <span class="big-label">🎉 <?php echo $joined_date; ?></span></p>
<p>Bor i postorten <span class="big-label">🗺 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-city.php'; ?></span></p>
<p>Loopar i området <span class="big-label">📍 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-primary-blog.php'; ?></span><br> &nbsp;</p>
<div class="wrapped">
<h1><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="Mynt:" class="symbol"><?php echo $coins; ?></h1>
<p class="small"><?php echo $first_name; ?> kan just nu hämta <?php echo $coins; ?> saker</p>
<hr>
<p class="small">💚 <?php echo $count_given; ?> saker lämnade</p>
<p class="small">❤ <?php echo $count_booked; ?> saker hämtade</p>
<p class="small">🍀 <?php echo $clovers; ?> fyrklöver</p>
<p class="small">⭐ <?php echo $stars; ?> guldstjärnor</p>
</div><!-- wrapped -->

<!--ADMIN LOG-->
<?php if (current_user_can('manage_options') || current_user_can('loopis_admin')) { include LOOPIS_THEME_DIR . '/includes/output/user-data/user-summary.php'; } ?>

<?php endif; ?>

<?php if (in_array('administrator', $user->roles)): ?>
<p>💡 Admin-konton har ingen profilsida.</p>
<?php endif; ?>

<!-- EJ INLOGGAD -->
<?php } else { ?>
<h1>👤 LOOPARE</h1>
<hr>
<p>💡 Profilsidor visas bara för inloggade medlemmar.</p>
<?php } ?>

</div><!-- page-padding -->

<?php get_footer(); ?>