<?php
/**
 * Profile activity tabs.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue tabs script
wp_enqueue_script('loopis-tabs', get_template_directory_uri() . '/assets/js/tabs.js', array(), '1.0.0', true);
?>

<h1>👤 Min aktivitet</h1>

<!-- VARIABLER -->
<?php $user_id = get_current_user_id(); ?>

<!-- Tab Navigation -->
<div class="tab-nav">
  <nav class="tab-navbar">
    <a href="#" class="tab-link" data-tab="tab-activity"><i class="fas fa-walking" style="color:#333;margin-right:0px"></i></a>
    <a href="#" class="tab-link" data-tab="tab-posts">🎁</a>
        <a href="#" class="tab-link" data-tab="tab-coins">👛</a>
    <a href="#" class="tab-link" data-tab="tab-comments">🗨</a>
    <a href="#" class="tab-link" data-tab="tab-settings">⚙</a>
  </nav>
</div><!--tab-nav-->

<!-- Tab Content -->
<div class="tab-content">

  <!-- ACTIVITY -->
  <div id="tab-activity" class="tab-panel">
    <p class="small">💡 Här ser du saker att hämta eller lämna.</p>
    <?php include_once __DIR__ . '/start-tabs/activity.php'; ?>
  </div>

  <!-- POSTS -->
  <div id="tab-posts" class="tab-panel">
    <p class="small">💡 Här ser du sakerna du har cirkulerat.</p>
    <?php include_once __DIR__ . '/start-tabs/posts.php'; ?>
  </div>

  <!-- COINS -->
  <div id="tab-coins" class="tab-panel">
    <p class="small">💡 Här ser du information om dina regnbågsmynt.</p>
    <?php include_once __DIR__ . '/start-tabs/coins.php'; ?>
  </div>
      
  <!-- COMMENTS -->
  <div id="tab-comments" class="tab-panel">
    <p class="small">💡 Här ser du dina senaste 50 kommentarer.</p>
    <?php include_once __DIR__ . '/start-tabs/comments.php'; ?>
  </div>
  
  <!-- SETTINGS -->
  <div id="tab-settings" class="tab-panel">
    <p class="small">💡 Här gör du inställningar för din aktivitet.</p>
    <?php include_once __DIR__ . '/start-tabs/settings.php'; ?>
  </div>

</div><!--tab-content-->