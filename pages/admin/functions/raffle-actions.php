<?php
/**
 * Raffle action buttons
 * Handles all post actions for raffle management
 * Used in both today and tomorrow tabs
 */

if (!defined('ABSPATH')) {
    exit;
}

// This file expects: $post_id, $participant_count, $participants, $location, $visibility
?>

<!-- Removed posts - Erase action -->
<?php if (in_category('removed')) : ?>
    <?php if (isset($_POST['erase' . $post_id])) {
        admin_action_erase($post_id);
    } ?>
    <form method="post" class="arb" action="">
        <button name="erase<?php echo $post_id; ?>" 
                type="submit" 
                class="notif-button small blue" 
                onclick="return confirm('Vill du hantera annonsen manuellt?')" 
                style="visibility:<?php echo $visibility; ?>">🔥</button>
    </form>
    <div class="notif-meta post-list-post-meta"><p>❌ Borttagen</p></div>

<!-- New posts - Raffle actions -->
<?php elseif (in_category('new')) : ?>

    <!-- No participants - Switch category -->
    <?php if ($participant_count == 0) : ?>
        <?php if (isset($_POST['switch' . $post_id])) {
            admin_action_switch($post_id);
        } ?>
        <form method="post" class="arb" action="">
            <button name="switch<?php echo $post_id; ?>" 
                    type="submit" 
                    class="notif-button small grey" 
                    onclick="return confirm('Vill du hantera annonsen manuellt?')" 
                    style="visibility:<?php echo $visibility; ?>">🟢</button>
        </form>
        <div class="notif-meta post-list-post-meta"><p>⚪ Ingen intresserad...</p></div>
    <?php endif; ?>

    <!-- One participant - Locker location -->
    <?php if ($participant_count == 1 && $location == 'Skåpet') : ?>
        <?php
        $winner_id = $participants[0];
        $winner_name = get_user_by('ID', $winner_id)->display_name;
        ?>
        <?php if (isset($_POST['alone' . $post_id])) {
            admin_action_book_locker($winner_id, $post_id);
        } ?>
        <form method="post" class="arb" action="">
            <button name="alone<?php echo $post_id; ?>" 
                    type="submit" 
                    class="notif-button small red" 
                    onclick="return confirm('Vill du hantera annonsen manuellt?')" 
                    style="visibility:<?php echo $visibility; ?>">❤</button>
        </form>
        <div class="notif-meta post-list-post-meta"><p>🧡 <span><?php echo esc_html($winner_name); ?>...</span></p></div>
    <?php endif; ?>

    <!-- One participant - Custom location -->
    <?php if ($participant_count == 1 && $location != 'Skåpet') : ?>
        <?php
        $winner_id = $participants[0];
        $winner_name = get_user_by('ID', $winner_id)->display_name;
        ?>
        <?php if (isset($_POST['alone' . $post_id])) {
            admin_action_book_custom($winner_id, $post_id);
        } ?>
        <form method="post" class="arb" action="">
            <button name="alone<?php echo $post_id; ?>" 
                    type="submit" 
                    class="notif-button small red" 
                    onclick="return confirm('Vill du hantera annonsen manuellt?')" 
                    style="visibility:<?php echo $visibility; ?>">❤</button>
        </form>
        <div class="notif-meta post-list-post-meta"><p>🧡 <span><?php echo esc_html($winner_name); ?>...</span></p></div>
    <?php endif; ?>

    <!-- Multiple participants - Locker location -->
    <?php if ($participant_count > 1 && $location == 'Skåpet') : ?>
        <?php if (isset($_POST['raffle_locker' . $post_id])) {
            admin_action_raffle_locker($participants, $participant_count, $post_id);
        } ?>
        <form method="post" class="arb" action="">
            <button name="raffle_locker<?php echo $post_id; ?>" 
                    type="submit" 
                    class="notif-button small orange" 
                    onclick="return confirm('Vill du hantera annonsen manuellt?')" 
                    style="visibility:<?php echo $visibility; ?>">🎲</button>
        </form>
        <div class="notif-meta post-list-post-meta"><p>🎲 <?php echo $participant_count; ?> deltagare...</p></div>
    <?php endif; ?>

    <!-- Multiple participants - Custom location -->
    <?php if ($participant_count > 1 && $location != 'Skåpet') : ?>
        <?php if (isset($_POST['raffle_custom' . $post_id])) {
            admin_action_raffle_custom($participants, $participant_count, $post_id);
        } ?>
        <form method="post" class="arb" action="">
            <button name="raffle_custom<?php echo $post_id; ?>" 
                    type="submit" 
                    class="notif-button small orange" 
                    onclick="return confirm('Vill du hantera annonsen manuellt?')" 
                    style="visibility:<?php echo $visibility; ?>">🎲</button>
        </form>
        <div class="notif-meta post-list-post-meta"><p>🎲 <?php echo $participant_count; ?> deltagare...</p></div>
    <?php endif; ?>

<?php endif; ?>