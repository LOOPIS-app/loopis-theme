<?php
/**
 * Show admin info for profile.
 *
 * Used in author.php
 */

 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>
<div class="admin-block">
<?php include_once LOOPIS_THEME_DIR . '/assets/output/admin/admin-link.php'; ?>

<?php
// Extra info
$count_support = count_user_support($user_id);
?>

<!-- TABS -->
<div id="profile-navigation">
	<nav class="profile-navbar">
	<a href="#" class="tablinks" onclick="openCity(event, 'tab-1')" id="defaultOpen">👤</a>
	<a href="#" class="tablinks" onclick="openCity(event, 'tab-2')">⬆️</a>
	<a href="#" class="tablinks" onclick="openCity(event, 'tab-3')">⬇️</a>
	<a href="#" class="tablinks" onclick="openCity(event, 'tab-4')">🛟</a>
	</nav>
</div><!--profile-navigation-->

<div class="profile-tab-content">

<!-- TAB #1 -->
<div id="tab-1" class="tabcontent">

	<div class="wrapped">
		<h5>📋 Medlemsregister</h5>
		<hr>
		<p><span class="label">⚧ <?php include_once LOOPIS_THEME_DIR . '/assets/output/admin/profile/user-gender.php'; ?></span></p>
		<p><span class="label">👶 <?php include_once LOOPIS_THEME_DIR . '/assets/output/admin/profile/user-age.php'; ?></span></p>
		<p><span class="label">✉ <a href="mailto:<?php echo esc_attr($user->user_email); ?>"><?php echo esc_html($user->user_email); ?></a></span></p>
		<p><span class="label">📱 <a href="sms:<?php echo esc_attr(get_the_author_meta('wpum_phone', $user_id)); ?>"><?php echo esc_html(get_the_author_meta('wpum_phone', $user_id)); ?></a></span></p>
	</div><!--wrapped-->

	<div class="wrapped">
		<h5>📒 Kvitton</h5>
		<hr>
		<?php include_once LOOPIS_THEME_DIR . '/assets/output/user/profile/user-payments.php'; ?>
	</div><!--wrapped-->

	<div class="wrapped">
		<h5>🎁 Saker att få</h5>
		<hr>
		<p><span class="label">⬆ <?php echo $count_submitted; ?> annonser</span> <span class="small">♻ <?php echo $given_percentage; ?>%</span></p>
		<p><span class="label">❌ <?php echo $count_deleted; ?> borttagna</span></p>
	</div><!--wrapped-->

	<div class="wrapped">
		<h5>🛟 Support</h5>
		<hr>
		<p><span class="label">🗒 <?php echo $count_support; ?> ärenden</span></p>
	</div><!--wrapped-->

	<h3>🧮 Aktivitet</h3>
<hr>
<div class="economy wrapped">
<p>Regnbågsmynt<span class="right"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:15px; width: auto;"></span></p>
<hr>
<p><b><?php echo $payments_membership; ?></b> köp av medlemskap <span class="plus right">+<?php echo $membership_coins; ?></span></p>
<?php if ( $payments_coins > 0 ) { ?>
<p><b><?php echo $payments_coins; ?></b> köp av extra mynt <span class="plus right">+<?php echo $bought_coins; ?></span></p>
<?php } ?>
<!--p><b><?php echo $clovers; ?></b> fyrklöver <span class="plus right">+<?php echo $clover_coins; ?></span></p>
<p><b><?php echo $stars; ?></b> stjärnor <span class="plus right">+<?php echo $star_coins; ?></span></p-->
<p><b><?php echo $count_given; ?></b> saker lämnade <span class="plus right">+<?php echo $count_given; ?></span></p>
<p><b><?php echo $count_booked; ?></b> saker hämtade/paxade <span class="minus right">–<?php echo $count_booked; ?></span></p>
<p><b><?php echo $count_borrowed; ?></b> saker lånade <span class="minus right">–<?php echo $count_borrowed; ?></span></p>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $coins - $clover_coins - $star_coins; ?></b></span></p>
</div>

<div class="economy wrapped">
<p>Fyrklöver<span class="right">🍀</span></p>
<hr>
<p><b><?php echo $count_submitted; ?></b> annonser skapade <span class="plus right">+<?php echo $count_submitted; ?></span></p>
<p><b><?php echo $count_booked; ?></b> saker hämtade <span class="plus right">+<?php echo $count_booked; ?></span></p>
<p><b><?php echo $count_borrowed; ?></b> saker lånade <span class="plus right">+<?php echo $count_borrowed; ?></span></p>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $clovers; ?></b></span></p>

<p class="small">
<?php if ($clover_coins > 0) { ?>
→ <b><?php echo $clover_coins; ?> mynt</b> i belöning! 🎉
<?php } else { ?>→ Inga mynt i belöning.
<?php } ?>
</p>
</div>

<div class="economy wrapped">
<p>Guldstjärnor<span class="right">🌟</span></p>
<hr>
<?php include_once LOOPIS_THEME_DIR . '/assets/output/user/profile/user-rewards.php'; ?>
<hr>
<p>&nbsp;<span class="right">Totalt: <b><?php echo $stars; ?></b></span></p>

<p class="small">
<?php if ($star_coins > 0) { ?>
→ <b><?php echo $star_coins; ?> mynt</b> i belöning! 🎉 
<?php } else { ?>→ Inga mynt i belöning.
<?php } ?>
</p>
</div>

</div><!--tabcontent-->

<!-- TAB #2 -->
<div id="tab-2" class="tabcontent">
	<?php echo do_shortcode('[code_snippet id=162 php]'); ?> 
</div>

<!-- TAB #3 -->
<div id="tab-3" class="tabcontent">
	<?php echo do_shortcode('[code_snippet id=163 php]'); ?> 
</div>

<!-- TAB #4 -->
<div id="tab-4" class="tabcontent">
	<?php echo do_shortcode('[code_snippet id=198 php]'); ?> 
</div>

</div><!--profile-tab-content-->
</div><!--admin-block-->

<script>
function openCity(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>

<script>
// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>