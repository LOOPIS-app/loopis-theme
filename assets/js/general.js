/*
    General scripts for LOOPIS theme
*/

(function($) {
    "use strict";

    $(document).ready(function() {
        /* "Remember me" by Poe */
        $('#remember').prop('checked', true);

        /* "Scroll to top" by ALX */
        $('a#back-to-top').on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            return false;
        });

        /* "Copy URL" by Poe and Copilot */
        $('#copy_url').on('click', function() {
            const url = $(location).attr('href'); // Get the current URL
            navigator.clipboard.writeText(url).then(function() {
                alert("Länk kopierad."); // Optional: Add a confirmation message
            }).catch(function(err) {
                console.error("Ett fel uppstod: ", err);
            });
        });

        /* "Trap focus" by ALX = keyboard focus restricted */
        const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
        const modal = document.querySelector('.search-trap-focus');

        if (modal) {
            const firstFocusableElement = modal.querySelectorAll(focusableElements)[0];
            const focusableContent = modal.querySelectorAll(focusableElements);
            const lastFocusableElement = focusableContent[focusableContent.length - 1];

            document.addEventListener('keydown', function(e) {
                const isTabPressed = e.key === 'Tab' || e.keyCode === 9;

                if (!isTabPressed) {
                    return;
                }

                if (e.shiftKey) { // Shift + Tab
                    if (document.activeElement === firstFocusableElement) {
                        lastFocusableElement.focus();
                        e.preventDefault();
                    }
                } else { // Tab
                    if (document.activeElement === lastFocusableElement) {
                        firstFocusableElement.focus();
                        e.preventDefault();
                    }
                }
            });
        }
    });

})(jQuery);