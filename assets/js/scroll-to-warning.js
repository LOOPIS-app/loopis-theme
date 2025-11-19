/**
 * Scroll to warning message
 * Improvment needed: block: 'center' has no effect.
 */

document.addEventListener('DOMContentLoaded', function() {
    const warningElement = document.querySelector('.wpum-message.warning');
    if (warningElement) {
        warningElement.scrollIntoView({
            behavior: 'smooth', 
            block: 'center'
        });
    }
});