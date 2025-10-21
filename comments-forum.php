<?php
// Get user and post
$post_id = get_the_ID();
$comment_count = get_comment_count($post_id)['approved'];
$author = get_the_author_ID();
$authorname = get_the_author_meta('display_name', $author);
$authorlink = get_the_author_posts_link();
$current = get_current_user_id();
?>

<div class="auto-respond">

<!-- ACCESS YES -->
<?php if ( current_user_can('member') || current_user_can('administrator') ) { ?>	
	
<!-- KOMMENTARER -->
<div class="columns"><div class="column1">
<h3><i class="far fa-comment"></i> <?php echo $comment_count ?></h3></div>
<div class="column2 bottom"><?php if ( $comment_count > 1 ) { echo 'Senaste överst ↓'; } ?></div></div>
<hr>
	<div id="commentlist-container" class="comment-tab">			
		<ol class="commentlist">
		<?php if ( $comment_count == 0 ) {
			echo '<div class="comment-body"><p>💢 Skriv den första kommentaren!</p></div>';
			} else {
			wp_list_comments( 'avatar_size=96&type=comment' ); } ?>	
		</ol>	
	</div>	


<!-- SKRIV KOMMENTAR -->
<h6>Skriv kommentar</h6>
<hr style="margin:0">
<p class="small"><?php if ( $current != $author ) { ?><span class="small-link"><a href="#" id="tag-author">🔔 Pinga <?php echo $authorname; ?></a></span><br> <?php } ?>💡 Pinga användare för att de ska se din kommentar.</p>

<?php comment_form(array(
    'title_reply' => '',
    'submit_button' => '<button name="submit" type="submit" id="submit" class="grey small">Skicka</button>')); ?>



<!-- Scripts for pings -->
<script>
    // Pass PHP variable $authorname to JavaScript
    window.authorUsername = '@<?php echo $authorname; ?>';
</script>
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/comments.js"></script>


<!-- NO ACCESS MESSAGE -->	
<?php } else { 
	echo do_shortcode('[code_snippet id=124 php]'); } ?>

</div><!--auto-respond-->