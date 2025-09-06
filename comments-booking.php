<!-- VARIABLER -->
<?php
$current = get_current_user_id();
$post_id = get_the_ID();
$comment_count = get_comment_count($post_id)['approved'];

// Get names and phones
$author = get_the_author_meta('ID');
$owner = get_field('owner');
$authorname = get_the_author_meta('display_name');
$authorfirstname = get_the_author_meta('first_name');
$authorphone = get_the_author_meta('wpum_phone');
$ownername = get_the_author_meta('display_name', $owner);
$ownerfirstname = get_the_author_meta('first_name', $owner);
$ownerphone = get_the_author_meta('wpum_phone', $owner);

// Get status of the post
$status_name = '💢 Okänd status';
$status_slug = '';
$terms = get_the_terms(get_the_ID(), 'booking-status');
if ($terms && !is_wp_error($terms)) {
    $status = $terms[0]; // Use the first term if multiple
    $status_name = $status->name;
    $status_slug = $status->slug;
}

// Get booking data
$terms = get_field('terms');
$date_start = get_field('date_start');
$day_start = date('l Y-m-d', strtotime($date_start));
$date_end = get_field('date_end');
$day_end = date('l Y-m-d', strtotime($date_end));
?>

<div class="auto-respond">

<!--STATUS-->
<h3>Status</h3>
<hr>
<p>Bokningen är <span class="label"><?php echo $status_name; ?></span></p>

<!--Bokning skickad-->	
<?php if ($status_slug === 'sent') : ?>

	<?php if ( $current == $author ) : ?>
		<p><span class="label">👤<?php echo $ownername ?></span> kommer att kontakta dig via sms på <span class="label">&#128241;<?php echo $authorphone; ?></span> <span class="info">(Ditt mobilnummer anges på <a href="../../profile-settings">Inställningar</a>)</span></p>
	<?php endif;?>	
	
	<?php if ( $current == $owner ) : ?>
		<?php if(isset($_POST['confirm'])){ 
			update_field('status', null, $post_id);
			update_field('status', 149, $post_id);
			update_field('date_confirmed', date('Y-m-d H:i:s'));
			add_comment('<p class="green_light">✅ Bokningen är bekräftad <span>' . $authorname . '</span> <br><span>🔔' . $ownername . '</span> ska nu kontakta dig via sms.</p>', $post_id);
			echo "<meta http-equiv='refresh' content='0'>"; } ?>
		<form method="post" class="arb" action=""><button name="confirm" type="submit" class="green">Bekräfta bokning</button></form>
		<p class="info">Tryck på knappen för att bekräfta bokningen.</p>	
	<?php endif;?>

		<?php if(isset($_POST['cancel'])){ 
			update_field('status', null, $post_id);
			update_field('status', 150, $post_id);
			update_field('date_canceled', date('Y-m-d H:i:s'));
			add_comment ('<p class="red_light">🚫 Bokningen är avbruten.</p>', $post_id );
			echo "<meta http-equiv='refresh' content='0'>"; } ?>
		<form method="post" class="arb" action=""><button name="cancel" type="submit" class="red small">Avbryt bokning</button></form>
		<p class="info">Tryck på knappen för att avbryta bokningen.</p>

<?php endif;?>

<!--Bokning bekräftad-->	
<?php if ($status_slug === 'confirmed') : ?>

	<?php if ( $current == $author ) : ?>
		<p>⌛ Du ska hämta <span class="label"><i class="far fa-calendar"></i> <?php echo get_field('date_start') ?></span> – <span class="label"><i class="fas fa-walking"></i> <?php echo get_field('location', get_field('object')) ?></span></p>
		<p><span class="label">👤<?php echo $ownername ?></span> kontaktar dig via sms på <span class="label">&#128241;<?php echo $authorphone; ?></span></p>
	<?php endif;?>	
	
	<?php if ( $current == $owner ) : ?>
		<p>⌛ <?php echo $authorfirstname; ?> ska hämta <span class="label"><i class="far fa-calendar"></i> <?php echo get_field('date_start') ?></span> – <span class="label"><i class="fas fa-walking"></i> <?php echo get_field('location', get_field('object')) ?></span></p>

		<?php if(isset($_POST['borrowed'])){ 
			update_field('status', null, $post_id);
			update_field('status', 151, $post_id);
			update_field('date_borrowed', date('Y-m-d H:i:s'));
			add_comment ('<p class="orange_light">💫 ' . $authorname . ' har hämtat!</p>', $post_id );
			echo "<meta http-equiv='refresh' content='0'>"; } ?>
		<form method="post" class="arb" action=""><button name="borrowed" type="submit" class="orange">Hämtad</button></form>
		<p class="info">Tryck på knappen när <?php echo $authorname; ?> har hämtat.</p>	
	<?php endif;?>	

		<?php if(isset($_POST['cancel'])){ 
			update_field('status', null, $post_id);
			update_field('status', 150, $post_id);
			update_field('date_canceled', date('Y-m-d H:i:s'));
			add_comment ('<p class="red_light">⛔ Bokningen är avbruten.</p>', $post_id );
			echo "<meta http-equiv='refresh' content='0'>"; } ?>
		<form method="post" class="arb" action=""><button name="cancel" type="submit" class="red small">Avbryt bokning</button></form>
		<p class="info">Tryck på knappen för att avbryta bokningen.</p>

<?php endif;?>	


<!--Bokning utlånad-->	
<?php if ($status_slug === 'borrowed') : ?>

	<?php if ( $current == $author ) : ?>
		<p>Du ska lämna tillbaka <span class="label"><i class="far fa-calendar"></i> <?php echo $day_end ?></span> – <span class="label"><i class="fas fa-walking"></i> <?php echo get_field('return_location', get_field('object')) ?></span></p>
	<?php endif;?>	
	
	<?php if ( $current == $owner ) : ?>
		<p><?php echo $authorfirstname; ?> ska lämna tillbaka <span class="label"><i class="far fa-calendar"></i> <?php echo $day_end ?></span> – <span class="label"><i class="fas fa-walking"></i> <?php echo get_field('return_location', get_field('object')) ?></span></p>
		<?php if(isset($_POST['returned'])){ 
			update_field('status', null, $post_id);
			update_field('status', 123, $post_id);
			update_field('date_returned', date('Y-m-d H:i:s'));
			add_comment ('<p class="blue_light">☑ ' . $authorname . ' har lämnat tillbaka!</p>', $post_id );
			echo "<meta http-equiv='refresh' content='0'>"; } ?>
		<form method="post" class="arb" action=""><button name="returned" type="submit" class="blue">Återlämnad</button></form>
		<p class="info">Tryck på knappen när <?php echo $authorname; ?> har lämnat tillbaka.</p>
	<?php endif;?>	

<?php endif;?>

<!--Bokning återlämnad-->	
<?php if ($status_slug === 'returned') : ?>
	<p>Lånet är avslutat. Tack för att du loopar! 💚</p>
<?php endif;?>	

<!--Kontaktvägar-->
<?php if ( $current == $author) : ?>
	<form class="arb"><button type="button" onclick="location.href='sms:<?php echo $ownerphone; ?>'">&#128241;<?php echo $ownerphone; ?></button></form>
	<p class="info">Tryck på knappen för att skriva sms till <?php echo $ownername ?>.</p>
<?php endif;?>

<?php if ( $current == $owner ) : ?>
	<form class="arb"><button type="button" class="small" onclick="location.href='sms:<?php echo $authorphone; ?>'">&#128241;<?php echo $authorphone; ?></button></form>
	<p class="info">Tryck på knappen för att skriva sms till <?php echo $authorname ?>.</p>
<?php endif;?>

<!-- KOMMENTARER -->
<div class="columns"><div class="column1">
<h3><i class="far fa-comment"></i> <?php echo $comment_count ?></h3></div>
<div class="column2 bottom"><?php if ( $comment_count > 1 ) { echo '↓ Senaste överst'; } ?></div></div>
<hr>
	
		<div id="commentlist-container" class="comment-tab">			
			<ol class="commentlist">
		<?php if ( $comment_count == 0 ) {
			echo '<div class="comment-body"><p>⌛ Väntar på svar från ägaren...</p></div>';
			} else {
			wp_list_comments( 'avatar_size=96&type=comment' ); } ?>	
			</ol>	
		</div>	

<h6>Skriv kommentar</h6>
<hr>
<p class="small">Tagga användare med @Fornamn-Efternamn för att ge dem en notifikation.</p>

<!-- SKRIV KOMMENTAR -->
<?php comment_form(array(
    'title_reply' => '',
    'submit_button' => '<button name="submit" type="submit" id="submit" class="grey small">Skicka</button>')); ?>

</div><!--auto-respond-->

<!-- Ersätt @ i kommentarer -->
<script>
	var paragraphs = document.querySelectorAll('.commentlist .comment-body p');
	paragraphs.forEach(function (paragraph) {
	var text = paragraph.innerHTML;
	var modifiedText = text.replace(/@/g, '🔔');
	paragraph.innerHTML = modifiedText;
	});
</script>