<?php
/**
 * Show post details & settings for admin
 *
 * Used in single-booking.php
 */
 
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="admin-block">
<?php include LOOPIS_THEME_DIR . '/assets/output/admin/admin-link.php'; ?>

<!-- LOGG -->
<div class="columns">↓ Logg</div>	
<hr style="margin-bottom: 2px;">
<div class="logg">

<p><i class="fas fa-envelope"></i><?php echo get_the_author_posts_link(); ?> – <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen <span><?php the_time('Y-m-d H:i')?></span></p>

<?php if (in_array($status_slug, array('confirmed', 'borrowed', 'returned'))) { ?>
<p><i class="fas fa-check-circle"></i><?php echo $ownername ?> – <?php echo human_time_diff(strtotime(get_field('date_confirmed')), current_time('timestamp'))?> sen <span><?php echo get_field('date_confirmed')?></span></p><?php } ?>

<?php if (in_array($status_slug, array('borrowed', 'returned'))) { ?>
<p><i class="fas fa-hourglass-start"></i><?php echo get_the_author_posts_link(); ?> – <?php echo human_time_diff(strtotime(get_field('date_borrowed')), current_time('timestamp'))?> sen <span><?php echo get_field('date_borrowed')?></span></p><?php } ?>

<?php if ($status_slug === 'returned') { ?>
<p><i class="fas fa-undo-alt"></i><?php echo get_the_author_posts_link(); ?> – <?php echo human_time_diff(strtotime(get_field('date_returned')), current_time('timestamp'))?> sen <span><?php echo get_field('date_returned')?></span></p><?php } ?>

<?php if ($status_slug === 'canceled') { ?>
<p><i class="fas fa-ban"></i>Avbruten – <?php echo human_time_diff(strtotime(get_field('date_canceled')), current_time('timestamp'))?> sen <span><?php echo get_field('date_canceled')?></span></p><?php } ?>

</div><!--logg-->
</div><!--admin-block-->