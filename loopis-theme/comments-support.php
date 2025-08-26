<!-- VARIABLER -->
<?php
$post_id = get_the_ID();
$author = get_the_author_meta('ID');
$authorname = get_the_author_meta('display_name', $author);
$authorlink = get_the_author_posts_link();
$current = get_current_user_id();
$comment_count = get_comment_count($post_id)['approved'];

// Get status of the post
$taxonomies = get_the_terms( get_the_ID(), 'support-status' );
if ( $taxonomies && ! is_wp_error( $taxonomies ) ) {
foreach ( $taxonomies as $taxonomy ) {
$status = get_term( $taxonomy->term_id, 'support-status' );
$status_name = $status->name;
$status_slug = $status->slug; } }
?>

<div class="auto-respond">
		
<!-- KOMMENTARER -->
<div class="columns"><div class="column1">
<h3><i class="far fa-comment"></i> <?php echo $comment_count ?></h3>
</div>
<div class="column2 bottom"><?php if ( $comment_count > 1 ) { echo '↓ Senaste överst'; } ?>
</div></div>
<hr>
	
		<div id="commentlist-container" class="comment-tab">			
			<ol class="commentlist">
		<?php if ( $comment_count == 0 ) {
			echo '<div class="comment-body"><p>⌛ Väntar på svar från admin...</p></div>';
			} else {
			wp_list_comments( 'avatar_size=96&type=comment' ); } ?>	
			</ol>	
		</div>	


<!-- SKRIV KOMMENTAR -->
<h6>Skriv kommentar</h6>
<hr style="margin:0">
<?php if ( $current != $author ) : ?>
<span class="small"><a href="#" id="tag-author">Pinga författaren</a> (<?php echo $authorname; ?>)</span>
<?php endif;?>
<p class="info">Pinga användare för att ge dem en mail-notifikation.</p>

<?php comment_form(array(
    'title_reply' => '',
    'submit_button' => '<button name="submit" type="submit" id="submit" class="grey small">Skicka</button>')); ?>



<h6>Status</h6>
<hr>

<p>Ärendets status är <span class="label"><?php echo $status_name; ?></span></p>

<!-- Arkivera -->
<?php if ($status_slug === 'active' && ($current == $author || current_user_can('administrator') || $current == 2)) : ?>
<?php if(isset($_POST['inactive'])) { 
	update_field('status', null, $post_id);
	update_field('status', 146, $post_id); 
	add_comment ('<p class="participate">✅ Markerar frågan som besvarad.</p>', $post_id );
	echo "<meta http-equiv='refresh' content='0'>"; } ?>
		<form method="post" class="arb" action=""><button name="inactive" type="submit" class="green small" onclick="return confirm('Är frågan besvarad?')">Frågan är besvarad</button></form>
		<p class="info">Tryck på knappen så arkiveras ärendet.</p>
<?php endif;?>

</div><!--auto-respond-->


<!-- PINGA -->

<!-- Script: Pinga kommentar -->
<script>
    // Wait for the DOM to load
    document.addEventListener('DOMContentLoaded', function() {
        // Get all the existing comments on the page
        var comments = document.querySelectorAll('.comment');

        // Iterate through each comment
        comments.forEach(function(comment) {
            // Get the <cite class="fn">Username</cite> element
            var usernameElement = comment.querySelector('.comment-author .fn');

            // Get the username
            var username = usernameElement.textContent.trim();

            // Create a "Tagga" link element
            var taggaLink = document.createElement('a');
            taggaLink.className = 'comment-tagga';
            taggaLink.href = '#comment';
            taggaLink.textContent = '  – pinga';

            // Insert the "Tagga" link after the username element
            usernameElement.parentNode.insertBefore(taggaLink, usernameElement.nextSibling);

            // Add a click event listener
            taggaLink.addEventListener('click', function(event) {
                event.preventDefault();

                // Get the comment field
                var commentField = document.getElementById('comment');

                // Get the current value of the comment field
                var currentValue = commentField.value.trim();

                // Add "@username " to the comment field if it's not already present
                if (!currentValue.includes('@' + username)) {
                    var newValue = currentValue + ' @' + username + ' ';
                    commentField.value = newValue;
                }
            });
        });
    });
</script>
<!-- Script: Pinga givaren -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var insertUsernameLink = document.getElementById('tag-author');
        var commentTextArea = document.getElementById('comment');

        insertUsernameLink.addEventListener('click', function(event) {
            event.preventDefault();
            var authorUsername = '@<?php echo $authorname; ?>';
            commentTextArea.value += authorUsername;
        });
    });
</script>

<!-- Script: Ersätt @ i kommentarer -->
<script>
	var paragraphs = document.querySelectorAll('.commentlist .comment-body p');
	paragraphs.forEach(function (paragraph) {
	var text = paragraph.innerHTML;
	var modifiedText = text.replace(/@/g, '🔔');
	paragraph.innerHTML = modifiedText;
	});
</script>