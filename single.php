<?php
/**
 * Template for single post.
 */

get_header(); ?>

<!-- Extra php functions -->
<?php 
if (current_user_can('member') || current_user_can('administrator')) {
    include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-remove.php';
} 
if (current_user_can('administrator')) {
    include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-admin.php';
} 
?>

<!-- SET VARIABLES -->
<?php
wp_reset_postdata(); // added here when removed from functions.php
$current = get_current_user_id();
$author = get_the_author_meta('ID'); 
$post_id = get_the_ID();
$post_date = get_the_time('Y-m-d H:i');
$extend_date = get_post_meta($post_id, 'extend_date', true);
$previous_post_id = get_post_meta($post_id, 'previous_post', true);
$forward_post_id = get_post_meta($post_id, 'forward_post', true);
$location = get_post_meta(get_the_ID(), 'location', true) ?: 'Plats saknas';
$fetcher = get_post_meta($post_id, 'fetcher', true);
if ($fetcher && ($fetcher_data = get_userdata($fetcher))) {
    $fetchername = $fetcher_data->display_name; 
    $fetcherlink = get_author_posts_url($fetcher); 
} else {
    $fetchername = 'Hämtare saknas';
    $fetcherlink = 'Hämtare saknas';
} 
$thumbnail_id = get_post_thumbnail_id($post_id);
$image_2_id = get_post_meta($post_id, 'image_2', true);
$image_3_id = get_post_meta($post_id, 'image_3', true);
?>

        <!-- THE POST -->
        <div class="post-wrapper">   

            <div class="post-images">
                <div class="post-image">
                    <?php if ($thumbnail_id){ echo wp_get_attachment_image($thumbnail_id, 'large'); } ?>
                </div>
                <div class="post-image-1">
                    <?php if ($image_2_id){ echo wp_get_attachment_image($thumbnail_id, 'large'); } ?>
                </div>
                <div class="post-image-2">
                    <?php if ($image_2_id) { echo wp_get_attachment_image($image_2_id, 'large'); } ?>
                </div>
                <div class="post-image-3">
                    <?php if ($image_3_id) { echo wp_get_attachment_image($image_3_id, 'large'); } ?>
                </div>
            </div><!--post-images-->

            <div class="post-padding">
                <div class="post-meta">
                    <span><?php the_category(' '); if (in_category('new')) { echo raffle_time(); } ?></span>
                    <span><i class="fas fa-walking"></i><?php if ($location == 'Skåpet') { ?><a class="no-link-styling" href="https://maps.app.goo.gl/h63CFSWVyk52NkbD7"><?php echo $location; ?> i Bagarmossen</a><?php } else { ?><a class="no-link-styling" href="https://maps.google.com/maps?q=<?php echo urlencode($location); ?>"><?php echo $location ?></a><?php } ?></span>
                </div><!--post-meta-->    
            
                <div class="post-title"><h1 class="wrap"><?php the_title(); ?></h1></div>
            <div class="post-meta">
                <?php $tags = get_the_tags(); if ($tags) {  foreach ($tags as $tag) { echo '<span class="big-link"><a href="' . get_tag_link($tag->term_id) . '"><i class="fas fa-hashtag"></i>' . $tag->name . '</a></span>'; }} ?>
                </div><!--post-meta-->    

                <div class="post-content">
                    <?php the_content();?>
                    <?php if ($author == 66 || $author == 237) : ?>
                        <a style="float:left; font-size:14px; padding-top:2px; margin-right:12px" href="<?php esc_url(home_url('/faq/max-murpos'));?>">💫 Räddad från soprum</a>
                    <?php endif; ?>

                    <!-- POST OPTIONS -->
                    <!-- Edit & remove-->
                    <?php if (($current == $author && !in_category(array('removed', 'fetched', 'locker'))) || current_user_can('administrator') || current_user_can('manager')) : ?>
                        <?php include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-remove.php'; ?>
                        <?php if (isset($_POST['remove'])) { action_remove(get_the_ID()); } ?>
                        <a href="#" class="option" onclick="if (confirm('Ta bort annonsen?')) { document.getElementById('remove-form').submit(); } return false;"><i class="fas fa-times"></i>Ta bort</a>
                        <form id="remove-form" method="post" action="" style="display:inline; float:left;"><input type="hidden" name="remove" value="1"></form>
                        <a class="option" href="<?php echo esc_url(home_url('/submit/?option=single&edit_post_id=' . (int) $post_id)); ?>"><i class="fas fa-pen"></i>Redigera</a>
                    <?php endif;?>
                    
                    <!-- Copy link -->
                    <a href="#" id="copy_url" class="option">🔗 Kopiera länk</a>

                </div><!--post-content-->				
            </div><!--post-padding-->				
        </div><!--post-wrapper-->							

<div class="page-padding" style="padding-top: 5px;"> <!-- Logg close to post -->

        <!-- User log -->
        <?php include LOOPIS_THEME_DIR . '/templates/post/post-log.php'; ?>

            <!-- INTERACTION -->
                <div class="columns">
                    <div class="column1"><h3>Dina alternativ</h3></div>
                    <div class="column2 bottom"><a href="<?php echo get_permalink( get_page_by_path('hur-far-jag-saker') ); ?>">📌 Hur får jag saker?</a></div>
                </div>
                <hr>

                <?php 
                // Access control
                if (current_user_can('member') || current_user_can('administrator')) {

                    // Post actions
                    include LOOPIS_THEME_DIR . '/templates/post/post-actions.php';

                    // Comments
                    if (comments_open()) { comments_template('/comments.php', true); }

                    } else {
                    // Visitor message & FAQ
                    include LOOPIS_THEME_DIR . '/templates/access/message.php';
                    include LOOPIS_THEME_DIR . '/templates/faq/questions-visitor.php';
                } 
                ?>

            <!-- Admin log -->
            <?php if (current_user_can('administrator') || current_user_can('manager')) { 
                include LOOPIS_THEME_DIR . '/templates/post/post-log-admin.php'; 
            } ?>

</div><!--page-padding-->    

<!-- Extra scripts -->
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/post-image-switch.js"></script>

<?php get_footer(); ?>