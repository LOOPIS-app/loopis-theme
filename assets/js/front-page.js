    
document.addEventListener('DOMContentLoaded', function() {
    const termsRow = document.getElementById('gift-untags-row');
    const termsOptions = document.getElementById('untags-options');
    const termsSearchInput = document.getElementById('untags-search');
    let applyTermsFilter = function() {};
    
    function getTermCheckboxes() {
        if (!termsOptions) {
            return [];
        }

        return Array.from(termsOptions.querySelectorAll('input[type="checkbox"][name="untags[]"]'));
    }

    function attachTermsSelectHandlers() {
        if (!termsOptions) {
            return;
        }

        termsOptions.addEventListener('change', function(event) {
            const target = event.target;
            if (!target || target.type !== 'checkbox') {
                return;
            }
        });
    }

    function normalizeSearchString(value) {
        const input = String(value || '').toLowerCase().trim();

        if (typeof input.normalize !== 'function') {
            return input;
        }

        return input.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function attachTermsSearchHandler() {
        if (!termsOptions || !termsSearchInput) {
            return;
        }

        const termRows = Array.from(termsOptions.querySelectorAll('.untag-option'));

        let hasUserInteracted = false;
        let hasOpenedOptions = getTermCheckboxes().some(function(checkbox) {
            return checkbox.checked;
        });

        if (!hasOpenedOptions) {
            termsOptions.classList.add('is-collapsed');
        }

        function openTermsOptions() {
            if (hasOpenedOptions) {
                return;
            }

            hasOpenedOptions = true;
            termsOptions.classList.remove('is-collapsed');
        }

        function applyTermFilter() {
            const query = normalizeSearchString(termsSearchInput.value);

            termRows.forEach(function(row) {
                const checkbox = row.querySelector('input[type="checkbox"]');
                const labelNode = row.querySelector('span');
                const labelSource = row.dataset.termLabel || (labelNode ? labelNode.textContent : '');
                const labelValue = normalizeSearchString(labelSource);
                const matches = !query || labelValue.includes(query);
                const isSelected = !!(checkbox && checkbox.checked);
                const shouldShow = (isSelected ||  matches);
                
                row.classList.toggle('is-filter-hidden', !shouldShow);
            });
        }

        function scrollTermsRowIntoView() {
            // One-time mobile assist when search gains focus so options are not hidden by keyboard.
            const isLikelyMobile = window.matchMedia('(pointer: coarse)').matches || navigator.maxTouchPoints > 0;
            if (!isLikelyMobile) {
                return;
            }

            const anchorElement = termsRow || termsSearchInput;
            if (!anchorElement) {
                return;
            }

            anchorElement.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' });

            // Run once more after keyboard animation so options stay visible above iOS keyboard.
            setTimeout(function() {
                anchorElement.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' });
            }, 180);
        }

        // Keep the shared filter callback in sync for checkbox change handling.
        applyTermsFilter = applyTermFilter;

        ['input', 'search', 'keyup', 'change'].forEach(function(eventName) {
            termsSearchInput.addEventListener(eventName, function() {
                hasUserInteracted = true;
                openTermsOptions();
                applyTermFilter();
            });
        });

        ['focus', 'click'].forEach(function(eventName) {
            termsSearchInput.addEventListener(eventName, function() {
                hasUserInteracted = true;
                openTermsOptions();
                applyTermFilter();
                scrollTermsRowIntoView();
            });
        });

        applyTermFilter();
    }


        // Clear stale max-tag validation once the selection becomes valid again.
    if (termsOptions) {
        attachTermsSelectHandlers();
        attachTermsSearchHandler();
    }
});