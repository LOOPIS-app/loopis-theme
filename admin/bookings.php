<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🗓 Lån</h1>
<hr>
<p class="small">💡 Här ser du alla bokningar av 🗓 Saker att låna, aktuella och avslutade.</p>

		<div id="profile-navigation">
	 	<nav class="profile-navbar">
		<a href="#" class="tablinks" onclick="openCity(event, 'tab-1')" id="defaultOpen">🗓</a>
   	   	<a href="#" class="tablinks" onclick="openCity(event, 'tab-2')">☑</a>
		</nav>
		</div><!--profile-navigation-->

		<!-- TAB CONTENT -->
		<div class="profile-tab-content">

			<div id="tab-1" class="tabcontent">
			<h7>🗓 Aktuella bokningar</h7>
			<?php echo do_shortcode('[code_snippet id=155 php]'); ?> 
			</div>

			<div id="tab-2" class="tabcontent">
			<h7>☑ Avslutade bokningar</h7>
			<?php echo do_shortcode('[code_snippet id=156 php]'); ?>
			</div>

	   	</div><!--profile-tab-content-->


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