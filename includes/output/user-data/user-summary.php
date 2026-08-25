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
wp_enqueue_script('loopis-tabs', LOOPIS_THEME_URI . '/assets/js/tabs.js', array(), '1.0.0', true);
?>

<div class="admin-block">
    <?php include LOOPIS_THEME_DIR . '/templates/links/admin-link.php'; ?>

    <!-- Tab Navigation -->
    <div class="tab-nav">
        <nav class="tab-navbar">
            <a href="#" class="tab-link" data-tab="tab-info">👤</a>
            <a href="#" class="tab-link" data-tab="tab-posts">🎁</a>
            <a href="#" class="tab-link" data-tab="tab-economy">🧮</a>
            <a href="#" class="tab-link" data-tab="tab-ledger">📕</a>
            <a href="#" class="tab-link" data-tab="tab-support">🛟</a>
        </nav>
    </div><!--tab-nav-->

    <div class="tab-content"> 

        <!-- Member Info Tab -->
        <div id="tab-info" class="tab-panel">
        <p class="small">💡 Data och statistik.</p>

            <div class="wrapped">
                <h7>📋 Medlemsregister</h7>
                <hr>
                <p>👤 Användarnamn: <b><?php echo $user->user_login ?></b></p>
                <p>📍 Område: <b><?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-area.php'; ?></b></p>
                <p>🚼 Ålder: <b><?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-age.php'; ?></b></p>
                <p>⚧ Kön: <b><?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-gender.php'; ?></b></p>
                <p>📧 E-post: <b><?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-email.php'; ?></b></p>
                <p>📱 Mobilnummer: <b><?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-phone.php'; ?></b></p>
                <p>🔧 User-ID: <b><?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-id.php'; ?></b></p>
            </div><!--wrapped-->

            <div class="wrapped">
                <h5>📊 Statistik</h5>
                <hr>
                <p>♻ <?php echo $given_percentage; ?>% av <?php echo $count_submitted; ?> annonser lämnade</p>
                <p>🛟 <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-posts-support-count.php'; ?> supportfrågor skapade</p>
                <p>⏱ Senaste inloggning: <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-last-login.php'; ?></p>
            </div><!--wrapped-->

        </div><!--tab-panel-->

        <!-- Posts Tab -->
        <div id="tab-posts" class="tab-panel">
            <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-posts.php'; ?>
        </div>
        <!-- Economy Tab -->
        <div id="tab-economy" class="tab-panel">
            <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-economy.php'; ?>
        </div>
        <!-- Ledger Tab -->
        <div id="tab-ledger" class="tab-panel">
            <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-ledger.php'; ?>
        </div>
        <!-- Support Tab -->
        <div id="tab-support" class="tab-panel">
            <?php include LOOPIS_THEME_DIR . '/includes/output/user-data/user-posts-support.php'; ?>
        </div>
    </div><!--tab-content-->

</div><!--admin-block-->