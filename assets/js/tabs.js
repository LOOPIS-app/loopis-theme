/**
 * Tab navigation handler
 * Handles tab switching for tabbed interface.
 * Should be improved with AJAX for dynamic loading of tab content.
 * 
 * Created by CoPilot, inspired by WPUM.
 */

(function() {
    'use strict';

    /**
     * Initialize tabs
     * @param {string} containerId - ID of the tab container (optional, defaults to entire document)
     */
    function initTabs(containerId) {
        const container = containerId ? document.getElementById(containerId) : document;
        
        if (!container) {
            console.error('Tab container not found:', containerId);
            return;
        }

        // Get all tab links and panels within the container
        const tabLinks = container.querySelectorAll('.tab-link');
        const tabPanels = container.querySelectorAll('.tab-panel');
        
        if (tabLinks.length === 0) {
            console.warn('No tab links found in container');
            return;
        }

        // Set first tab as active on load
        if (tabLinks.length > 0) {
            tabLinks[0].classList.add('active');
        }
        if (tabPanels.length > 0) {
            tabPanels[0].classList.add('active');
        }

        // Add click event to each tab link
        tabLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const tabId = this.getAttribute('data-tab');
                openTab(tabId, container);
            });
        });
    }

    /**
     * Open a specific tab
     * @param {string} tabId - ID of the tab to open
     * @param {Element} container - Container element to search within
     */
    function openTab(tabId, container) {
        // Get all tab links and panels
        const tabLinks = container.querySelectorAll('.tab-link');
        const tabPanels = container.querySelectorAll('.tab-panel');

        // Remove active class from all tabs and panels
        tabLinks.forEach(function(link) {
            link.classList.remove('active');
        });
        tabPanels.forEach(function(panel) {
            panel.classList.remove('active');
        });

        // Add active class to clicked tab link
        const selectedLink = container.querySelector('[data-tab="' + tabId + '"]');
        if (selectedLink) {
            selectedLink.classList.add('active');
        }

        // Add active class to corresponding panel
        const selectedPanel = container.querySelector('#' + tabId);
        if (selectedPanel) {
            selectedPanel.classList.add('active');
        }
    }

    // Initialize tabs when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initTabs();
        });
    } else {
        initTabs();
    }

    // Expose initTabs globally for manual initialization if needed
    window.initTabs = initTabs;

})();