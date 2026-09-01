/**
 * Add-to-homescreen prompt for mobile browsers.
 * Shows a prompt for Android install support and an iOS instruction panel.
 */
(function () {
    'use strict';

    let deferredPrompt = null;

    // Cache the two UI containers we toggle from this script.
    const homescreen = document.getElementById('homescreen');
    const arrow = document.getElementById('arrow');

    // Detect the current device platform.
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

    // Keep the Android install prompt event so we can trigger it later.
    window.addEventListener('beforeinstallprompt', function (event) {
        event.preventDefault();
        deferredPrompt = event;

        // Show the install banner when native install is available.
        if (isAndroid() && !isStandalone() && homescreen) {
            homescreen.style.display = 'block';
        }
    });

    // iOS does not support beforeinstallprompt, so show the hint immediately.
    if (isIos() && !isStandalone() && homescreen) {
        homescreen.style.display = 'block';
    }

    // Fallback for Android when the install prompt is not yet available.
    if (isAndroid() && !isStandalone() && homescreen && !deferredPrompt) {
        homescreen.style.display = 'block';
    }

    // Trigger the native install flow when possible, otherwise show the iOS guide.
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

        // No native install prompt: reveal the iOS help arrow panel.
        if (arrow) {
            arrow.style.display = 'flex';
        }
    };

    // Hide the iOS help arrow panel.
    window.hideArrow = function () {
        if (arrow) {
            arrow.style.display = 'none';
        }
    };

    // Once the app is installed, clear the install banner and reset state.
    window.addEventListener('appinstalled', function () {
        if (homescreen) {
            homescreen.style.display = 'none';
        }

        if (arrow) {
            arrow.style.display = 'none';
        }

        deferredPrompt = null;
    });
})();