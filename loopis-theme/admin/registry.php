<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>👥 Medlemsregister</h1>
<hr>
<p class="small">💡 Här ser du LOOPIS alla medlemmar, fördelade på fyra flikar.</p>

		<div id="profile-navigation">
	    <nav class="profile-navbar">
        <a href="#" class="tablinks" onclick="openCity(event, 'tab-1')" id="defaultOpen">👤</a>
        <a href="#" class="tablinks" onclick="openCity(event, 'tab-2')">👻</a>
    	<a href="#" class="tablinks" onclick="openCity(event, 'tab-3')">🌝</a>
		<a href="#" class="tablinks" onclick="openCity(event, 'tab-4')">🌚</a>
	    </nav>
		</div><!--profile-navigation-->

		<!-- TAB CONTENT -->
		<div class="profile-tab-content">

			<div id="tab-1" class="tabcontent">
			<h7>👤 Aktiva medlemmar</h7>
			<hr>
			<?php echo do_shortcode('[wpum_user_directory id="4670"]'); ?> 
			</div>
		
			<div id="tab-2" class="tabcontent">
			<h7>👻 Tidigare medlemmar</h7>
			<hr>
			<?php echo do_shortcode('[wpum_user_directory id="4926"]'); ?> 
			</div>
		
			<div id="tab-3" class="tabcontent">
			<h7>🌝 Utsocknes medlemmar</h7>
			<hr>
			<?php echo do_shortcode('[wpum_user_directory id="6015"]'); ?> 
			</div>
			
			<div id="tab-4" class="tabcontent">
			<h7>🌚 Tidigare utsocknes medlemmar</h7>
			<hr>
			<?php echo do_shortcode('[wpum_user_directory id="6258"]'); ?> 
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