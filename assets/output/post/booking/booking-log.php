<?php
/**
 * Show post details for user/visitor
 *
 * Used in single.php
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="logg">
<p><i class="fas fa-envelope"></i><?php echo get_the_author_posts_link(); ?> skickade för <?php echo human_time_diff(get_the_time('U'), current_time('timestamp'))?> sen <span><?php the_time('Y-m-d H:i')?></span></p>

<?php if ($status_slug === 'confirmed') { ?>
<p><i class="fas fa-check-circle"></i><?php echo $ownername ?> bekräftade för <?php echo human_time_diff(strtotime(get_field('date_confirmed')), current_time('timestamp'))?> sen <span><?php echo get_field('date_confirmed')?></span></p><?php } ?>

<?php if ($status_slug === 'borrowed') { ?>
<p><i class="fas fa-hourglass-start"></i><?php echo get_the_author_posts_link(); ?> hämtade för <?php echo human_time_diff(strtotime(get_field('date_borrowed')), current_time('timestamp'))?> sen <span><?php echo get_field('date_borrowed')?></span></p><?php } ?>

<?php if ($status_slug === 'returned') { ?>
<p><i class="fas fa-undo-alt"></i><?php echo get_the_author_posts_link(); ?> återlämnade för <?php echo human_time_diff(strtotime(get_field('date_returned')), current_time('timestamp'))?> sen <span><?php echo get_field('date_returned')?></span></p><?php } ?>

<?php if ($status_slug === 'canceled') { ?>
<p><i class="fas fa-ban"></i>Avbruten för <?php echo human_time_diff(strtotime(get_field('date_canceled')), current_time('timestamp'))?> sen <span><?php echo get_field('date_canceled')?></span></p><?php } ?>

</div><!--logg-->	