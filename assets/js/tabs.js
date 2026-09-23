/**
 * Tab navigation handler
 * 
 * Handles tab switching for pages with tabs (eg. /activity and /user).
 * 
 * Should be improved: 
 * – possibility to use URL for each tab (+ back button support)
 * – delayed loading of a tabs content until clicked?
 * 
 * Created by CoPilot, inspired by WPUM, modified by Hubert.
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

        const params = new URLSearchParams(window.location.search);
        var tabGet = Number(params.get("tab"));
        if (!Number.isFinite(tabGet)) {
            tabGet = 0;
        }
        // Get all tab links and panels within the container
        const tabLinks = container.querySelectorAll('.tab-link');
        const tabPanels = container.querySelectorAll('.tab-panel');
        
        if (tabLinks.length === 0) {
            console.warn('No tab links found in container');
            return;
        }
        
        tabGet = Math.max(Math.min(tabGet, tabLinks.length - 1), 0);
        // Set first tab as active on load
        if (tabLinks.length > 0) {
            tabLinks[tabGet].classList.add('active');
        }
        if (tabPanels.length > 0) {
            tabPanels[tabGet].classList.add('active');
        }

        // Add click event to each tab link
        tabLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const tabIndex = Array.from(tabLinks).indexOf(this);
                const url = new URL(window.location.href);
                url.searchParams.set('tab', tabIndex);
                window.history.pushState({}, '', url);
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