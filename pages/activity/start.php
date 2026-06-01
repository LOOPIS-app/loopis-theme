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

<h1>🔔 Min aktivitet</h1>

<!-- VARIABLER -->
<?php $user_id = get_current_user_id(); ?>

<!-- Tab Navigation -->
<div class="tab-nav">
  <nav class="profile-navbar">
    <a href="#" class="tab-link" data-tab="tab-activity"><i class="fas fa-walking" style="color:#333;margin-right:0px"></i></a>
    <a href="#" class="tab-link" data-tab="tab-raffles">🎲</a>
    <a href="#" class="tab-link" data-tab="tab-posts">🎁</a>
    <a href="#" class="tab-link" data-tab="tab-comments">🗨</a>
    <a href="#" class="tab-link" data-tab="tab-settings">⚙</a>
  </nav>
</div><!--tab-nav-->

<!-- Tab Content -->
<div class="tab-content">

  <!-- ACTIVITY -->
  <div id="tab-activity" class="tab-panel">
    <p class="small">💡 Här visas saker du just nu ska hämta eller lämna.</p>
    <?php include_once __DIR__ . '/start-tabs/activity.php'; ?>
  </div>
  
  <!-- RAFFLES -->
  <div id="tab-raffles" class="tab-panel">
    <p class="small">💡 Här visas lottningar där du deltar/deltagit.</p>
    <h7>🎲 Lottningar</h7>
    <?php include_once __DIR__ . '/start-tabs/raffle.php'; ?>
  </div>

  <!-- POSTS -->
  <div id="tab-posts" class="tab-panel">
    <p class="small">💡 Här visas alla saker du har loopat.</p>
    <?php include_once __DIR__ . '/start-tabs/posts.php'; ?>
  </div>
    
  <!-- COMMENTS -->
  <div id="tab-comments" class="tab-panel">
    <p class="small">💡 Här visas dina senaste 50 kommentarer.</p>
    <h7>🗨 Mina kommentarer</h7>
    <?php include_once __DIR__ . '/start-tabs/comments.php'; ?>
  </div>
  
  <!-- SETTINGS -->
  <div id="tab-settings" class="tab-panel">
    <p class="small">💡 Här gör du inställningar för din aktivitet.</p>
    <h7>😎 Pausa annonser</h7>
    <?php include_once __DIR__ . '/start-tabs/settings.php'; ?>
  </div>

</div><!--tab-content-->