<?php
/**
 * New users page
 * 
 * Shows recently activated user accounts and pending registrations
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue tabs script
wp_enqueue_script('loopis-tabs', LOOPIS_THEME_URI . '/assets/js/tabs.js', array(), '1.0.0', true);
?>

<h1>🎉 Nya användare</h1>
<hr>
<p class="small">💡 Här ser du nya användare och registreringar.</p>


<!-- Tab Navigation -->
<div class="tab-nav">
  <nav class="tab-navbar">
    <a href="#" class="tab-link" data-tab="tab-new">✅</a>
    <a href="#" class="tab-link" data-tab="tab-pending">📋</a>
    
  </nav>
</div><!--tab-nav-->

<!-- Tab Content -->
<div class="tab-content">

  <!-- Tab: New Users -->
  <div id="tab-new" class="tab-panel">
    <?php include_once __DIR__ . '/tabs/users-new.php'; ?>
  </div>

  <!-- Tab: Pending Users -->
  <div id="tab-pending" class="tab-panel">
    <?php include_once __DIR__ . '/tabs/users-pending.php'; ?>
  </div>

</div><!--tab-content-->  