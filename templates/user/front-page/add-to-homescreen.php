<?php
/**
 * Add to homescreen prompt for iOS devices.
 * Included in front-page.php for logged-in users
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div id="homescreen" style="display: none; position: fixed; height: 30px; width: 100%; bottom: 80px; text-align: center; background-color: #fff; border: 1px solid #e5e5e5; border-radius: 0px; padding: 5px; z-index: 9998;">
    <p class="small">💡<a href="#" onclick="showArrow(); return false;"> Lägg LOOPIS på hemskärmen</a> så liknar det en app!</p>
</div>

<div id="arrow" style="display: none; position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); z-index: 9999; text-align: center;">
    <p>Tryck på dela-knappen i <b>Safari</b> och scrolla ner till <b>Lägg till på hemskärmen</b></p>
    <img style="width: 72px;" src="<?php echo get_template_directory_uri(); ?>/assets/img/homescreen-iphone.png" alt="arrow" />
    <a style="position: absolute; right: 20px; bottom: 10px;" href="#" onclick="hideArrow(); return false;"><i class="fas fa-times"></i> Stäng</a>
</div>

<script>
(function() {
    // Detects if device is on iOS
    const isIos = () => {
        const userAgent = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(userAgent);
    };

    // Detects if device is in standalone mode
    const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

    // Check if the web app is running on an iOS device and not in standalone mode
    if (isIos() && !isInStandaloneMode()) {
        const homescreen = document.getElementById('homescreen');
        if (homescreen) {
            homescreen.style.display = 'block';
        }
    }

    // Global functions for onclick handlers
    window.showArrow = function() {
        const arrow = document.getElementById('arrow');
        const homescreen = document.getElementById('homescreen');
        if (arrow) arrow.style.display = 'block';
        if (homescreen) homescreen.style.display = 'none';
    };

    window.hideArrow = function() {
        const arrow = document.getElementById('arrow');
        if (arrow) arrow.style.display = 'none';
    };
})();
</script>