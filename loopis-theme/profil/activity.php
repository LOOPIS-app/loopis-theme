<?php
/* Template Name: Activity Template */
?>

<?php get_header(); ?>

<!-- Extra php functions -->
<?php include_once LOOPIS_THEME_DIR . '/assets/functions/user-extra/post-action-activity.php'; ?>
<?php include_once LOOPIS_THEME_DIR . '/assets/functions/user-extra/post-action-list.php'; ?>

<div class="content">
<div class="page-padding">

<h1>🔔 Min aktivitet</h1>

<!-- MEMBER ACCESS -->
<?php if ( current_user_can('member') || current_user_can('administrator') ) { ?>

<!-- VARIABLER -->
<?php $user_id = get_current_user_id(); ?>

<!-- TABS -->
<div id="profile-navigation">
  <nav class="profile-navbar">
    <a href="#start" class="tablinks" onclick="tab(event, 'start')"><i class="fas fa-walking" style="color:#333;margin-right:0px"></i></a>
    <a href="#raffles" class="tablinks" onclick="tab(event, 'raffles')">🎲</a>
    <a href="#booked" class="tablinks" onclick="tab(event, 'booked')">❤</a>
    <a href="#active" class="tablinks" onclick="tab(event, 'active')">💚</a>
    <a href="#comments" class="tablinks" onclick="tab(event, 'comments')">🗨</a>
    <a href="#settings" class="tablinks" onclick="tab(event, 'settings')">⚙</a>
  </nav>
</div><!--profile-navigation-->

<!-- TABS -->
<div class="profile-tab-content">

<!-- START/ACTIVITY -->
	<div id="start" class="tabcontent">
	<p class="small">💡 Här visas saker du just nu ska hämta eller lämna.</p>
  <?php include_once LOOPIS_THEME_DIR . '/assets/output/user/activity/activity-alerts.php'; ?>
	</div>
	
  <!-- RAFFLES -->
  <div id="raffles" class="tabcontent">
	<p class="small">💡 Här visas lottningar där du deltar/deltagit.</p>
	<h7>🎲 Lottningar</h7>
	<?php echo do_shortcode('[code_snippet id=59 php]'); ?>
	</div>

  <!-- BOOKED -->
	<div id="booked" class="tabcontent">
	<p class="small">💡 Här visas saker du just nu har paxat.</p>
	<h7>❤ Mina paxningar</h7>
	<?php echo do_shortcode('[code_snippet id=41 php]'); ?>
	</div>

  <!-- ACTIVE (POSTS) -->
	<div id="active" class="tabcontent">
	<p class="small">💡 Här visas saker du just nu ger bort.</p>
	<h7>💚 Mina annonser</h7>
	<?php echo do_shortcode('[code_snippet id=42 php]'); ?>
	</div>
		
  <!-- COMMENTS -->
	<div id="comments" class="tabcontent">
	<p class="small">💡 Här visas dina senaste 50 kommentarer.</p>
	<h7>🗨 Mina kommentarer</h7>
	<?php echo do_shortcode('[code_snippet id=118 php]'); ?>
	</div>
	
  <!-- SETTINGS -->
	<div id="settings" class="tabcontent">
	<p class="small">💡 Här gör du inställningar för din aktivitet.</p>
	<h7>😎 Pausa annonser</h7>
	<?php echo do_shortcode('[code_snippet id=121 php]'); ?>
	</div>

</div><!--profile-tab-content-->

<!-- TAB HANDLING -->
<script>
function tab(evt, tabName) {
  // Prevent scroll to tabcontent
  evt.preventDefault();

  // Update the URL with the tab name
  var url = new URL(window.location.href);
  url.hash = tabName;
  window.history.pushState({}, document.title, url.toString());

  // Get all elements with class="tablinks" and remove the "active" class
  var tablinks = document.getElementsByClassName("tablinks");
  for (var i = 0; i < tablinks.length; i++) {
    tablinks[i].classList.remove("active");
  }

  // Get all elements with class="tabcontent" and hide them
  var tabcontents = document.getElementsByClassName("tabcontent");
  for (var i = 0; i < tabcontents.length; i++) {
    tabcontents[i].style.display = "none";
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.classList.add("active");
}

// Activate the tab based on the URL fragment
document.addEventListener("DOMContentLoaded", function() {
  var tabName = window.location.hash.substr(1);
  if (tabName) {
    // Only call tab() if there is a hash in the URL
    tab(event, tabName);
    var tabLink = document.querySelector(`a[href="#${tabName}"]`);
    if (tabLink) {
      tabLink.classList.add("active");
    }
  } else {
    // Default to the first tab
    document.querySelector(".tablinks").classList.add("active");
    document.querySelector(".tabcontent").style.display = "block";
  }
});
</script>

<!-- NO ACCESS MESSAGE -->	
<?php } else { ?>
<hr>
<?php include_once LOOPIS_THEME_DIR . '/assets/output/access/member-only.php'; } ?>

</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>