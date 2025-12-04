<?php
/**
 * Template for comments.
 */

// Get neccesary variables again (since comments.php is loaded separately)
$post_id = get_the_ID();
$current = get_current_user_id();
$author = get_the_author_meta('ID'); 
$authorname = get_the_author_meta('display_name', $author);

// Get comment count
$comment_count = get_comment_count($post_id)['approved'];

// Get post type
$post_type = get_post_type($post_id);

// Set message for no comments based on post type
switch ($post_type) {
    case 'post':
        $message = "💢 Ingen har ännu visat intresse.";
        break;
    case 'forum':
        $message = "👋 Skriv den första kommentaren!";
        break;
    case 'support':
        $message = "⌛ Väntar på svar från admin...";
        break;
    default:
        $message = "💢 Inga kommentarer ännu.";
        break;
}

?>

<!-- SHOW COMMENTS -->
<div class="columns"><div class="column1"><h3><i class="far fa-comment"></i> <?php echo $comment_count ?></h3></div>
<div class="column2 bottom"><?php if ( $comment_count > 1 ) { echo 'Senaste överst ↓'; } ?></div></div>
<hr>
    <div id="commentlist-container" class="comment-tab">			
        <ol class="commentlist">
        <?php if ( $comment_count == 0 ) {
            echo '<div class="comment-body"><p>' . $message . '</p></div>';
            } else {
            wp_list_comments( 'avatar_size=96&type=comment' ); } ?>	
        </ol>	
    </div>	


<!-- ADD COMMENT -->
<h6><i class="far fa-comment"></i></i> Skriv kommentar</h6>
<hr style="margin:0">
<p class="small"><?php if ( $current != $author ) { ?><span class="small-link"><a href="#" id="tag-author">🔔 Pinga skaparen</a> (<?php echo $authorname; ?>)</span><br> <?php } ?>💡 Pinga användare för att de ska se din kommentar.</p>

<?php comment_form(array(
    'title_reply' => '',
    'submit_button' => '<button name="submit" type="submit" id="submit" class="grey small">Skicka</button>')); ?>


<!-- Mention sctripts -->
<script>
    // Pass PHP variable $authorname to JavaScript
    window.authorUsername = '@<?php echo $authorname; ?>';
</script>
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/mentions-add.js"></script>
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/mentions-show.js"></script>