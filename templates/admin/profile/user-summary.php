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
    <?php include_once LOOPIS_THEME_DIR . '/templates/admin/links/admin-link.php'; ?>

    <!-- Tab Navigation -->
    <div class="tab-nav">
        <nav class="profile-navbar">
            <a href="#" class="tab-link" data-tab="tab-info">👤</a>
            <a href="#" class="tab-link" data-tab="tab-submitted">⬆️</a>
            <a href="#" class="tab-link" data-tab="tab-fetched">⬇️</a>
            <a href="#" class="tab-link" data-tab="tab-support">🛟</a>
        </nav>
    </div><!--tab-nav-->

    <div class="tab-content">

        <!-- Member Info Tab -->
        <div id="tab-info" class="tab-panel">

            <div class="wrapped">
                <h5>📋 Medlemsregister</h5>
                <hr>
                <p><span class="label"><?php include_once LOOPIS_THEME_DIR . '/templates/admin/profile/user-id.php'; ?></span></p>
                <p><span class="label"><?php include_once LOOPIS_THEME_DIR . '/templates/admin/profile/user-age.php'; ?></span></p>
                <p><span class="label"><?php include_once LOOPIS_THEME_DIR . '/templates/admin/profile/user-gender.php'; ?></span></p>
                <p><span class="label"><?php include_once LOOPIS_THEME_DIR . '/templates/admin/profile/user-email.php'; ?></span></p>
                <p><span class="label"><?php include_once LOOPIS_THEME_DIR . '/templates/admin/profile/user-phone.php'; ?></span></p>
            </div><!--wrapped-->

            <div class="wrapped">
                <h5>📒 Kvitton</h5>
                <hr>
                <?php include_once LOOPIS_THEME_DIR . '/templates/user/profile/user-payments.php'; ?>
            </div><!--wrapped-->

            <div class="wrapped">
                <h5>🎁 Saker att få</h5>
                <hr>
                <p><span class="label">⬆ <?php echo $count_submitted; ?> annonser</span> <span class="small">♻ <?php echo $given_percentage; ?>%</span></p>
                <p><span class="label">❌ <?php echo $count_deleted; ?> borttagna</span></p>
            </div><!--wrapped-->

            <div class="wrapped">
                <h5>🛟 Support</h5>
                <hr>
                <p><span class="label">🗒 <?php include_once LOOPIS_THEME_DIR . '/templates/admin/profile/user-support.php'; ?> ärenden</span></p>
            </div><!--wrapped-->

            <!-- Activity Section -->
            <h3>🧮 Aktivitet</h3>
            <hr>

            <!-- Rainbow Coins -->
            <div class="economy wrapped">
                <p>Regnbågsmynt<span class="right"><img src="<?php echo LOOPIS_THEME_URI; ?>/assets/img/coin.png" alt="coin symbol" style="height:15px; width: auto;"></span></p>
                <hr>
                <p><b><?php echo $payments_membership; ?></b> köp av medlemskap <span class="plus right">+<?php echo $membership_coins; ?></span></p>
                <?php if ($payments_coins > 0) : ?>
                    <p><b><?php echo $payments_coins; ?></b> köp av extra mynt <span class="plus right">+<?php echo $bought_coins; ?></span></p>
                <?php endif; ?>
                <!--p><b><?php echo $clovers; ?></b> fyrklöver <span class="plus right">+<?php echo $clover_coins; ?></span></p>
                <p><b><?php echo $stars; ?></b> stjärnor <span class="plus right">+<?php echo $star_coins; ?></span></p-->
                <p><b><?php echo $count_given; ?></b> saker lämnade <span class="plus right">+<?php echo $count_given; ?></span></p>
                <p><b><?php echo $count_booked; ?></b> saker hämtade/paxade <span class="minus right">–<?php echo $count_booked; ?></span></p>
                <hr>
                <p>&nbsp;<span class="right">Totalt: <b><?php echo $coins - $clover_coins - $star_coins; ?></b></span></p>
            </div>

            <!-- Clovers -->
            <div class="economy wrapped">
                <p>Fyrklöver<span class="right">🍀</span></p>
                <hr>
                <p><b><?php echo $count_submitted; ?></b> annonser skapade <span class="plus right">+<?php echo $count_submitted; ?></span></p>
                <p><b><?php echo $count_booked; ?></b> saker hämtade <span class="plus right">+<?php echo $count_booked; ?></span></p>
                <hr>
                <p>&nbsp;<span class="right">Totalt: <b><?php echo $clovers; ?></b></span></p>
                <p class="small">
                    <?php if ($clover_coins > 0) : ?>
                        → <b><?php echo $clover_coins; ?> mynt</b> i belöning! 🎉
                    <?php else : ?>
                        → Inga mynt i belöning.
                    <?php endif; ?>
                </p>
            </div>

            <!-- Stars -->
            <div class="economy wrapped">
                <p>Guldstjärnor<span class="right">⭐</span></p>
                <hr>
                <?php include_once LOOPIS_THEME_DIR . '/templates/user/profile/user-rewards.php'; ?>
                <hr>
                <p>&nbsp;<span class="right">Totalt: <b><?php echo $stars; ?></b></span></p>
                <p class="small">
                    <?php if ($star_coins > 0) : ?>
                        → <b><?php echo $star_coins; ?> mynt</b> i belöning! 🎉
                    <?php else : ?>
                        → Inga mynt i belöning.
                    <?php endif; ?>
                </p>
            </div>

        </div><!--tab-panel-->

        <!-- Submitted Posts Tab -->
        <div id="tab-submitted" class="tab-panel">
            <?php include_once LOOPIS_THEME_DIR . '/templates/admin/profile/user-posts-submitted.php'; ?>
        </div>

        <!-- Fetched Posts Tab -->
        <div id="tab-fetched" class="tab-panel">
            <?php include_once LOOPIS_THEME_DIR . '/templates/admin/profile/user-posts-fetched.php'; ?>
        </div>

        <!-- Support Tab -->
        <div id="tab-support" class="tab-panel">
            <?php include_once LOOPIS_THEME_DIR . '/templates/admin/profile/user-posts-support.php'; ?>
        </div>

    </div><!--tab-content-->

</div><!--admin-block-->