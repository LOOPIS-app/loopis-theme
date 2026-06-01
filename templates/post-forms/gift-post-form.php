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
        $post_cat = wp_get_post_terms($post_id, 'category', ['fields' => 'ids']);
        $post_cat = !empty($post_cat)   ? $post_cat[0]    : loopis_cat('new');
        $edit = true;
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
                <script>
                window.existingImages = <?php
                $images = [];
                
                $ids = [$thumbnail_id, $image_2_id, $image_3_id];
                
                foreach ($ids as $i => $id) {
                    if (!$id) continue;
                
                    $images[] = [
                        'id' => (int) $id,
                        'src' => wp_get_attachment_image_url($id, 'large'),
                        'thumbnail' => ($id === $thumbnail_id)
                    ];
                }
                
                echo json_encode($images);
                ?>;
                </script>
                
                <small>Aktuella bilder:</small>
                <div class="image-previews" id="image-previews">
                </div>
                <label for="images" id="img-label" class="image-prev"><i class="fa-solid fa-camera"></i></label>
                <input type="file" id="images" name="images[]" accept="image/*" multiple style="display:none;">
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
                        'name' => 'cat',
                        'id' => 'cat',
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
        $images['thumb'] = isset($_POST['thumb'])? (int) $_POST['thumb'] : 0;
        $images['error'] = $_FILES['images']['error'] ?? [];
        $images['type']  = $_FILES['images']['type'] ?? [];
        $images['size']  = $_FILES['images']['size'] ?? [];
        $has_existing_changes =
            !empty($_POST['remove_old_0']) ||
            !empty($_POST['remove_old_1']) ||
            !empty($_POST['remove_old_2']) ||
            isset($_POST['rotation_0']) ||
            isset($_POST['rotation_1']) ||
            isset($_POST['rotation_2']);
        if (!current_user_can('publish_posts')) {
            wp_die('Not allowed');
        }
        if (!empty($images['name'][0]) && count($images['name']) > 3) {
        wp_die('Max 3 images allowed');
        }
        if (count($terms) > 3) {
            wp_die('Max 3 categories allowed');
        }
        if (!empty($images['error'][0])) {
            error_log(print_r($images['error'], true));
        }
        $allowed_mimes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];
        if (!empty($images['error'])){
            
            foreach ($images['error'] as $index => $error) {

                if ($error !== UPLOAD_ERR_OK) {
                    wp_die('Image upload failed');
                }  

                if (!in_array($images['type'][$index], $allowed_mimes, true)) {
                    wp_die('Invalid image type');
                }
            }
        }
        
        if ($edit){ // IGNORE A LITTLE

            if (!empty($_POST['remove_old_0'])) {
                wp_delete_attachment(get_post_thumbnail_id($post_id), true);
                delete_post_thumbnail($post_id);
            }

            if (!empty($_POST['remove_old_1'])) {
                wp_delete_attachment(get_post_meta($post_id, 'image_2', true), true);
                delete_post_meta($post_id, 'image_2');
            }

            if (!empty($_POST['remove_old_2'])) {
                wp_delete_attachment(get_post_meta($post_id, 'image_3', true), true);
                delete_post_meta($post_id, 'image_3');
            }

            $post = array(
                'post_title'   => $post_title,
                'ID'           => $post_id,
                'post_content' => $post_content,
                'post_status'  => 'publish', 
                'post_author'  => get_current_user_id(),
                'post_type'    => 'post',
            );
            wp_update_post($post);

            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $uploads_tmp = WP_CONTENT_DIR . '/uploads/tmp/';
            if (!is_dir($uploads_tmp)) {
                mkdir($uploads_tmp, 0755, true);
            }
            if (!empty($images['name'][0])|| $has_existing_changes) {

                $processed_images = [];

                foreach ($_POST as $key => $value) {
                        
                    if (!preg_match('/^rotation_(\d+)$/', $key, $matches)) {
                        continue;
                    }
                        
                    $index = intval($matches[1]);
                        
                    if (!empty($_POST['remove_' . $index])) {
                        continue;
                    }
                        
                    $rotation = intval($_POST['rotation_' . $index]);
                        
                    $processed_images[$index] = [
                        'rotation' => $rotation,
                        'is_new'   => isset($_FILES['images']['tmp_name'][$index]) &&
                                      !empty($_FILES['images']['tmp_name'][$index]),
                    ];
                        
                    if ($processed_images[$index]['is_new']) {
                        
                        $tmp = $_FILES['images']['tmp_name'][$index];
                        
                        $ext = pathinfo($_FILES['images']['name'][$index], PATHINFO_EXTENSION);
                        
                        $file_to = $uploads_tmp . uniqid('loopis_', true) . '.' . $ext;
                        
                        if (move_uploaded_file($tmp, $file_to)) {
                        
                            $processed_images[$index]['tmp_name'] = $file_to;
                        
                            $processed_images[$index]['name'] =
                                $_FILES['images']['name'][$index];
                        }
                    }
                }
                        
                update_post_meta($post_id, '_pending_images', $processed_images);
            }

            // Upload image and attach to post
            wp_set_post_terms($post_id, $cat, 'category', false );
            wp_set_post_terms($post_id, $terms, 'post_tag', false );
            $new_url = get_permalink($post_id);
            update_post_meta($post_id, 'link', $new_url );
            if ($locker===1){
                update_post_meta($post_id, 'location', 'Skåpet' );
            }elseif(isset($where) && $where!==''){
                update_post_meta($post_id, 'location', $where );
            }else{
                update_post_meta($post_id, 'location', 'Plats saknas' );
            }
            wp_redirect(get_permalink($post_id));


            exit;
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
                        $ext = pathinfo($name, PATHINFO_EXTENSION);

                        $file_to = $uploads_tmp . uniqid('loopis_', true) . '.' . $ext;
                        if(move_uploaded_file( $images['tmp_name'][$index] , $file_to )){
                            $images['tmp_name'][$index] = $file_to;
                        }
                        $images['rotation'][$index] = $_POST['rotation_'.$index];
                        $images['is_old'][$index] = false;
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