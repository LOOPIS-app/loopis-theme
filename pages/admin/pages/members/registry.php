<?php
/**
 * Member registry page
 * Shows all LOOPIS members organized by status
 * Four tabs: Active members, Former members, Remote members, Former remote members
 * Will be improved or replaced with our own code.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue tabs script
wp_enqueue_script('loopis-tabs', get_template_directory_uri() . '/assets/js/tabs.js', array(), '1.0.0', true);
?>

<h1>👥 Medlemsregister</h1>
<hr>
<p class="small">💡 Här ser du LOOPIS alla medlemmar, fördelade på fyra flikar.</p>

<!-- Tab Navigation -->
<div class="tab-nav">
    <nav class="tab-navbar">
        <a href="#" class="tab-link" data-tab="tab-active">👤 Aktiva</a>
        <a href="#" class="tab-link" data-tab="tab-former">👻 Tidigare</a>
        <a href="#" class="tab-link" data-tab="tab-remote">🌝 Utsocknes</a>
        <a href="#" class="tab-link" data-tab="tab-remote-former">🌚 Tidigare utsocknes</a>
    </nav>
</div><!--tab-nav-->

<!-- Tab Content -->
<div class="tab-content">

    <!-- Active Members -->
    <div id="tab-active" class="tab-panel">
        <h7>👤 Aktiva medlemmar</h7>
        <hr>
        <?php echo do_shortcode('[wpum_user_directory id="4670"]'); ?>
    </div>

    <!-- Former Members -->
    <div id="tab-former" class="tab-panel">
        <h7>👻 Tidigare medlemmar</h7>
        <hr>
        <?php echo do_shortcode('[wpum_user_directory id="4926"]'); ?>
    </div>

    <!-- Remote Members -->
    <div id="tab-remote" class="tab-panel">
        <h7>🌝 Utsocknes medlemmar</h7>
        <hr>
        <?php echo do_shortcode('[wpum_user_directory id="6015"]'); ?>
    </div>

    <!-- Former Remote Members -->
    <div id="tab-remote-former" class="tab-panel">
        <h7>🌚 Tidigare utsocknes medlemmar</h7>
        <hr>
        <?php echo do_shortcode('[wpum_user_directory id="6258"]'); ?>
    </div>

</div><!--tab-content-->