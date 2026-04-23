<?php
/**
 * Form for support-posts
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('wp_enqueue_scripts', 
function () {
    wp_enqueue_script(
        'loopis-theme-post-scripts', // handle
        LOOPIS_THEME_URI . '/assets/js/checkbox-limited.js', // URL to JS
        array(), // dependencies
        filemtime(LOOPIS_THEME_DIR . '/assets/js/checkbox-limited.js'), // version
        true // load in footer
    );
});


function loopis_gift_post() {
    global $wpdb;
    if ( is_user_logged_in() ) {
        
        ?>
        <!-- post form -->
        <form action="" method="post" id="loopis-form" enctype="multipart/form-data" >
            <?php wp_nonce_field('loopis_gift_post_action', 'loopis_gift_post_nonce'); ?>
            <div class="form-subcontainer">
                <label  class="required" for="images"> 1️⃣ Bilder </label>
                <input type="file" id="images" name="images[]" accept="image/*" multiple required>
                <small> Maximal filstorlek per bild: 50 MB. Max antal bilder: 3</small>
                <div id="image-previews">
                </div>
            </div>

            <div class="form-subcontainer">
                <label class="required" for="post_title"> 2️⃣ Rubrik</label>
                <input type="text" id="post_title" name="post_title" placeholder="T.ex: Cykel, monark" required>
            </div>
            <div class="form-subcontainer">
                <label  class="required" for="post_content"> 3️⃣ Beskrivning</label>
                <textarea id="post_content" name="post_content" placeholder="T.ex: En välanvänd monarkcykel, behöver ny kedja"required></textarea>
            </div>
            <div class="form-subcontainer">
                <label class="required" >4️⃣ Kategorier </label>
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
                <label class="required" >5️⃣ Överlämning </label> 
                <label for="skapet" class="buttons" ><input type="radio" name="overlamning" id="skapet" value=1/>  Skåpet </label>
                <label for="annan" class="buttons"><input type="radio" name="overlamning" id="annan" value=0 />  Annan adress </label> </input>
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
            $locker = isset($_POST['overlamning']) ? (int) $_POST['overlamning'] : 0;
            $where = isset($_POST['where']) ? sanitize_textarea_field($_POST['where']): '';
            

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

                $images = $_FILES['images'];

                if(!empty($images['name'][0])){
                    foreach ($images['name'] as $index => $name){
                        $ext = pathinfo($name, PATHINFO_EXTENSION);
                        $new_name = 'img-' .$new_post. '-' . $index .'.'. $ext;
                        $file = [
                            'name'       => $new_name,
                            'type'       => $images['type'][$index],
                            'tmp_name'   => $images['tmp_name'][$index],
                            'error'      => $images['error'][$index],
                            'size'       => $images['size'][$index],
                        ];

                        $_FILES['single_image']=$file;

                        $image_id = media_handle_upload('single_image', $new_post);
                        if (!is_wp_error($image_id)){
                            if ($index===0){
                                set_post_thumbnail($new_post, $image_id);
                                update_post_meta($new_post, 'image_1', $image_id);
                            }else{
                                update_post_meta($new_post, 'image_' . ($index+1), $image_id);
                            }
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
add_shortcode( 'loopis_gift_post', 'loopis_gift_post' );