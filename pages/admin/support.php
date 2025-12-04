<?php
/**
 * Support tickets page
 * View and manage support tickets
 * Shows active and resolved support cases in separate tabs
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue tabs script
wp_enqueue_script('loopis-tabs', get_template_directory_uri() . '/assets/js/tabs.js', array(), '1.0.0', true);
?>

<h1>🛟 Support</h1>
<hr>
<p class="small">💡 Här ser du alla support-ärenden, pågående och avslutade.</p>

<!-- Tab Navigation -->
<div class="tab-nav">
    <nav class="profile-navbar">
        <a href="#" class="tab-link" data-tab="tab-active">⚠ Pågående</a>
        <a href="#" class="tab-link" data-tab="tab-resolved">✅ Avslutade</a>
    </nav>
</div>

<!-- Tab Content -->
<div class="tab-content">

    <!-- Active Cases -->
    <div id="tab-active" class="tab-panel">
        <h7>⚠ Pågående ärenden</h7>
        <?php include __DIR__ . '/support/support-active.php'; ?>
    </div>

    <!-- Resolved Cases -->
    <div id="tab-resolved" class="tab-panel">
        <h7>✅ Avslutade ärenden</h7>
        <?php include __DIR__ . '/support/support-solved.php'; ?>
    </div>

</div>