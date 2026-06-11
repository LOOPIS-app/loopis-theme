<?php
/**
 * Force Twemoji Override
 * 
 * Loads Twemoji library to replace native emojis with consistent SVG versions.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Twemoji script to page head
 */
function use_twemoji() {
    ?>
    <script type="text/javascript">
    !function(window, document) {
        // Immediate emoji replacement based on wp-emoji-loader.min.js
        function loadTwemojiSync() {
            var script = document.createElement('script');
            script.src = 'https://unpkg.com/twemoji@latest/dist/twemoji.min.js';
            script.onload = function() {
                // Parse immediately when loaded
                twemoji.parse(document.body || document.documentElement, {
                    folder: 'svg',
                    ext: '.svg'
                });
                
                replaceOptionEmojis()
                
                // Set up observer for dynamic content
                if (window.MutationObserver) {
                    var observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            mutation.addedNodes.forEach(function(node) {
                                if (node.nodeType === 1) {
                                    twemoji.parse(node, {
                                        folder: 'svg',
                                        ext: '.svg'
                                    });
                                }
                            });
                        });
                    });
                    
                    observer.observe(document.body || document.documentElement, {
                        childList: true,
                        subtree: true
                    });
                }
            };
            document.head.appendChild(script);
        }
        
        // Load immediately - don't wait for DOMContentLoaded
        if (document.head) {
            loadTwemojiSync();
        } else {
            // Fallback if head doesn't exist yet
            document.addEventListener('DOMContentLoaded', loadTwemojiSync);
        }
        
    }(window, document);
    function replaceOptionEmojis() {
        const options = document.querySelectorAll('select[name="category"] option');
        options.forEach(function(option) {
            // Get the current text
            const text = option.textContent;
            // Parse and replace emojis in the text
            const replacedText = twemoji.parse(text, {
                folder: 'svg',
                ext: '.svg'
            });
            // Set the innerHTML of the option to the parsed SVG
            option.innerHTML = replacedText;
        });
    }
    </script>
    <?php
}

// Add Twemoji to frontend
add_action('wp_head', 'use_twemoji');   