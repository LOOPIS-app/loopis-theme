<?php
// filepath: /Users/mbp/Documents/PROJEKT.../LOOPIS/_APPEN/_GIT/loopis-theme/admin/raffle/raffle-tomorrow.php
/**
 * Tomorrow's raffle tab content
 * Shows posts that will be raffled tomorrow
 */

if (!defined('ABSPATH')) {
    exit;
}

// Set button visibility based on user capability
$visibility = current_user_can('loopis_raffle') ? 'visible' : 'hidden';
?>

<p class="small">💡 Här visas annonser som lottas imorgon.</p>
<h3>⏳ Lottning imorgon</h3>

<?php
// Query tomorrow's raffle posts
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'cat'            => '1',
    'date_query'     => array(
        array(
            'after'     => $today_start,
            'before'    => $today_end,
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

                <?php if (in_category('new') || in_category('removed')) : ?>
                    <?php include LOOPIS_THEME_DIR . '/pages/admin/raffle/raffle-actions.php'; ?>
                <?php else : ?>
                    <div class="notif-meta post-list-post-meta">
                        <p>
                            <?php
                            the_category(' ');
                            if (has_category(array('booked_locker', 'booked_custom'))) {
                                $fetcher = get_post_meta($post_id, 'fetcher', true);
                                if ($fetcher) {
                                    $fetchername = get_userdata($fetcher)->display_name;
                                    echo " av " . esc_html($fetchername);
                                }
                            }
                            ?>
                        </p>
                    </div>
                <?php endif; ?>

            </div><!--post-list-post-->

        <?php endwhile; ?>
    <?php else : ?>
        <p>💢 Inga saker att lotta imorgon.</p>
    <?php endif; ?>
</div><!--post-list-->

<?php wp_reset_postdata(); ?>