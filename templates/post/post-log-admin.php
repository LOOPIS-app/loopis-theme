<?php
/**
 * Show post details & settings for admin
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Set more variables
$now_time = get_now_time();
$authorname = get_the_author_meta('display_name', $author);
$authorlink = get_author_posts_url(get_the_author_meta('ID')); 
$participants = get_post_meta($post_id, 'participants', true); 
    if (is_array($participants) && !empty($participants)) {
        $participants = array_filter($participants);   /* remove gaps  */
        $participants = array_values($participants) ;}  /* re-index */
    if (is_array($participants)) { $count = count($participants); } else { $count = 0; }
$raffle_date = get_post_meta($post_id, 'raffle_date', true);
$edit_wpadmin = get_admin_url(null, 'post.php?post=' . $post_id . '&action=edit');
$edit_wpum = get_permalink() . 'edit';
?>

<div class="admin-block">
<?php include LOOPIS_THEME_DIR . '/templates/admin/links/admin-link.php'; ?>

<!-- QUEUE -->	
<?php if (!in_category( 'new' )) :
    $queue = get_post_meta($post_id, 'queue', true); 
    if (is_array($queue)) { $queue_count = count($queue); } else { $queue_count = 0; } ?>
    
<div class="columns">↓ <?php echo $queue_count; ?> deltagare i kö</div>	
<hr style="margin-bottom: 2px;">
<div class="logg">
    <?php if (!empty($queue)) {
    foreach ($queue as $user_id) {
        $user_info = get_userdata($user_id);
        if ($user_info !== false) {
            $display_name = $user_info->display_name;
            $author_posts_url = get_author_posts_url($user_info->ID);
            
            echo '<i class="fas fa-user"></i><a href="' . $author_posts_url . '">' . $display_name . '</a><br>'; } }
            } else { echo '<p>💢 Ingen står i kö.</p>'; } ?>
</div><!--logg-->
<?php endif;?>
    
<!-- PARTICIPANTS -->
<div class="columns">↓ <?php echo $count; ?> deltagare i lottning</div>	
<hr style="margin-bottom: 2px;">
<div class="logg">
    <?php if (!empty($participants)) {
    foreach ($participants as $user_id) {
        $user_info = get_userdata($user_id);
        if ($user_info !== false) {
            $display_name = $user_info->display_name;
            $author_posts_url = get_author_posts_url($user_info->ID);
            
            echo '<i class="fas fa-user"></i><a href="' . $author_posts_url . '">' . $display_name . '</a><br>'; } }
            } else { echo '<p>💢 Ingen har anmält intresse.</p>'; } ?>
</div><!--logg-->
    
<!-- LOGG -->
<div class="columns">↓ Logg</div>	
<hr style="margin-bottom: 2px;">
<div class="logg">
<?php
if ($previous_post_id) {
    // Recreated
    echo '<p><a href="' . get_permalink($previous_post_id) . '"><i class="fas fa-arrow-alt-circle-left"></i></a><i class="fas fa-arrow-alt-circle-right"></i>' . get_the_author_posts_link() . ' – ' . human_time_diff(get_the_time('U'), $now_time) . ' sen <span>' . get_the_time('Y-m-d H:i') . '</span></p>';
} else {
    // Created
    echo '<p><i class="fas fa-arrow-alt-circle-up"></i>' . get_the_author_posts_link() . ' – ' . human_time_diff(get_the_time('U'), $now_time) . ' sen <span>' . get_the_time('Y-m-d H:i') . '</span></p>';
}

// Raffled
if ($raffle_date) {
    echo '<p><i class="fas fa-dice-six"></i>' . $count . ' deltagare – ' . human_time_diff(strtotime($raffle_date), $now_time) . ' sen <span>' . $raffle_date . '</span></p>';
}

// Booked
if (in_category(array('booked_locker', 'booked_custom', 'locker', 'fetched'))) {
    $book_date = get_post_meta($post_id, 'book_date', true);
    echo '<p><i class="fas fa-heart"></i><a href="' . esc_url($fetcherlink) . '">' . $fetchername . '</a> – ' . human_time_diff(strtotime($book_date), $now_time) . ' sen <span>' . $book_date . '</span></p>';
}

// Booked (locker)
if (in_category('booked_locker')) {
    echo '<p><i class="fas fa-check-square"></i>' . get_the_author_posts_link() . ' – <i class="fas fa-walking"></i>' . $location . '...<span>';
    include LOOPIS_THEME_DIR . '/templates/post/timer-locker.php';
    echo '</span></p>';
}

// Booked (custom location)
if (in_category('booked_custom')) {
    echo '<p><i class="fas fa-mobile-alt"></i><a href="' . esc_url($fetcherlink) . '">' . $fetchername . '</a> – <i class="fas fa-walking"></i>' . $location . '...<span>';
    include LOOPIS_THEME_DIR . '/templates/post/timer-locker.php';
    echo '</span></p>';
}

// Locker or Fetched
if (in_category(array('locker', 'fetched')) && $location == 'Skåpet') {
    $locker_date = get_post_meta($post_id, 'locker_date', true);
    echo '<p><i class="fas fa-check-square"></i>' . get_the_author_posts_link() . ' – ' . human_time_diff(strtotime($locker_date), $now_time) . ' sen<span>' . $locker_date . '</span></p>';
}

// Locker
if (in_category('locker')) {
    echo '<p><i class="far fa-square"></i><a href="' . esc_url($fetcherlink) . '">' . $fetchername . '</a> <i class="fas fa-walking"></i>' . $location . '...<span>';
    include LOOPIS_THEME_DIR . '/templates/post/timer-fetch.php';
    echo '</span></p>';
}

// Fetched (locker)
if (in_category('fetched') && $location == 'Skåpet') {
    $fetch_date = get_post_meta($post_id, 'fetch_date', true);
    echo '<p><i class="far fa-check-square"></i><a href="' . esc_url($fetcherlink) . '">' . $fetchername . '</a> – ' . human_time_diff(strtotime($fetch_date), $now_time) . ' sen<span>' . $fetch_date . '</span></p>';
}

// Fetched (custom location)
if (in_category('fetched') && $location != 'Skåpet') {
    $fetch_date = get_post_meta($post_id, 'fetch_date', true);
    echo '<p><i class="far fa-check-square"></i><a href="' . esc_url($fetcherlink) . '">' . $fetchername . '</a> hämtade för ' . human_time_diff(strtotime($fetch_date), $now_time) . ' sen<span>' . $fetch_date . '</span></p>';
}

// Forwarded
if ($forward_post_id) {
    $forward_date = get_post_meta($post_id, 'forward_date', true);
    echo '<p><a href="' . get_permalink($forward_post_id) . '"><i class="fas fa-arrow-alt-circle-right"></i></a><a href="' . $fetcherlink . '">' . $fetchername . '</a> – ' . human_time_diff(strtotime($forward_date), $now_time) . ' sen <span>' . $forward_date . '</span></p>';
}

// Removed
if (in_category('removed')) {
    $remove_date = get_post_meta($post_id, 'remove_date', true);
    echo '<p><i class="fas fa-times-circle"></i>Borttagen – ' . human_time_diff(strtotime($remove_date), $now_time) . ' sen<span>' . $remove_date . '</span></p>';
}
?>
</div><!--logg-->

<div class="columns">Metadata</div>	
<hr style="margin-bottom: 2px;">
<div class="logg">
    <p><i class="fas fa-info-circle"></i> Post ID: <?php echo $post_id; ?></p>
    <p><i class="fas fa-user-circle"></i> Author ID: <?php echo $author; ?></p>
</div><!--logg-->	

<?php
// Edit images
$image_2_id = get_post_meta($post_id, 'image_2', true);
if (has_post_thumbnail()) {
    $thumbnail_id = get_post_thumbnail_id();
    $edit_image_url = admin_url('post.php?post=' . $thumbnail_id . '&action=edit');
    echo '<div class="columns">Bilder</div>	
<hr style="margin-bottom: 2px;"><div class="logg"><p><a href="' . esc_url($edit_image_url) . '"><i class="fas fa-image"></i> Redigera bild</a></p>';
}
if ($image_2_id) {
    $edit_image_2_url = admin_url('post.php?post=' . $image_2_id . '&action=edit');
    echo '<p><a href="' . esc_url($edit_image_2_url) . '"><i class="fas fa-image"></i> Redigera bild-2</a></p>';
}
echo '</div><!--logg-->';
?>

<div class="columns">Hantera</div>	
<hr style="margin-bottom: 2px;">
<!-- Fetched button -->
<?php if (in_category( array( 'locker', 'booked_custom' ))) : ?>
        <?php if(isset($_POST['fetched'])) { admin_action_fetched ($post_id); } ?>
        <form method="post" class="arb" action=""><button name="fetched" type="submit" class="admin-style blue small" onclick="return confirm('Har saken hämtats?')">Hämtat</button></form>
        <p class="info">Har hämtaren glömt tryck hämta? Tryck på knappen.</p>
<?php endif;?>

<!-- Notification button -->
        <?php if(isset($_POST['notif_manual'])) { admin_action_notif_manual ($post_id); } ?>
        <form method="post" class="arb" action=""><button name="notif_manual" type="submit" class="admin-style orange small" onclick="return confirm('Skicka manuellt?')">Skicka besked</button></form>
        <p class="info">Har inga mail skickats? Tryck på knappen.</p>

<!-- Edit & remove -->
<div class="logg">
<p><?php
echo '<a href="' . $edit_wpum . '">Redigera annons</a> '; 
echo ' <a href="' . $edit_wpadmin . '">👽 Redigera i WP-admin</a>';
    ?></p>
</div><!--logg-->
</div><!--admin-->