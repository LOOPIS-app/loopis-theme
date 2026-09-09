<?php
/**
 * Locker traffic page
 * 
 * Reached on /admin/?view=traffic-locker
 * 
 * Shows locker activity for a selected date
 * Displays chronological log of items left and fetched from lockers
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🔐 Trafik i skåpen</h1>
<hr>
<p class="small">💡 Här ser du trafik i skåpen för valt datum.</p>

<?php
// Get the selected date from the dropdown (via GET request)
$selected_date = isset($_GET['locker_date']) ? sanitize_text_field($_GET['locker_date']) : date('Y-m-d');
?>

<!-- Date Selection Form -->
<div class="loopis-form loopis-filter">
<form method="GET" action="<?php echo esc_url(home_url('/admin/')); ?>" style="margin-bottom: 20px;">
    <input type="hidden" name="view" value="traffic-locker">
    <label for="locker_date">Välj dag: </label>
    <select id="locker_date" name="locker_date" onchange="this.form.submit()">
        <?php
        // Generate dropdown for the last 7 days
        for ($i = 0; $i < 7; $i++) :
            $date = date('Y-m-d', strtotime("-$i days"));
            $weekday = date('l', strtotime("-$i days"));
            $formatted_date = $date . ' ' . $weekday;
            $selected = ($date === $selected_date) ? 'selected' : '';
            ?>
            <option value="<?php echo esc_attr($date); ?>" <?php echo $selected; ?>>
                <?php echo esc_html($formatted_date); ?>
            </option>
        <?php endfor; ?>
    </select>
</form>
</div> <!-- loopis-form -->

<?php
// Initialize variables for counts and entries
$entries = array();
$count_actions = 0;
$count_fetched = 0;
$count_locker = 0;
$fetcher_ids = array();
$author_ids = array();

// Query 1: Posts with 'locker_date' matching the selected date
$args_locker = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'locker_date',
            'value'   => $selected_date,
            'compare' => 'LIKE',
        ),
    ),
);

$query_locker = new WP_Query($args_locker);

if ($query_locker->have_posts()) :
    while ($query_locker->have_posts()) : $query_locker->the_post();
        $count_locker++;
        $count_actions++;

        $post_id = get_the_ID();
        $locker_date = get_post_meta($post_id, 'locker_date', true);
        $locker_time = date('H:i:s', strtotime($locker_date));

        $author_id = get_the_author_meta('ID');
        $author_display_name = get_the_author_meta('display_name', $author_id);
        $author_url = get_author_posts_url($author_id);

        if ($author_id) {
            $author_ids[] = $author_id;
        }

        $post_title = get_the_title();
        $post_permalink = get_permalink();

        $entries[] = array(
            'time'     => $locker_time,
            'display'  => '⏹ <a href="' . esc_url($author_url) . '">' . esc_html($author_display_name) . '</a> lämnade <a href="' . esc_url($post_permalink) . '">' . esc_html($post_title) . '</a>',
            'username' => $author_display_name,
        );
    endwhile;
endif;

// Query 2: Posts with 'fetch_date' matching the selected date
$args_fetch = array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'fetch_date',
            'value'   => $selected_date,
            'compare' => 'LIKE',
        ),
    ),
);

$query_fetch = new WP_Query($args_fetch);

if ($query_fetch->have_posts()) :
    while ($query_fetch->have_posts()) : $query_fetch->the_post();
        $count_fetched++;
        $count_actions++;

        $post_id = get_the_ID();
        $fetch_date = get_post_meta($post_id, 'fetch_date', true);
        $fetch_time = date('H:i:s', strtotime($fetch_date));

        $fetcher_id = get_post_meta($post_id, 'fetcher', true);
        
        if ($fetcher_id) {
            $fetcher_ids[] = $fetcher_id;
        }

        $fetcher_display_name = $fetcher_id ? get_the_author_meta('display_name', $fetcher_id) : 'Unknown User';
        $fetcher_url = $fetcher_id ? get_author_posts_url($fetcher_id) : '#';

        $post_title = get_the_title();
        $post_permalink = get_permalink();

        $entries[] = array(
            'time'     => $fetch_time,
            'display'  => '☑ <a href="' . esc_url($fetcher_url) . '">' . esc_html($fetcher_display_name) . '</a> hämtade <a href="' . esc_url($post_permalink) . '">' . esc_html($post_title) . '</a>',
            'username' => $fetcher_display_name,
        );
    endwhile;
endif;

wp_reset_postdata();

// Sort all entries by time (chronologically)
usort($entries, function ($a, $b) {
    return strcmp($a['time'], $b['time']);
});
?>

<!-- Activity Log -->
<h3>♻ Logg</h3>
<div class="columns">
    <div class="column1">↓ <?php echo $count_actions; ?> händelser</div>
    <div class="column2"><?php echo esc_html($selected_date); ?></div>
</div>
<hr style="margin-bottom: 3px;">

<div class="logg" style="padding: 0;">
    <?php if (!empty($entries)) : ?>
        <ul>
            <?php foreach ($entries as $entry) : ?>
                <li>
                    <?php echo esc_html($entry['time']); ?> &nbsp;
                    <?php echo $entry['display']; ?>
                    <a href="#" class="ping-bell" data-username="<?php echo esc_attr($entry['username']); ?>">🔔</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>Inga händelser för det valda datumet.</p>
    <?php endif; ?>
</div>

<?php
// Calculate unique counts
$count_fetchers = count(array_unique($fetcher_ids));
$count_authors = count(array_unique($author_ids));
$count_visitors = count(array_unique(array_merge($fetcher_ids, $author_ids)));
?>

<!-- Summary -->
<h3>🧮 Summering</h3>
<hr>

<div class="logg" style="padding: 0;">
    <p>
        👤 <?php echo esc_html($count_visitors); ?> besökare 
        <a href="#" id="ping-all" style="text-decoration: none;">🔔</a><br>
        ⏹ <?php echo esc_html($count_authors); ?> lämnare 
        (<?php echo esc_html($count_locker); ?> saker)<br>
        ☑ <?php echo esc_html($count_fetchers); ?> hämtare 
        (<?php echo esc_html($count_fetched); ?> saker)
    </p>
</div>

<!-- Mention Box -->
<h6>🔔 Pinga?</h6>
<hr>
<textarea id="mention-box" 
          style="width: 100%; height: 55px; font-size: 15px;" 
          placeholder="Användare att pinga visas här..."></textarea>

<script>
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const mentionBox = document.getElementById('mention-box');

        /**
         * Add username to mention box if not already present
         */
        function addMention(username) {
            const currentValue = mentionBox.value.trim();
            const currentMentions = currentValue.split(/\s+/).filter(Boolean);
            const mention = '@' + username;

            if (!currentMentions.includes(mention)) {
                const newValue = currentValue + ' ' + mention;
                mentionBox.value = newValue.trim();
            }
        }

        // Individual ping buttons
        const bellLinks = document.querySelectorAll('.ping-bell');
        bellLinks.forEach(function(bell) {
            bell.addEventListener('click', function(event) {
                event.preventDefault();
                const username = bell.getAttribute('data-username');
                addMention(username);
            });
        });

        // Ping all button
        const pingAllLink = document.getElementById('ping-all');
        if (pingAllLink) {
            pingAllLink.addEventListener('click', function(event) {
                event.preventDefault();

                // Get all unique usernames
                const usernames = Array.from(document.querySelectorAll('.ping-bell'))
                    .map(bell => '@' + bell.getAttribute('data-username'));

                // Get current mentions
                const currentValue = mentionBox.value.trim();
                const currentMentions = currentValue.split(/\s+/).filter(Boolean);

                // Merge and deduplicate
                const uniqueMentions = new Set([...currentMentions, ...usernames]);

                // Update mention box
                mentionBox.value = Array.from(uniqueMentions).join(' ').trim();
            });
        }
    });
})();
</script>