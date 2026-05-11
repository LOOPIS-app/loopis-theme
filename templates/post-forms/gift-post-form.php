<?php
/**
 * Form for gift-posts
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $wpdb;

wp_enqueue_script(
    'loopis_checkbox_limited_js', // handle
    LOOPIS_THEME_URI . '/assets/js/checkbox-limited.js', // URL to JS
    ['jquery'],
    filemtime(LOOPIS_THEME_DIR . '/assets/js/checkbox-limited.js'),
    true // load in footer
);

$location = '';
$post_terms = [];
$edit = false;
if (isset($_GET['post_id'])){
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    if (current_user_can( 'edit_post', $post_id)){
        $post = get_post($post_id);
        $post_title = $post->post_title;
        $post_content = $post->post_content;
        $post_terms = wp_get_post_terms( $post_id, 'post_tag', ['fields' => 'ids']);
        $location = get_post_meta($post_id, 'location', true);
        $post_cat = wp_get_post_terms( $post_id, 'category', ['fields' => 'ids'])[0];
        $edit = true;
        ?>
        <script>
            
        </script>
        <?php
    }else{
        wp_die('You are not allowed to edit this post');
    }
}

if ( is_user_logged_in() ) {
    ?>
    <!-- post form -->
    <form action="" method="post" id="loopis-form" enctype="multipart/form-data" name="<?php echo ($edit ? "edit" : "submission")?>">
        <?php wp_nonce_field('loopis_gift_post_action', 'loopis_gift_post_nonce'); ?>
        <div class="form-subcontainer">
            <h5  class="required" for="images"> 1️⃣ Bilder <span class='secret right' id='image_warning'> ⚠️ Välj 1-3 bilder </span></h5>
            <input type="hidden" id="thumb" name="thumb" value = 0>
            <?php
            if ($edit){
                $thumbnail_id = get_post_thumbnail_id($post_id);
                $image_2_id = get_post_meta($post_id, 'image_2', true);
                $image_3_id = get_post_meta($post_id, 'image_3', true);
                ?>
                <small>Aktuella bilder:</small>
                <div class="image-previews" id="current-image-previews">
                    <?php
                    
                    echo wp_get_attachment_image( $thumbnail_id, 'large', false, array('class'=> 'image-prev', 'id' => 'img-0'));
                    
                    if ($image_2_id) {
                        echo wp_get_attachment_image($image_2_id, 'large', false, array('class'=> 'image-prev', 'id' => 'img-1'));
                    }
                    if ($image_3_id) {
                        echo wp_get_attachment_image($image_3_id, 'large', false, array('class'=> 'image-prev', 'id' => 'img-2'));
                    }
                    ?>
                </div>
                <?php
            }else{
                ?>
                <label for="images" id="file-label">Välj bilder</label>
                <input type="file" id="images" name="images[]" accept="image/*" multiple style="display:none;">
                <small>Välj 1-3 bilder, den första visas i listor.</small>
                <div class="image-previews" id="image-previews">
                </div>
                <?php
            }
            ?>
        </div>
        <div class="form-subcontainer">
            <h5 class="required" for="post_title"> 2️⃣ Rubrik <span class='secret right' id='title_warning'> ⚠️ Var god fyll i fältet </span></h5>
            <input type="text" id="post_title" name="post_title" placeholder="T.ex: Cykel, monark" value="<?php echo ($edit ? $post_title : ''); ?>">
        </div>
        <div class="form-subcontainer">
            <h5  class="required" for="post_content"> 3️⃣ Beskrivning <span class='secret right' id='content_warning'> ⚠️  Var god fyll i fältet </span></h5>
            <textarea id="post_content" name="post_content" placeholder="T.ex: En välanvänd monarkcykel, behöver ny kedja"><?php echo ($edit ? $post_content : ''); ?></textarea>
        </div>
        <div class="form-subcontainer">
            <h5 class="required" >4️⃣ Kategorier <span class='secret right' id='category_warning'> ⚠️ Välj minst 1 kategori! </span></h5>
            <div id="termlist" style="  display: flex; flex-direction  : row;">

            </div>
            <div id="checkbox_container">
                <?php
                $terms = $wpdb->get_results("SELECT t.term_id, t.name
                FROM {$wpdb->terms} t
                INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                WHERE tt.taxonomy = 'post_tag';", ARRAY_A);
                foreach ($terms as $term){
                    $checked = in_array($term['term_id'], $post_terms) ? 'checked' : '';
                    echo '<label class="checkbox-row"><span>'. ucfirst($term['name']) . '</span><input id="'. ucfirst($term['name']) . '" type="checkbox" name="terms[]" class="term_checkbox" value="' . $term['term_id'] . '" '. $checked .'/><br/></label>';
                }
                ?>
            </div>
        </div>
        <div class="form-subcontainer" id="radio-box" >
            <h5 class="required" >5️⃣ Överlämning  <span class='secret right' id='radio_warning'> ⚠️ Välj en plats! </span></h5> 
            <label for="skapet" class="buttons" ><input type="radio" name="locker" id="skapet" value=1 <?php echo (($location==='Skåpet')? 'checked' : '');?>/>  Skåpet </label>
            <label for="annan" class="buttons"><input type="radio" name="locker" id="annan" value=0 <?php echo (($location==='Skåpet'||$location==='') ? '' : 'checked');?>/>  Annan adress </label> </input>
            <div class="hidden-container">
                <label for="where"> Vilken adress: </label>
                <input  type="text" id="where" name="where" placeholder="T.ex. Växthusvägen 12" value="<?php echo (($location==='Skåpet')? '' : $location);?>">
            </div>
        </div>
        <?php if (current_user_can('loopis_storage')):?>
            <div class="form-subcontainer">
                <h5 class="" >6️⃣ Visinings-kategori</h5>
                <div id="dropdown" class="dropdown">
                    <?php
                    $dropdown_settings = array(
                        'taxonomy' => 'category',
                        'name' => 'post_category',
                        'id' => 'post_category',
                        'class' => 'category-dropdown',
                        'hide_empty' => false,
                        'selected' => isset($post_cat) ? strval($post_cat) : strval(loopis_cat('new')),
                    );
                    if (!current_user_can('administrator')){
                        $dropdown_settings['include'] = [loopis_cat('new'), loopis_cat('storage')];
                        if ($edit) {
                            $dropdown_settings['include'][] = $post_cat;
                        }
                    }
                    
                    wp_dropdown_categories($dropdown_settings);
     
                    ?>
                </div>
            </div>
        <?php endif ;?>
        <p>
            <input type="submit" name="submit_gift_post" value="Publicera!">
        </p>
    </form>
    <?php
    if ( isset( $_POST['submit_gift_post'] ) ) {
        if (!isset($_POST['loopis_gift_post_nonce']) || !wp_verify_nonce($_POST['loopis_gift_post_nonce'], 'loopis_gift_post_action')) {
            wp_die('Security check failed');
        }
        $post_title = sanitize_textarea_field( $_POST['post_title'] );
        $post_content = wp_kses_post( $_POST['post_content'] );
        $cat =  isset($_POST['cat']) ? (int) $_POST['cat'] : loopis_cat("new");
        $terms = isset($_POST['terms']) ? array_map('intval', $_POST['terms']) : [];
        $locker = (isset($_POST['locker']) && in_array($_POST['locker'], [0,1])) ? (int) $_POST['locker'] : 0;
        $where = isset($_POST['where']) ? sanitize_textarea_field($_POST['where']): '';
        $images = [];
        $images['name'] = $_FILES['images']['name'] ?? '';
        $images['tmp_name'] = $_FILES['images']['tmp_name'] ?? '';
        $images['thumb'] = (int) $_POST['thumb'] ?? 0;
        if (!current_user_can('publish_posts')) {
            wp_die('Not allowed');
        }
        if ($images['name'] !== ''){
            if (count($images['name']) > 3) {
                wp_die('Max 3 images allowed');
            }
        }
        if (count($terms) > 3) {
            wp_die('Max 3 categories allowed');
        }
        if (!empty($images['error'][0])) {
            error_log(print_r($images['error'], true));
        }
            
        if ($edit){
            $post = array(
                'post_title'   => $post_title,
                'ID'           => $post_id,
                'post_content' => $post_content,
                'post_status'  => 'publish', 
                'post_author'  => get_current_user_id(),
                'post_type'    => 'post',
            );
            wp_update_post($post);
        } else{
            $new_post = array(
                'post_title'   => $post_title,
                'post_content' => $post_content,
                'post_status'  => 'publish', 
                'post_author'  => get_current_user_id(),
                'post_type'    => 'post',
            );
            $new_post = wp_insert_post( $new_post );
            // Set meta and taxonomy
            if(!is_wp_error($new_post)){
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                if(!empty($images['name'][0])){
                    $uploads_tmp = WP_CONTENT_DIR . '/uploads/tmp/';
                    if (!is_dir($uploads_tmp)) {
                        mkdir($uploads_tmp, 0755, true);
                    }
                    foreach ($images['name'] as $index => $name){
                        $file_to = $uploads_tmp . basename($new_post . '_image_'. $index);
                        if(move_uploaded_file( $images['tmp_name'][$index] , $file_to )){
                            $images['tmp_name'][$index] = $file_to;
                        }
                        $images['rotation'][$index] = $_POST['rotation_'.$index];
                    }
                    update_post_meta($new_post, '_pending_images', $images );
                }
                // Upload image and attach to post
                wp_set_post_terms($new_post, $cat, 'category', false );
                wp_set_post_terms($new_post, $terms, 'post_tag', false );
                $new_url = get_permalink($new_post);
                update_post_meta($new_post, 'link', $new_url );
                if ($locker===1){
                    update_post_meta($new_post, 'location', 'Skåpet' );
                }elseif(isset($where) && $where!==''){
                    update_post_meta($new_post, 'location', $where );
                }else{
                    update_post_meta($new_post, 'location', 'Plats saknas' );
                }
                wp_redirect(get_permalink($new_post));
                exit;
            }else{
                echo '<p>Hoppsan det gick inte riktigt, försök gärna igen!</p>';
            }
        } 
    }
} else {
        echo '<p>Du måste vara inloggad för att ge saker.</p>';
}
