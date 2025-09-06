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


<!-- Pinga kommentar -->
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

            // Create a "Pinga" link element
            var pingLink = document.createElement('a');
            pingLink.className = 'comment-ping';
            pingLink.href = '#comment';
            pingLink.innerHTML = ' – <span class="small-link">🔔 Pinga</span>';

            // Insert the "ping" link after the username element
            usernameElement.parentNode.insertBefore(pingLink, usernameElement.nextSibling);

            // Add a click event listener
            pingLink.addEventListener('click', function(event) {
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

<!-- Ping author -->
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

<!-- Replace @ in comments -->
<script>
	var paragraphs = document.querySelectorAll('.commentlist .comment-body p');
	paragraphs.forEach(function (paragraph) {
	var text = paragraph.innerHTML;
	var modifiedText = text.replace(/@/g, '🔔');
	paragraph.innerHTML = modifiedText;
	});
</script>


<!-- NO ACCESS MESSAGE -->	
<?php } else { 
	echo do_shortcode('[code_snippet id=124 php]'); } ?>

</div><!--auto-respond-->