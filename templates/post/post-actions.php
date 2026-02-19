<?php
/**
 * Show post actions for user
 * 
 * Should be split into multiple files later?
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get author variables
$authorname = get_the_author_meta('display_name', $author);
$authorlink = get_the_author_posts_link();

// Get participants variables
$participants = get_post_meta($post_id, 'participants', true);
	if (!is_array($participants)) { $participants = array(); }
$queue = get_post_meta($post_id, 'queue', true);
	if (!is_array($queue)) { $queue = array(); }
	$queue_total = count($queue);
	$queue_position = array_search($current, $queue) + 1; 

// Get locker code
$locker_code = get_locker_code(LOCKER_ID);
?>

<!-- NEW POST -->
<?php if (in_category( 'new' )) : ?>
<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-participate.php'; ?>

<?php if ( $current != $author ) : ?>
	<?php if (!in_array($current, $participants)) :
		if(isset($_POST['participate'])) { action_participate($post_id); } ?>
		<form method="post" class="arb" action=""><button name="participate" type="submit" class="orange" onclick="return confirm('Vill du delta i lottning?')">Delta i lottning</button></form>
		<p class="info">Tryck på knappen om du vill delta i lottning<?php echo raffle_time(); ?>.</p>
	<?php endif;?>

	<?php if (in_array($current, $participants)) : ?>
		<p>⏳ Du väntar på lottning <?php echo raffle_time(); ?>...</p>		
		<?php if(isset($_POST['un_participate'])) { action_unparticipate($post_id); } ?>
		<form method="post" class="arb" action=""><button name="un_participate" type="submit" class="red small" onclick="return confirm('Vill du lämna lottning?')">Lämna lottning</button></form>
		<p class="info">Tryck på knappen om du inte längre vill delta i lottning.</p>
	<?php endif;?>
<?php endif;?>
	
	<?php if ( $current == $author ) : ?>
		<p>⏳ Du väntar på lottning <?php echo raffle_time(); ?>...</p>
	<?php endif;?>

<?php endif;?>


<!-- AVAILABLE POST -->
<?php if (in_category( 'first' )) : ?>
<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-book.php'; ?>

	<p>Ingen ville delta i lottning så nu gäller <span class="label">🟢 Först till kvarn</span></p>

	<?php if ( $current == $author ) : ?>
		<p>⏳ Du väntar på att någon ska paxa...</p>
		<p>🧘 Vänta gärna så länge som möjligt med att ta bort din annons. Plötsligt kanske någon ny eller gammal medlem behöver det du ger bort!</p>
	<?php endif;?>
		
	<?php if ( $current != $author && $location == 'Skåpet') : ?>
	<p>Den som först paxar får hämta i <span class="label"><i class="fas fa-walking"></i><?php echo $location; ?></span><p>
		<?php if(isset($_POST['book_locker'])) { action_book_locker($post_id); } ?>
		<form method="post" class="arb" action=""><button name="book_locker" type="submit" class="red" onclick="return confirm('Vill du paxa och hämta i skåpet?')">Paxa</button></form>
		<p class="info">Tryck på knappen för att paxa.</p>
	<?php endif;?>
	
	<?php if ( $current != $author && $location != 'Skåpet') : ?>
		<p>Den som först paxar får hämta på <span class="label"><i class="fas fa-walking"></i><?php echo $location ?></span><p>
		<?php if(isset($_POST['book_custom'])) { action_book_custom ($post_id); } ?>
		<form method="post" class="arb" action=""><button name="book_custom" type="submit" class="red" onclick="return confirm('Vill du paxa och hämta på <?php echo $location ?>?')">Paxa</button></form>
		<p class="info">Tryck på knappen för att paxa.</p>
	<?php endif;?>

	<?php if ( current_user_can('loopis_storage_book')) :
	include_once LOOPIS_THEME_DIR . '/templates/post/storage-booking.php';
	endif; ?>
	
<?php endif;?>


<!-- POST IN STORAGE -->
<?php if (in_category( 'storage' )) : ?>

	<p>📦 Denna annons ligger i lager. Kan bara paxas och hämtas på ett event.</p>

	<?php if ( current_user_can('loopis_storage_book') || $current == $author ) :
	include_once LOOPIS_THEME_DIR . '/templates/post/storage-booking.php';
	endif; ?>

	<?php if ( current_user_can('administrator') || current_user_can('manager') ) : ?>
		<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-storage.php'; ?>
		<?php if(isset($_POST['publish_storage'])) { admin_action_publish_storage ($post_id); } ?>
		<form method="post" class="arb" action=""><button name="publish_storage" type="submit" class="small" onclick="return confirm('Publiera som ny annons?')">Publicera</button></form>
		<p class="info">Vill du publicera annonsen? Tryck på knappen.</p>
	<?php endif; ?>
	
<?php endif;?>


<!-- PAUSED POST -->
<?php if (in_category( 'paused' )) : ?>

	<p>⚠ Denna annons har pausats av givaren och kan inte paxas.</p>	

	<?php if ( $current == $author ) : ?>
		<p>Gå till dina <span class="big-link"><a href="/pages/activity/posts/?status=paused">😎 Pausade annonser</a></span> för att aktivera.<p>
	<?php endif;?>
	
	<?php if ( $current != $author ) : ?>
		<p class="small">💡 Du får vänta på att givaren aktiverar sina annonser igen.<p>
	<?php endif;?>

<?php endif;?>


<!-- ARCHIVED POST -->
<?php if (in_category( 'archived' )) : ?>

<p>⚠ Denna annons är för tillfället automatiskt arkiverad eftersom den är äldre än 4 veckor.</p>	

<?php if ( $current == $author ) : ?>
	<p>Gå till dina <span class="big-link"><a href="/pages/activity/posts/?status=archived">⭕ Arkiverade annonser</a></span> för att aktivera.<p>
<?php endif;?>

<?php if ( $current != $author ) : ?>
	<p class="small">💡 Givaren har inte meddelat om annonsen fortfarande är aktuell, men du kan pinga i kommentarsfältet och fråga.<p>
<?php endif;?>

<?php endif;?>


<!-- BOOKED POST (LOCKER) -->
<?php if (in_category( 'booked_locker' )) : ?>

	<?php if ( $current == $fetcher ) : ?>
		<p>⏳ Du väntar på att <span class="link">👤<?php echo $authorlink;?></span> ska lämna i <span class="label"><i class="fas fa-walking"></i><?php echo $location; ?></span> <?php include LOOPIS_THEME_DIR . '/templates/post/timer-locker.php';?>...</p>
	<?php endif;?>
	
	<?php if ( $current == $author ) : ?>
		<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-deliver.php'; ?>
		<p>🔔 Dags att lämna i <span class="label"><i class="fas fa-walking"></i><?php echo $location; ?></span></p>
		<p><?php include LOOPIS_THEME_DIR . '/templates/post/timer-locker.php';?></p>
		<p>🔓 Kod till skåpet: <span class="code"><?php echo $locker_code;?></span></p>
		<?php if(isset($_POST['locker'])){ action_locker($post_id); } ?>
		<form method="post" class="arb" action=""><button name="locker" type="submit" class="green" onclick="return confirm('Har du lämnat i skåpet?')">Lämnat!</button></form>
		<p class="info">Har du lämnat i skåpet? Tryck på knappen för att meddela mottagaren.</p>
	<?php endif;?>	

	<?php if ( $current == $fetcher ) : ?>
		<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-regret.php'; ?>	
		<?php if(isset($_POST['regret'])){ action_regret($post_id); } ?>
		<form method="post" class="arb" action=""><button name="regret" type="submit" class="red small" onclick="return confirm('Vill du inte längre hämta?')">Jag har ångrat mig</button></form>
		<p class="info">Du kan ångra dig fram tills att givaren lämnat i skåpet.</p>
	<?php endif;?>
	
<?php endif;?>


<!-- BOOKED POST (CUSTOM) -->
<?php if (in_category( 'booked_custom' )) : ?>
<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-fetch.php'; ?>
<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-regret.php'; ?>

	<?php if ( $current == $fetcher ) : ?>

		<p>🔔 Du ska skicka ett sms till <span class="link">👤 <?php echo $authorlink ?></span> för att komma överens om hämtning på <span class="label"><i class="fas fa-walking"></i><a href="http://maps.apple.com/maps?q=<?php echo $location; ?>"><?php echo $location; ?></a></span></p>
		<form class="arb"><button type="button" onclick="location.href='sms:<?php echo get_the_author_meta('wpum_phone'); ?>'">&#128241;<?php echo get_the_author_meta('wpum_phone'); ?></button></form>
		<p class="info">Tryck på knappen för att skicka ett sms till <?php echo $authorname; ?>.</p>

		<?php if(isset($_POST['fetched'])) { action_fetched ($post_id); } ?>
		<form method="post" class="arb" action=""><button name="fetched" type="submit" class="blue">Hämtat!</button></form>
		<p class="info">Har du hämtat? Tryck på knappen.</p>
	<?php endif;?>	

	<?php if ( $current == $author ) : ?>
		<p>⏳ Du väntar på att  <span class="link">👤 <a href="<?php echo $fetcherlink; ?>"><?php echo $fetchername; ?></a></span> ska skicka ett sms och hämta...</span></p>
		<form class="arb"><button type="button" onclick="location.href='sms:<?php echo get_user_meta($fetcher, 'wpum_phone', true); ?>'">&#128241;<?php echo get_user_meta($fetcher, 'wpum_phone', true); ?></button></form>
		<p class="info">Tryck på knappen om du vill skicka ett sms till <?php echo $fetchername ?></p>
	<?php endif;?>	

	<?php if ( $current == $fetcher ) : ?>			
		<?php if(isset($_POST['regret'])){ action_regret($post_id); } ?>
		<form method="post" class="arb" action=""><button name="regret" type="submit" class="red small" onclick="return confirm('Vill du inte längre hämta?')">Jag har ångrat mig</button></form>
		<p class="info">Tryck på knappen så blir annonsen tillgänglig för andra.</p>
	<?php endif;?>
	
<?php endif;?>


<!-- BOOKED POST (LOCKER/CUSTOM) -->
<?php if (in_category( array( 'booked_locker', 'booked_custom' ) )) : ?>
	<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-queue.php'; ?>

	<?php if ( $current != $author && $current != $fetcher && !in_array($current, $queue)) : ?>
		<p>♻ Denna pryl är på väg till ett nytt hem.<br>💔 Du kan köa om den som paxat ångrar sig. <span class="label">👫 <?php echo $queue_total; ?> står i kö</span> </p>
		<?php if(isset($_POST['queue'])) { action_queue($post_id); } ?>
		<form method="post" class="arb" action=""><button name="queue" type="submit" class="orange" onclick="return confirm('Vill du köa?')">Köa...</button></form>
		<p class="info">Tryck på knappen för att köa.</p>
	<?php endif;?>
	
	<?php if ( $current != $author && $current != $fetcher && in_array($current, $queue)) : ?>
		<p>♻ Denna pryl är på väg till ett nytt hem.<br>⌛ Du står i kö om den som paxat ångrar sig. <span class="label">👫 plats <?php echo $queue_position; ?> av <?php echo $queue_total; ?></span></p>
		<?php if(isset($_POST['unqueue'])) { action_unqueue($post_id); } ?>
		<form method="post" class="arb" action=""><button name="unqueue" type="submit" class="orange small" onclick="return confirm('Vill du lämna kön?')">Lämna kön</button></form>
		<p class="info">Tryck på knappen för att lämna kön.</p>
	<?php endif;?>
			
<?php endif;?>


<!-- LOCKER -->
<?php if (in_category( 'locker' )) : ?>

	<?php if ( $current != $author && $current != $fetcher ) : ?>
		<p>♻ Denna pryl är på väg till ett nytt hem. Du kan leta efter något liknande på <?php $tags = get_the_tags(); if ($tags) { foreach ($tags as $tag) { echo '<span class="link" style="margin-right:5px;"><a href="' . get_tag_link($tag->term_id) . '"><i class="fas fa-hashtag"></i>' . $tag->name . '</a></span>'; }} ?></p>
	<?php endif;?>
	
	<?php if ( $current == $author ) : ?>
		<p>♻ Nu behöver du inte göra något mer. Tack för att du loopar!</p>
	<?php endif;?>

	<?php if ( $current == $fetcher ) : ?>
		<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-fetch.php'; ?>
		<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-regret.php'; ?>

		<p>🔔 Dags att hämta i <span class="label"><i class="fas fa-walking"></i><?php echo $location; ?></span></p>
		<p><?php include LOOPIS_THEME_DIR . '/templates/post/timer-fetch.php';?></p>
		<p>🔓 Kod till skåpet: <span class="code"><?php echo $locker_code;?></span></p>
		<?php if(isset($_POST['fetched'])) { action_fetched ($post_id); } ?>
		<form method="post" class="arb" action=""><button name="fetched" type="submit" class="blue" onclick="return confirm('Har du hämtat?')">Hämtat!</button></form>
		<p class="info">Har du hämtat i skåpet? Tryck på knappen.</p>

		<?php if ( !empty($queue) ) : ?>
			<p>👫 Eftersom det finns en kö kan du fortfarande ångra dig.</p>
			<?php if(isset($_POST['regret'])){ action_regret($post_id); } ?>
			<form method="post" class="arb" action=""><button name="regret" type="submit" class="red small" onclick="return confirm('Vill du inte längre hämta?')">Jag har ångrat mig</button></form>
			<p class="info">Lämna kvar saken i skåpet och tryck på knappen.</p>
		<?php endif;?>
	<?php endif;?>
	
	<?php if (current_user_can('administrator') || current_user_can('manager')) : ?>
		<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-fetch.php'; ?>
	<?php endif;?>

<?php endif;?>
	

<!-- FETCHED POST -->
<?php if (in_category( 'fetched' )) : ?>

	<?php if ( $current != $author && $current != $fetcher ) : ?>
		<p>♻ Denna pryl har fått ett nytt hem. Du kan leta efter något liknande på <?php $tags = get_the_tags(); if ($tags) { foreach ($tags as $tag) { echo '<span class="link" style="margin-right:5px;"><a href="' . get_tag_link($tag->term_id) . '"><i class="fas fa-hashtag"></i>' . $tag->name . '</a></span>'; }} ?></p>
	<?php endif;?>

	<?php if ( $current == $author ) : ?>
		<p>♻ Din pryl har fått ett nytt hem. Tack för att du loopar!</p>
	<?php endif;?>
	
	<?php if ( $current == $fetcher ) : ?>
		
	<?php $forward_post = get_post_meta($post_id, 'forward_post', true) ?>
		<?php if ( $forward_post ) { ?> 
			<p>♻ Du har skickat vidare! <span class="link"><a href="<?php echo get_permalink($forward_post); ?>">→ Ny annons</a></span></p>
			
			<?php } else { ?>
			
			<p>♻ Du har hämtat. Tack för att du loopar!</p>
			<?php include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-forward.php'; ?>
		<?php if(isset($_POST['forward'])) { action_forward ($post_id); } ?>
		<form method="post" class="arb" action=""><button name="forward" type="submit" class="purple" onclick="return confirm('Vill du skicka vidare? Du kan redigera din kopia av annonsen.')">Skicka vidare</button></form>
		<p class="info">Tryck på knappen så lägger vi upp samma annons igen.</p>
			<?php } ?>
			
	<?php endif;?>

<?php endif;?>


<!-- REMOVED POST -->
<?php if (in_category( 'removed' )) : ?>

	<?php if ( $current == $author ) : ?>
		<p>⚠ Denna annons har tagits bort.</p>
		<?php if(isset($_POST['unremove'])) { action_unremove($post_id); } ?>
		<form method="post" class="arb" action=""><button name="unremove" type="submit" class="green small" onclick="return confirm('Är annonsen aktuell igen?')">Publicera igen</button></form>
		<p class="info">Är annonsen aktuell igen? Tryck på knappen för att publicera den igen.</p>
	<?php endif;?>
	
	<?php if ( $current != $author ) : ?>
		<p>⚠ Denna annons har tagits bort. Du kan leta efter något liknande på <?php $tags = get_the_tags(); if ($tags) { foreach ($tags as $tag) { echo '<span class="link" style="margin-right:5px;"><a href="' . get_tag_link($tag->term_id) . '"><i class="fas fa-hashtag"></i>' . $tag->name . '</a></span>'; }} ?></p>
	<?php endif;?>
	
<?php endif;?>