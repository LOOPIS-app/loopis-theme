/**
 * General scripts for LOOPIS theme
 * 
 * Loaded on all pages from functions.php
 */

(function($) {
    "use strict";

    $(document).ready(function() {

        /* "Remember me" checked by default for WPUM login form */
        $('#remember').prop('checked', true);

        /* "Up" link in footer.php */
        $('a#back-to-top').on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            return false;
        });

        /* "Copy URL" link  */
        $('#copy_url').on('click', function(e) {
            e.preventDefault();
            const url = window.location.href;

            const fallbackCopy = function(text) {
                const temp = document.createElement('textarea');
                temp.value = text;
                temp.setAttribute('readonly', '');
                temp.style.position = 'absolute';
                temp.style.left = '-9999px';
                document.body.appendChild(temp);
                temp.select();
                const ok = document.execCommand('copy');
                document.body.removeChild(temp);
                return ok;
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(function() {
                    alert("Länk kopierad.");
                }).catch(function(err) {
                    if (fallbackCopy(url)) {
                        alert("Länk kopierad.");
                    } else {
                        console.error("Ett fel uppstod: ", err);
                    }
                });
            } else if (fallbackCopy(url)) {
                alert("Länk kopierad.");
            } else {
                console.error("Ett fel uppstod: Kunde inte kopiera länk.");
            }
        });

        /* "Copy user info" link */
        const copyUserInfo = function(el) {
            const text = $(el).prev().text().trim(); // Get text of previous element
            if (!text) {
                return;
            }

            const fallbackCopy = function(value) {
                const temp = document.createElement('textarea');
                temp.value = value;
                temp.setAttribute('readonly', '');
                temp.style.position = 'absolute';
                temp.style.left = '-9999px';
                document.body.appendChild(temp);
                temp.select();
                const ok = document.execCommand('copy');
                document.body.removeChild(temp);
                return ok;
            };

            const showCopiedState = function($el) {
                $el.html('<i class="far fa-check-square"></i>');
                setTimeout(function() {
                    $el.html('<i class="far fa-copy"></i>');
                }, 1000);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopiedState($(el));
                }).catch(function() {
                    if (fallbackCopy(text)) {
                        showCopiedState($(el));
                    }
                });
            } else if (fallbackCopy(text)) {
                showCopiedState($(el));
            }
        };

        $(document).on('click', '.copy_user_info', function(e) {
            e.preventDefault();
            copyUserInfo(this);
        });

        $(document).on('keydown', '.copy_user_info', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                copyUserInfo(this);
            }
        });

        /* "Trap focus" by ALX (keyboard focus restricted) */
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