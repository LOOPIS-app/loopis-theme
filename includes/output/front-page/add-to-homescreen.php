<?php
/**
 * Add to homescreen prompt for iOS devices.
 * Included in front-page.php for logged-in users
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div id="homescreen"
     style="display: none; position: fixed; height: 30px; width: 100%;
            bottom: 80px; text-align: center; background-color: #fff;
            border: 1px solid #e5e5e5; border-radius: 0; padding: 5px;
            z-index: 9998;">

    <p class="small">
        💡 <a href="#" onclick="installApp(); return false;"> Lägg LOOPIS på hemskärmen</a> så liknar det en app!
    </p>
</div>

<div id="arrow"
     style="display: none; position: fixed; bottom: 0; left: 50%;
            transform: translateX(-50%); z-index: 9999;
            text-align: center; background: #fff; padding: 15px;
            width: 100%; box-sizing: border-box;">

    <p>
        Tryck på dela-knappen i <b>Safari</b> och scrolla ner till <b>Lägg till på hemskärmen</b>
    </p>

    <img
        style="width: 72px;"
        src="<?php echo get_template_directory_uri(); ?>/assets/img/homescreen-iphone.png"
        alt="Lägg till på hemskärmen"
    />

    <br>

    <a href="#" onclick="hideArrow(); return false;">
        <i class="fas fa-times"></i> Stäng
    </a>
</div>

<script>
(function () {
    let deferredPrompt = null;

    const homescreen = document.getElementById('homescreen');
    const arrow = document.getElementById('arrow');

    const isIos = () => {
        return /iphone|ipad|ipod/i.test(navigator.userAgent);
    };

    const isAndroid = () => {
        return /android/i.test(navigator.userAgent);
    };

    const isStandalone = () => {
        return (
            window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone === true
        );
    };

    const isMobile = () => {
        return isIos() || isAndroid();
    };

    window.addEventListener('beforeinstallprompt', function (event) {
        event.preventDefault();
        deferredPrompt = event;

        if (isAndroid() && !isStandalone() && homescreen) {
            homescreen.style.display = 'block';
        }
    });

    if (isIos() && !isStandalone() && homescreen) {
        homescreen.style.display = 'block';
    }

    if (isAndroid() && !isStandalone() && homescreen && !deferredPrompt) {
        homescreen.style.display = 'block';
    }

    window.installApp = async function () {
        if (homescreen) {
            homescreen.style.display = 'none';
        }

        if (deferredPrompt) {
            deferredPrompt.prompt();

            const choiceResult = await deferredPrompt.userChoice;

            if (choiceResult.outcome === 'accepted') {
                console.log('LOOPIS installerades.');
            }

            deferredPrompt = null;
            return;
        }

        if (arrow) {
            arrow.style.display = 'block';
        }
    };

    window.hideArrow = function () {
        if (arrow) {
            arrow.style.display = 'none';
        }
    };

    window.addEventListener('appinstalled', function () {
        if (homescreen) {
            homescreen.style.display = 'none';
        }

        deferredPrompt = null;
    });
})();
</script>
