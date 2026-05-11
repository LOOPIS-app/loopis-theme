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
$fetcher = get_post_meta($post_id, 'fetcher', true);
if ($fetcher) { 
    $fetcher_data = get_userdata($fetcher);
    if($fetcher_data){
        $fetchername = $fetcher_data->display_name; 
        $fetcherlink = get_author_posts_url($fetcher); 
    }
} 

wp_enqueue_script(
    'loopis_image_loader_js', // handle
    LOOPIS_THEME_URI . '/assets/js/image-loader.js', // URL to JS
    ['jquery'],
    filemtime(LOOPIS_THEME_DIR . '/assets/js/image-loader.js'),
    true // load in footer
);

wp_localize_script('loopis_image_loader_js', 'loopis_theme_ajax', [
    'ajax_url'        => admin_url('admin-ajax.php'),
    'nonce'           => wp_create_nonce('loopis_theme_nonce'),
    'post_id'         => $post_id,
]);


?>

<!-- Custom location?  -->
<?php 
$location = get_post_meta($post_id, 'location', true);
if ($location == 'Annan adress') { 
    $location = get_post_meta($post_id, 'custom_location', true); 
    update_post_meta($post_id, 'location', $location); 
} 
?>
 
<!-- Extra image? remove when new postform active  -->
<?php $thumbnail_id = get_post_thumbnail_id($post_id);
$image_2_id = get_post_meta($post_id, 'image_2', true);
$image_3_id = get_post_meta($post_id, 'image_3', true);
if (empty($image_2_id)) {
    $extra_image_url = get_post_meta($post_id, 'extra_image', true);
    if ($extra_image_url) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $file_name = basename($extra_image_url);
        $attachment_id = media_sideload_image($extra_image_url, $post_id, $file_name, 'id');
        update_post_meta($post_id,'image_2', $attachment_id);
        delete_post_meta($post_id, 'extra_image');
        $cache_buster = time();
        echo '<meta http-equiv="refresh" content="0;url=' . esc_url(add_query_arg('cache_buster', $cache_buster)) . '">';
        exit; 
    } 
} 

$pending = get_post_meta($post_id, '_pending_images',true);
?>

<div class="content">

        <!-- THE POST -->
        <div class="post-wrapper">    
            <div class="post-images">
                <div id="loading-indicator" style="display: none; height : 200px; justify-content: center; align-items: center;">
                    <img src="<?php echo LOOPIS_THEME_URI .'/assets/gif/LOOPIS_icon_snake.gif'; ?>" alt="Loading..." style="height : 100px;"/>
                </div>
                <div class="post-image">
                    <?php 
                        if ($thumbnail_id){
                            echo wp_get_attachment_image($thumbnail_id, 'large'); 
                        }
                    ?>
                </div>
                <div class="extra-image">
                <?php if ($image_2_id) {
                    echo wp_get_attachment_image($image_2_id, 'large');
                } ?>
                </div>
                <div class="extra-image-2">
                <?php if ($image_3_id) {
                    echo wp_get_attachment_image($image_3_id, 'large');
                } ?>
                </div>
            </div><!--post-images-->

            <div class="post-padding">
                <h1 class="wrap"><?php the_title(); ?></h1>
                <div class="post-meta">
                    <span><?php the_category(' '); if (in_category('new')) { echo raffle_time(); } ?></span>
                    <span><i class="fas fa-walking"></i><?php if ($location == 'Skåpet') { ?><a href="https://maps.app.goo.gl/bp1v8fSAf7MJqxu88"><?php echo $location; ?></a><?php } else { ?><a href="https://maps.google.com/maps?q=<?php echo urlencode($location); ?>"><?php echo $location ?></a><?php } ?></span>
                    <?php 
                    $tags = get_the_tags(); 
                    if ($tags) { 
                        foreach ($tags as $tag) { 
                            echo '<span><a href="' . get_tag_link($tag->term_id) . '"><i class="fas fa-hashtag"></i>' . $tag->name . '</a></span>'; 
                        }
                    } 
                    ?>
                </div><!--post-meta-->

                <div class="post-content">
                    <?php the_content();?>
                    <?php if ($author == 66 || $author == 237) : ?>
                        <a style="float:left; font-size:14px; padding-top:2px; margin-right:12px" href="<?php esc_url(home_url('/faq/max-murpos'));?>">💫 Räddad från soprum</a>
                    <?php endif; ?>

                    <!-- POST OPTIONS -->
                    <!-- Remove -->
                    <?php if (($current == $author || current_user_can('administrator')) && !in_category(array('removed', 'fetched', 'locker'))) : ?>
                        <?php include_once LOOPIS_THEME_DIR . '/includes/functions/user-extra/post-action-remove.php'; ?>
                        <?php if (isset($_POST['remove'])) { action_remove(get_the_ID()); } ?>
                        <button type="submit" form="remove-form" class="option" onclick="return confirm('Ta bort annonsen?')"><i class="fas fa-times"></i>Ta bort</button>
                        <form id="remove-form" method="post" action="" style="display:inline; float:left;"><input type="hidden" name="remove" value="1"></form>
                    <?php endif;?>
                    
                    <!-- Copy link -->
                    <button type="button" id="copy_url">🔗 Kopiera länk</button>

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

                    // User action
                    include LOOPIS_THEME_DIR . '/templates/post/post-actions.php';

                    // Comments
                    if (comments_open()) {
                        comments_template('/comments.php', true);
                    }
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
    </div><!--content-->

<!-- Extra scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/extra-image-switch.js"></script>
<script src="<?php echo LOOPIS_THEME_URI; ?>/assets/js/image-loader.js"></script>


<?php get_footer();
?>
<script>
    // loads and processes images see image-loader.js and gift-post-form.php loads
<?php if (!empty($pending)): ?>
    imageLoader();
<?php endif; ?>
</script>
