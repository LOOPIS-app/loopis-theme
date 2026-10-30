<?php
/**
 * Movement page
 * 
 * Shows all items in momvent (booked + in locker)
 * 
 * Reached on admin/?view=pages/movement.php
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<?php $admin_url = home_url('/admin/'); ?>

<h1><i class="fas fa-walking"></i> Saker i rörelse</h1>
<hr>
<p class="small">💡 Här ser du alla saker på väg att lämnas/hämtas.</p>

<p>I början av varje månad: gör <span class="big-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/inventory', $admin_url) ); ?>">📋 Inventering</a></span> av saker i skåpet.

<?php
// Extra functions
include_once LOOPIS_THEME_DIR . '/pages/admin/functions/reminder_symbols.php';
include_once LOOPIS_THEME_DIR . '/pages/admin/functions/reminder_sms.php';

// Get current timestamp
$now_time = (new DateTime(current_time('mysql')))->getTimestamp();
?>

<!-- FETCH IN LOCKER -->
<?php
// args
$args = array( 
	'post_type' => 'post',
	'cat' => loopis_cat('locker'),
    'posts_per_page' => -1
);

// query
$the_query = new WP_Query( $args );
$count = $the_query->found_posts;
?>

<h3>⏹ Skåpet</h3>
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' sak'; } else { echo ' saker'; } ?>
</div><div class="column2">
</div></div>
<hr>
<div class="post-list">

<?php if ($the_query->have_posts()) : ?>
    <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
        <?php
        $post_id = get_the_ID();
        $book_time = strtotime(get_post_meta($post_id, 'book_date', true));
        $locker_time = strtotime(get_post_meta($post_id, 'locker_date', true));
        $fetcher = get_post_meta($post_id, 'fetcher', true);
        if ($fetcher) { $fetcher_name = get_userdata($fetcher)->display_name; $fetcher_link = get_author_posts_url($fetcher); }
        $reminder_fetch = absint(get_post_meta($post_id, 'reminder_fetch', true)); ?>

        <div class="post-list-post" style="position:relative;" onclick="location.href='<?php the_permalink(); ?>';">
            <div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>

            <div class="logg">
                <p><i class="fas fa-heart"></i><a href="<?php echo esc_url($fetcher_link); ?>"><?php echo $fetcher_name; ?></a> – <?php echo human_time_diff($book_time, $now_time); ?> sedan</p>
                <p><i class="fas fa-check-square"></i><?php echo get_the_author_posts_link(); ?> – <?php echo human_time_diff($locker_time, $now_time); ?> sedan</p>
                <p><i class="far fa-square"></i><a href="<?php echo esc_url($fetcher_link); ?>"><?php echo $fetcher_name; ?></a>... <?php include LOOPIS_THEME_DIR . '/templates/post/timer-fetch.php';?> <?php echo reminder_symbols($reminder_fetch); ?></p>
            </div><!--logg-->
        </div><!--post-list-post-->

    <?php endwhile; ?>
<?php else : ?>
    <p>💢 Inga saker finns i skåpet just nu.</p>
<?php endif; ?>

</div><!--post-list-->	

<?php wp_reset_postdata(); ?>


<!-- LEAVE IN LOCKER -->
 <?php
// args
$args = array( 
	'post_type' => 'post',
     'cat' => loopis_cat('booked'),
     'posts_per_page' => -1,
);

// query
$the_query = new WP_Query( $args );
$count = $the_query->found_posts; 
?>

<h3>❤ På väg till skåpet</h3>
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' sak'; } else { echo ' saker'; } ?>
</div><div class="column2">
</div></div>
<hr>
<div class="post-list">
<?php if( $the_query->have_posts() ):
	while( $the_query->have_posts() ) : $the_query->the_post();
		$post_id = get_the_ID();
		$book_time = strtotime(get_post_meta($post_id, 'book_date', true));
		$author = get_post_field('post_author');
		$fetcher = get_post_meta($post_id, 'fetcher', true);
		if ($fetcher && $user = get_userdata($fetcher)) { $fetcher_name = $user->display_name; $fetcher_link = get_author_posts_url($fetcher);}
	    $reminder_leave = absint(get_post_meta($post_id, 'reminder_leave', true)); ?>

    <div class="post-list-post" style="position:relative;" onclick="location.href='<?php the_permalink(); ?>';">
        <div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>

    <div class="logg">
        <p><i class="fas fa-arrow-alt-circle-up"></i><?php echo get_the_author_posts_link(); ?> – <?php echo human_time_diff(get_the_time('U'), $now_time);?> sedan</p>		
        <p><i class="fas fa-heart"></i><a href="<?php echo esc_url($fetcher_link); ?>"><?php echo $fetcher_name; ?></a> – <?php echo human_time_diff($book_time, $now_time);?> sedan</p>
        <p><i class="far fa-square"></i><?php echo get_the_author_posts_link(); ?>... <?php include LOOPIS_THEME_DIR . '/templates/post/timer-locker.php';?> <?php echo reminder_symbols($reminder_leave); ?></p>
    </div><!--logg-->	
</div><!--post-list-post-->

    <?php endwhile; ?>
<?php else : ?>
		<p>💢 Inga saker är på väg till skåpet just nu.</p>
<?php endif; ?>

</div><!--post-list-->	

<?php wp_reset_postdata(); ?>

<!-- CUSTOM LOCATION -->
 <?php
// args
$args = array( 
	'post_type' => 'post',
	'cat' => loopis_cat('booked_custom'),
    'posts_per_page' => -1,
);

// query
$the_query = new WP_Query( $args );
$count = $the_query->found_posts;
?>

<h3>📍 Hämtas på annan adress</h3>
<div class="columns"><div class="column1">
↓ <?php echo $count; if ( $count == 1 ) { echo ' sak'; } else { echo ' saker'; } ?>
</div><div class="column2">
</div></div>
<hr>
<div class="post-list">

<?php if( $the_query->have_posts() ):
	while( $the_query->have_posts() ) : $the_query->the_post();
		$post_id = get_the_ID();
		$book_time = strtotime(get_post_meta($post_id, 'book_date', true));
		$author = get_post_field('post_author');
		$fetcher = get_post_meta($post_id, 'fetcher', true);
		if ($fetcher) { $fetcher_name = get_userdata($fetcher)->display_name; $fetcher_link = get_author_posts_url($fetcher);}
		$reminder_fetch = absint(get_post_meta($post_id, 'reminder_fetch', true)); ?>

<div class="post-list-post" style="position:relative;" onclick="location.href='<?php the_permalink(); ?>';">
	<div class="post-list-post-thumbnail"><?php the_post_thumbnail('thumbnail'); ?></div>

    <div class="logg">
        <p><i class="fas fa-arrow-alt-circle-up"></i><?php echo get_the_author_posts_link(); ?> – <?php echo human_time_diff(get_the_time('U'), $now_time);?> sedan</p>		
        <p><i class="fas fa-heart"></i><a href="<?php echo esc_url($fetcher_link); ?>"><?php echo $fetcher_name; ?></a> – <?php echo human_time_diff($book_time, $now_time);?> sedan</p>
    <p><i class="fas fa-mobile-alt"></i><a href="<?php echo esc_url($fetcher_link); ?>"><?php echo $fetcher_name; ?></a> ska hämta... <?php include LOOPIS_THEME_DIR . '/templates/post/timer-locker.php';?> <?php echo reminder_symbols($reminder_fetch); ?></p>
    </div><!--logg-->	
</div><!--post-list-post-->	
				
    <?php endwhile; ?>
<?php else : ?>
		<p>💢 Inga saker ska hämtas på annan adress just nu.</p>
<?php endif; ?>

</div><!--post-list-->	

<?php wp_reset_postdata(); ?>


<!--Manual start-->
<?php if (current_user_can('manage_options') ) : ?>
<div class="wrapped admin-block">
<p>🚨 Extrema situationer</p>
<hr>
<p class="small">💡 Verktyg endast för webmaster.</p>
		<?php if(isset($_POST['start_reminders'])) { cron_job_reminders(); } ?>
		<form method="post" class="arb" action=""><button name="start_reminders" type="submit" class="red small" onclick="return confirm('Vill du skicka påminnelser manuellt?')">🤖 Påminn nu...</button></form>
		<p class="info">Tryck på knappen för att skicka påminnelser manuellt.</p>
</div>
<?php endif; ?>