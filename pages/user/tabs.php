<?php
/**
 * Profile page tabs.
 * 
 * Dynamic content of page-profile.php
 * Reached on /profile
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get current user iD
$user_id = get_current_user_id();
$user = wp_get_current_user();

// Enqueue tabs script
wp_enqueue_script('loopis-tabs', get_template_directory_uri() . '/assets/js/tabs.js', array(), '1.0.0', true);
?>

<!-- Tab Navigation -->
<div class="tab-nav">
  <nav class="tab-navbar">
    <a href="#" class="tab-link" data-tab="tab-coins">👛</a>
    <a href="#" class="tab-link" data-tab="tab-activity">🧮</a>
    <a href="#" class="tab-link" data-tab="tab-areas">📍</a>
    <a href="#" class="tab-link" data-tab="tab-settings">⚙</a>
  </nav>
</div><!--tab-nav-->

<!-- Tab Content -->
<div class="tab-content">

  <!-- COINS -->
  <div id="tab-coins" class="tab-panel">
    <?php include_once __DIR__ . '/tabs/coins.php'; ?>
  </div>
  
  <!-- ACTIVITY -->
  <div id="tab-activity" class="tab-panel">
    <?php include_once __DIR__ . '/tabs/activity.php'; ?>
  </div>

  <!-- AREAS -->
  <div id="tab-areas" class="tab-panel">
    <?php include_once __DIR__ . '/tabs/areas.php'; ?>
  </div>

  <!-- SETTINGS -->
  <div id="tab-settings" class="tab-panel">
    <?php include_once __DIR__ . '/tabs/settings.php'; ?>
  </div>

</div><!--tab-content-->