<?php
/**
 * User summary for admin on author.php
 * 
 * Displays member info, payments, activity, and statistics
 * $user and $user_id is passed from context
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue tabs script
wp_enqueue_script('loopis-tabs', get_template_directory_uri() . '/assets/js/tabs.js', array(), '1.0.0', true);
?>

<div class="admin-block">
    <?php include LOOPIS_THEME_DIR . '/templates/links/admin-link.php'; ?>

    <!-- Tab Navigation -->
    <div class="tab-nav">
        <nav class="profile-navbar">
            <a href="#" class="tab-link" data-tab="tab-info">ℹ</a>
            <a href="#" class="tab-link" data-tab="tab-economy">🧮</a>
            <a href="#" class="tab-link" data-tab="tab-posts">🎁</a>
            <a href="#" class="tab-link" data-tab="tab-support">🛟</a>
            <a href="#" class="tab-link" data-tab="tab-about">⚙️</a>
        </nav>
    </div><!--tab-nav-->

    <div class="tab-content"> 

        <!-- Member Info Tab -->
        <div id="tab-info" class="tab-panel">
        <p class="small">💡 Mer info om <?php echo $first_name; ?>.</p>

            <div class="wrapped">
                <h5>📋 Medlemsregister</h5>
                <hr>
                <p><span class="label"><?php include LOOPIS_THEME_DIR . '/templates/admin/profile/user-id.php'; ?></span></p>
                <p><span class="label"><?php include LOOPIS_THEME_DIR . '/templates/admin/profile/user-age.php'; ?></span></p>
                <p><span class="label"><?php include LOOPIS_THEME_DIR . '/templates/admin/profile/user-gender.php'; ?></span></p>
                <p><span class="label"><?php include LOOPIS_THEME_DIR . '/templates/admin/profile/user-email.php'; ?></span></p>
                <p><span class="label"><?php include LOOPIS_THEME_DIR . '/templates/admin/profile/user-phone.php'; ?></span></p>
            </div><!--wrapped-->

            <div class="wrapped">
                <h5>🎁 Annonser</h5>
                <hr>
                <p><span class="label">💚 <?php echo $count_submitted; ?> skapade</span></p>
                <p><span class="label">♻ <?php echo $given_percentage; ?>% lämnade</span></p>
                <p><span class="label">❌ <?php echo $count_deleted; ?> borttagna</span></p>
            </div><!--wrapped-->

            <div class="wrapped">
                <h5>🛟 Support</h5>
                <hr>
                <p><span class="label">🗒 <?php include LOOPIS_THEME_DIR . '/templates/admin/profile/user-support.php'; ?> ärenden</span></p>
            </div><!--wrapped-->

        </div><!--tab-panel-->



        <!-- Support Tab -->
        <div id="tab-support" class="tab-panel">
            <?php include LOOPIS_THEME_DIR . '/templates/admin/profile/user-posts-support.php'; ?>
        </div>
        <!-- Economy Tab -->
        <div id="tab-economy" class="tab-panel">
            <?php include LOOPIS_THEME_DIR . '/templates/admin/profile/user-economy.php'; ?>
        </div>
        <!-- Posts Tab -->
        <div id="tab-posts" class="tab-panel">
            <?php include LOOPIS_THEME_DIR . '/templates/admin/profile/user-posts.php'; ?>
        </div>
        <!-- About Tab -->
        <div id="tab-about" class="tab-panel">
            <?php include LOOPIS_THEME_DIR . '/templates/admin/profile/user-about.php'; ?>
        </div>
    </div><!--tab-content-->

</div><!--admin-block-->