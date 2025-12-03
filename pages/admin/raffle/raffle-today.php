<?php
/**
 * Today's raffle tab content
 * Shows posts that are being raffled today
 */

if (!defined('ABSPATH')) {
    exit;
}

// Set button visibility based on user capability
$visibility = current_user_can('loopis_raffle') ? 'visible' : 'hidden';
?>

<p class="small">💡 Här visas annonser som lottas idag.</p>

<?php if ($complete_count == 0) : ?>
    <h3>✅ Lottning idag</h3>
<?php else : ?>
    <h3>⌛ Lottning idag</h3>
<?php endif; ?>

<?php
// Query today's raffle posts
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'date_query'     => array(
        array(
            'after'     => $yesterday_start,
            'before'    => $yesterday_end,
            'inclusive' => true,
        ),
    ),
);

$the_query = new WP_Query($args);
$count = $the_query->found_posts;
?>

<div class="columns">
    <div class="column1">
        ↓ <?php echo $count; ?> <?php echo ($count == 1) ? 'annons' : 'annonser'; ?>
    </div>
    <div class="column2"></div>
</div>
<hr>

<div class="post-list">
    <?php if ($the_query->have_posts()) : ?>
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <?php
            $post_id = get_the_ID();
            $location = get_post_meta($post_id, 'location', true);
            $participants = get_post_meta($post_id, 'participants', true);
            
            if (is_array($participants) && !empty($participants)) {
                $participants = array_filter($participants);
                $participants = array_values($participants);
            }
            
            $participant_count = is_array($participants) ? count($participants) : 0;
            ?>

            <div class="post-list-post" style="position:relative;" onclick="location.href='<?php the_permalink(); ?>';">
                <div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>
                <div class="post-list-post-title"><?php the_title(); ?></div>

                <?php if (in_category('new')) : ?>
                    <?php include LOOPIS_THEME_DIR . '/pages/admin/raffle/raffle-actions.php'; ?>
                <?php elseif (in_category('removed')) : ?>
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
                <?php else : ?>
                    <div class="notif-meta post-list-post-meta">
                        <p>
                            <?php
                            if (has_category(array('booked_locker', 'booked_custom'))) {
                                $fetcher = get_post_meta($post_id, 'fetcher', true);
                                if ($fetcher) {
                                    $fetchername = get_userdata($fetcher)->display_name;
                                    echo "❤️ " . esc_html($fetchername);
                                }
                                if ($participant_count > 1) {
                                    echo " ← 🎲 " . $participant_count . " deltagare";
                                }
                            } else {
                                the_category(' ');
                            }
                            ?>
                        </p>
                    </div>
                <?php endif; ?>

            </div><!--post-list-post-->

        <?php endwhile; ?>
    <?php else : ?>
        <p>💢 Inga saker att lotta idag.</p>
    <?php endif; ?>
</div><!--post-list-->

<?php wp_reset_postdata(); ?>

<!-- Manual Raffle Start -->
<?php if (current_user_can('loopis_raffle') && $complete_count > 0) : ?>
    <?php if (isset($_POST['start_raffle'])) {
        cron_job_raffle();
    } ?>
    <form method="post" class="arb" action="">
        <button name="start_raffle" 
                type="submit" 
                class="red small" 
                onclick="return confirm('Vill du starta lottning manuellt?')">
            🤖 Lotta nu...
        </button>
    </form>
    <p class="info">Tryck på knappen för att starta dagens lottning manuellt.</p>
<?php endif; ?>