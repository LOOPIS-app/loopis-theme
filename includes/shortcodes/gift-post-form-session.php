<?php
/**
 * Form for support-posts
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


function loopis_gift_session_post() {
    global $wpdb;
    if ( is_user_logged_in() ) {
        
        ?>
        <!-- post form -->
        <form action="" method="post" id="loopis-form" enctype="multipart/form-data" >
            <?php wp_nonce_field('loopis_gift_post_action', 'loopis_gift_post_nonce'); ?>
            <div class="form-subcontainer">
                <h5  class="required" for="images"> 1️⃣ Bilder <span class='secret right' id='image_warning'> ⚠️ Välj 1-3 bilder </span></h5>
                <input type="file" id="images" name="images[]" accept="image/*" multiple >
                <small>Välj 1-3 bilder, den första visas i listor!</small>
                <div id="image-previews">
                </div>
            </div>

            <div class="form-subcontainer">
                <h5 class="required" for="post_title"> 2️⃣ Rubrik <span class='secret right' id='title_warning'> ⚠️ Var god fyll i fältet </span></h5>
                <input type="text" id="post_title" name="post_title" placeholder="T.ex: Cykel, monark" >
            </div>
            <div class="form-subcontainer">
                <h5  class="required" for="post_content"> 3️⃣ Beskrivning <span class='secret right' id='content_warning'> ⚠️  Var god fyll i fältet </span></h5>
                <textarea id="post_content" name="post_content" placeholder="T.ex: En välanvänd monarkcykel, behöver ny kedja"></textarea>
            </div>
            <div class="form-subcontainer">
                <h5 class="required" >4️⃣ Kategorier <span class='secret right' id='category_warning'> ⚠️ Välj minst 1 kategori! </span></h5>
                <div id="checkbox_container">
                    <?php
                    $terms = $wpdb->get_results("SELECT t.term_id, t.name
                    FROM {$wpdb->terms} t
                    INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                    WHERE tt.taxonomy = 'post_tag';", ARRAY_A);

                    foreach ($terms as $term){
                        echo '<label class="checkbox-row">'. ucfirst($term['name']) . '<input type="checkbox" name="terms[]" class="term_checkbox" value="' . $term['term_id'] . '"/><br/></label>';
                    }
                    ?>
                </div>
            </div>
            <div class="form-subcontainer" id="radio-box">
                <h5 class="required" >5️⃣ Överlämning  <span class='secret right' id='radio_warning'> ⚠️ Välj en plats! </span></h5> 
                <label for="skapet" class="buttons" ><input type="radio" name="locker" id="skapet" value=1/>  Skåpet </label>
                <label for="annan" class="buttons"><input type="radio" name="locker" id="annan" value=0 />  Annan adress </label> </input>
                <div class="hidden-container">
                    <label for="where"> Vilken adress: </label>
                    <input  type="text" id="where" name="where" placeholder="T.ex. Växthusvägen 12" >
                </div>
            </div>
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
            $terms = isset($_POST['terms']) ? array_map('intval', $_POST['terms']) : [];
            $locker = (isset($_POST['locker']) && in_array($_POST['locker'], [0,1])) ? (int) $_POST['locker'] : 0;
            $where = isset($_POST['where']) ? sanitize_textarea_field($_POST['where']): '';
            $images = $_FILES['images'];
            // Safety layering for twits that try to bypass js
            if (!current_user_can('publish_posts')) {
                wp_die('Not allowed');
            }
            if (count($images['name']) > 3) {
                wp_die('Max 3 images allowed');
            }
            if (count($terms) > 3) {
                wp_die('Max 3 categories allowed');
            }

            $new_post = array(
                'post_title'   => $post_title,
                'post_content' => $post_content,
                'post_status'  => 'publish', 
                'post_author'  => get_current_user_id(),
                'post_type'    => 'post',
            );
            if (!empty($images['error'][0])) {
                error_log(print_r($images['error'], true));
            }
            $new_post = wp_insert_post( $new_post );
            // Set meta and taxonomy
            if(!is_wp_error($new_post)){

                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                if(!empty($images['name'][0])){
                    foreach ($images['name'] as $index => $name){

                        $ext = pathinfo($name, PATHINFO_EXTENSION);
                        $new_name = 'img-' .$new_post. '-' . $index .'.'. $ext;

                        $file = [
                            'name'       => $new_name,
                            'tmp_name'   => $images['tmp_name'][$index],
                        ];

                        $image_id = media_handle_sideload($file, $new_post);

                        if (!is_wp_error($image_id)){
                            if ($index===0){
                                set_post_thumbnail($new_post, $image_id);
                            }
                            update_post_meta($new_post, 'image_' . ($index+1), $image_id);
                        }
                    }
                }

                // Upload image and attach to post
                wp_set_post_terms($new_post, loopis_cat("new"), 'category', false );
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
    
                echo '<p>Tack!💚</p>';
            }else{
                echo '<p>Hoppsan det gick inte riktigt, försök gärna igen!</p>';
            }

        }
    } else {
            echo '<p>Du måste vara inloggad för att ge saker.</p>';
    }
}


function loopis_create_post(){
    if ( isset( $_POST['submit_gift_post'] ) ) {
        if (!isset($_POST['loopis_gift_post_nonce']) || !wp_verify_nonce($_POST['loopis_gift_post_nonce'], 'loopis_gift_post_action')) {
            wp_die('Security check failed');
        }
        $post_title = sanitize_textarea_field( $_POST['post_title'] );
        $post_content = wp_kses_post( $_POST['post_content'] );
        $terms = isset($_POST['terms']) ? array_map('intval', $_POST['terms']) : [];
        $locker = (isset($_POST['locker']) && in_array($_POST['locker'], [0,1])) ? (int) $_POST['locker'] : 0;
        $where = isset($_POST['where']) ? sanitize_textarea_field($_POST['where']): '';
        $images = $_FILES['images'];
        // Safety layering for twits that try to bypass js
        if (!current_user_can('publish_posts')) {
            wp_die('Not allowed');
        }
        if (count($images['name']) > 3) {
            wp_die('Max 3 images allowed');
        }
        if (count($terms) > 3) {
            wp_die('Max 3 categories allowed');
        }
        $new_post = array(
            'post_title'   => $post_title,
            'post_content' => $post_content,
            'post_status'  => 'publish', 
            'post_author'  => get_current_user_id(),
            'post_type'    => 'post',
        );
        if (!empty($images['error'][0])) {
            error_log(print_r($images['error'], true));
        }
        $new_post = wp_insert_post( $new_post );
        // Set meta and taxonomy
        if(!is_wp_error($new_post)){
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            if(!empty($images['name'][0])){
                foreach ($images['name'] as $index => $name){
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $new_name = 'img-' .$new_post. '-' . $index .'.'. $ext;

                    $file = [
                        'name'       => $new_name,
                        'tmp_name'   => $images['tmp_name'][$index],
                    ];

                    $image_id = media_handle_sideload($file, $new_post);
                    if (!is_wp_error($image_id)){
                        if ($index===0){
                            set_post_thumbnail($new_post, $image_id);
                        }
                        update_post_meta($new_post, 'image_' . ($index+1), $image_id);
                    }
                }
                }
            // Upload image and attach to post
            wp_set_post_terms($new_post, loopis_cat("new"), 'category', false );
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

            echo '<p>Tack!💚</p>';
        }else{
            echo '<p>Hoppsan det gick inte riktigt, försök gärna igen!</p>';
        }

    }
}

function loopis_upload_file(){
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $image_id = media_handle_upload('image', 0);

    update_post_meta($image_id, '_temp', 1);
    update_post_meta($image_id, '_session_key', 1);
    update_post_meta($image_id, '_time', time());

    wp_send_json_error($image_id->get_error_message());

    wp_send_json_success([
        'imgid' => $image_id,
    ]);
}
