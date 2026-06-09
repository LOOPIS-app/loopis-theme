
<?php
/**
 * Frontend form for creating gift posts.
 * 
 * Interactivity handled by gift-form.js
 * Created by CoPilot, prompted by Johan
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load form behavior script on page where form is included.
wp_enqueue_script(
    'loopis_gift_form_js',
    LOOPIS_THEME_URI . '/assets/js/gift-form.js',
    array(),
    filemtime(LOOPIS_THEME_DIR . '/assets/js/gift-form.js'),
    true
);

if (!function_exists('loopis_theme_hq_gift_form_normalize_files')) {
    /**
     * Convert PHP multi-file upload structure into a flat array.
     *
     * @param array $files The $_FILES field.
     * @return array<int, array<string, mixed>>
     */
    function loopis_theme_hq_gift_form_normalize_files(array $files) {
        $normalized = array();

        if (empty($files['name']) || !is_array($files['name'])) {
            return $normalized;
        }

        $count = count($files['name']);
        for ($index = 0; $index < $count; $index++) {
            if (empty($files['name'][$index])) {
                continue;
            }

            $normalized[] = array(
                'name'     => $files['name'][$index],
                'type'     => $files['type'][$index] ?? '',
                'tmp_name' => $files['tmp_name'][$index] ?? '',
                'error'    => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                'size'     => $files['size'][$index] ?? 0,
            );
        }

        return $normalized;
    }
}

// Prepare default form state so values survive validation errors.
$default_cat = function_exists('loopis_cat') ? (int) loopis_cat('new') : 0;
$selected_cat = isset($_POST['cat']) ? (int) $_POST['cat'] : $default_cat;
$selected_terms = isset($_POST['terms']) ? array_map('intval', (array) $_POST['terms']) : array();
$selected_locker = isset($_POST['locker']) ? (int) $_POST['locker'] : 1;
$title_value = isset($_POST['post_title']) ? sanitize_text_field(wp_unslash($_POST['post_title'])) : '';
$content_value = isset($_POST['post_content']) ? sanitize_textarea_field(wp_unslash($_POST['post_content'])) : '';
$custom_location_value = isset($_POST['custom_location']) ? sanitize_text_field(wp_unslash($_POST['custom_location'])) : '';
$post_tags = get_tags(array(
    'orderby'    => 'name',
    'order'      => 'ASC',
    'hide_empty' => false,
));
$is_admin_user = current_user_can('manage_options') || current_user_can('loopis_admin');
$gift_form_errors = array();
$gift_form_success = false;
$gift_form_created_post_id = isset($_GET['gift_post_id']) ? absint($_GET['gift_post_id']) : 0;
$gift_form_created_post_url = $gift_form_created_post_id > 0 ? get_permalink($gift_form_created_post_id) : '';
$gift_form_created_post_url = is_string($gift_form_created_post_url) ? $gift_form_created_post_url : '';
$gift_form_created_post_title = $gift_form_created_post_id > 0 ? get_the_title($gift_form_created_post_id) : '';
$gift_form_created_post_title = is_string($gift_form_created_post_title) ? $gift_form_created_post_title : '';
$gift_form_action_url = get_permalink(get_queried_object_id());
$featured_image_index = isset($_POST['featured_image_index']) ? absint($_POST['featured_image_index']) : 0;
$gift_form_edit_post_id = isset($_POST['edit_post_id'])
    ? absint($_POST['edit_post_id'])
    : (isset($_GET['edit_post_id']) ? absint($_GET['edit_post_id']) : 0);
$gift_form_is_edit_mode = false;
$gift_form_post_to_edit = null;
$gift_form_existing_images = array();

// Prefer the current request URL so existing query params (e.g. option=single) survive redirects.
if (!empty($_SERVER['REQUEST_URI'])) {
    $gift_form_action_url = home_url(wp_unslash($_SERVER['REQUEST_URI']));
}

// Fallback action target for success redirects.
if ('' === $gift_form_action_url) {
    $gift_form_action_url = wp_get_referer();
}

if ('' === $gift_form_action_url) {
    $gift_form_action_url = home_url('/');
}

if ($gift_form_edit_post_id > 0) {
    $gift_form_post_to_edit = get_post($gift_form_edit_post_id);
    if (!$gift_form_post_to_edit || 'post' !== $gift_form_post_to_edit->post_type) {
        $gift_form_errors[] = 'Annonsen kunde inte hittas för redigering.';
    } elseif (!current_user_can('edit_post', $gift_form_edit_post_id)) {
        $gift_form_errors[] = 'Du saknar behörighet att redigera annonsen.';
    } else {
        $gift_form_is_edit_mode = true;
    }
}

if ($gift_form_is_edit_mode && 'POST' !== strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET')) {
    $title_value = (string) $gift_form_post_to_edit->post_title;
    $content_value = (string) $gift_form_post_to_edit->post_content;
    $selected_terms = wp_get_post_terms($gift_form_edit_post_id, 'post_tag', array('fields' => 'ids'));
    $selected_terms = is_array($selected_terms) ? array_map('intval', $selected_terms) : array();

    if ($is_admin_user) {
        $selected_cats = wp_get_post_terms($gift_form_edit_post_id, 'category', array('fields' => 'ids'));
        if (!empty($selected_cats[0])) {
            $selected_cat = (int) $selected_cats[0];
        }
    }

    $location_value = (string) get_post_meta($gift_form_edit_post_id, 'location', true);
    if ('Skåpet' === $location_value || '' === trim($location_value)) {
        $selected_locker = 1;
        $custom_location_value = '';
    } else {
        $selected_locker = 0;
        $custom_location_value = $location_value;
    }

    $existing_image_ids = array_filter(array(
        get_post_thumbnail_id($gift_form_edit_post_id),
        (int) get_post_meta($gift_form_edit_post_id, 'image_2', true),
        (int) get_post_meta($gift_form_edit_post_id, 'image_3', true),
    ));

    foreach ($existing_image_ids as $existing_image_id) {
        $existing_image_url = wp_get_attachment_image_url((int) $existing_image_id, 'large');
        if (!$existing_image_url) {
            continue;
        }

        $parsed_path = wp_parse_url($existing_image_url, PHP_URL_PATH);
        $file_name = $parsed_path ? basename($parsed_path) : ('image-' . (int) $existing_image_id . '.jpg');

        $gift_form_existing_images[] = array(
            'url'  => esc_url_raw($existing_image_url),
            'name' => sanitize_file_name($file_name),
        );
    }
}

if ($gift_form_is_edit_mode && empty($gift_form_existing_images)) {
    $existing_image_ids = array_filter(array(
        get_post_thumbnail_id($gift_form_edit_post_id),
        (int) get_post_meta($gift_form_edit_post_id, 'image_2', true),
        (int) get_post_meta($gift_form_edit_post_id, 'image_3', true),
    ));

    foreach ($existing_image_ids as $existing_image_id) {
        $existing_image_url = wp_get_attachment_image_url((int) $existing_image_id, 'large');
        if (!$existing_image_url) {
            continue;
        }

        $parsed_path = wp_parse_url($existing_image_url, PHP_URL_PATH);
        $file_name = $parsed_path ? basename($parsed_path) : ('image-' . (int) $existing_image_id . '.jpg');

        $gift_form_existing_images[] = array(
            'url'  => esc_url_raw($existing_image_url),
            'name' => sanitize_file_name($file_name),
        );
    }
}

// Handle form submission, then re-render form with errors or redirect on success.
if ('POST' === strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') && isset($_POST['submit_gift_post'])) {
    // Security and capability checks.
    if (!isset($_POST['loopis_gift_form_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['loopis_gift_form_nonce'])), 'loopis_gift_form_action')) {
        $gift_form_errors[] = 'Säkerhetskontrollen misslyckades. Ladda om sidan och försök igen.';
    } elseif (!current_user_can('publish_posts')) {
        $gift_form_errors[] = 'Du har inte behörighet att publicera annonser.';
    } else {
        // Re-read sanitized input values from current POST payload.
        $title_value = sanitize_text_field(wp_unslash($_POST['post_title'] ?? ''));
        $content_value = sanitize_textarea_field(wp_unslash($_POST['post_content'] ?? ''));
        $selected_terms = array_map('intval', (array) ($_POST['terms'] ?? array()));
        $selected_locker = isset($_POST['locker']) ? (int) $_POST['locker'] : 1;
        $custom_location_value = sanitize_text_field(wp_unslash($_POST['custom_location'] ?? ''));
        $featured_image_index = isset($_POST['featured_image_index']) ? absint($_POST['featured_image_index']) : 0;

        $selected_cat = $is_admin_user ? (int) ($_POST['cat'] ?? $default_cat) : $default_cat;
        $selected_cat = $selected_cat > 0 ? $selected_cat : $default_cat;

        // Field-level validation.
        if ('' === trim($title_value)) {
            $gift_form_errors[] = 'Fyll i en rubrik.';
        }

        if ('' === trim($content_value)) {
            $gift_form_errors[] = 'Fyll i en beskrivning.';
        } elseif ((function_exists('mb_strlen') ? mb_strlen(trim($content_value)) : strlen(trim($content_value))) < 10) {
            $gift_form_errors[] = 'Beskrivningen måste vara minst 10 tecken.';
        }

        if (count($selected_terms) < 1) {
            $gift_form_errors[] = 'Välj minst en kategori.';
        } elseif (count($selected_terms) > 3) {
            $gift_form_errors[] = 'Du kan välja högst tre kategorier.';
        }

        if (0 === $selected_locker && '' === trim($custom_location_value)) {
            $gift_form_errors[] = 'Fyll i adressen när du väljer annan adress.';
        }

        if ((function_exists('mb_strlen') ? mb_strlen($custom_location_value) : strlen($custom_location_value)) > 40) {
            $gift_form_errors[] = 'Adressen får vara högst 40 tecken.';
        }

        // Normalize uploaded files and validate count.
        $uploaded_files = array();
        if (!empty($_FILES['images'])) {
            $uploaded_files = loopis_theme_hq_gift_form_normalize_files($_FILES['images']);
        }

        if (empty($uploaded_files) && !$gift_form_is_edit_mode) {
            $gift_form_errors[] = 'Lägg upp minst en bild.';
        } elseif (count($uploaded_files) > 3) {
            $gift_form_errors[] = 'Du kan ladda upp högst tre bilder.';
        }

        if (!empty($uploaded_files)) {
            $featured_image_index = min($featured_image_index, count($uploaded_files) - 1);
        }

        if (empty($gift_form_errors)) {
            // Bring in WP media/file helpers used by media_handle_sideload.
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            $attachment_ids = array();
            $allowed_types = array('image/jpeg', 'image/png', 'image/webp');

            if ($gift_form_is_edit_mode) {
                // Update existing post in edit mode.
                $post_id = wp_update_post(array(
                    'ID'           => $gift_form_edit_post_id,
                    'post_title'   => $title_value,
                    'post_content' => $content_value,
                ), true);
            } else {
                // Create the post first; image and taxonomy data is attached after.
                $post_id = wp_insert_post(array(
                    'post_title'   => $title_value,
                    'post_content' => $content_value,
                    'post_status'  => 'publish',
                    'post_author'  => get_current_user_id(),
                    'post_type'    => 'post',
                ), true);
            }

            if (is_wp_error($post_id)) {
                $gift_form_errors[] = $post_id->get_error_message();
            } else {
                // Build deterministic slot mapping so featured image is always img-1.
                $image_slot_map = array();
                $next_image_slot = 2;
                foreach ($uploaded_files as $upload_index => $file) {
                    if ($upload_index === $featured_image_index) {
                        $image_slot_map[$upload_index] = 1;
                    } else {
                        $image_slot_map[$upload_index] = $next_image_slot;
                        $next_image_slot++;
                    }
                }

                // Upload and attach selected images when provided.
                foreach ($uploaded_files as $index => $file) {
                    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                        $gift_form_errors[] = 'En bild kunde inte laddas upp.';
                        continue;
                    }

                    if (!in_array(($file['type'] ?? ''), $allowed_types, true)) {
                        $gift_form_errors[] = 'Endast JPG, PNG och WebP är tillåtna.';
                        continue;
                    }

                    // Rename uploaded files for easy post-scoped references: p[postID]-img-[slot].[ext]
                    $image_slot = (int) ($image_slot_map[$index] ?? ($index + 1));
                    $mime_type = (string) ($file['type'] ?? '');
                    $name_extension = strtolower((string) pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
                    $image_extension = 'jpg';

                    if ('image/png' === $mime_type || 'png' === $name_extension) {
                        $image_extension = 'png';
                    } elseif ('image/webp' === $mime_type || 'webp' === $name_extension) {
                        $image_extension = 'webp';
                    }

                    $file['name'] = 'p' . (int) $post_id . '-img-' . $image_slot . '.' . $image_extension;

                    $attachment_id = media_handle_sideload($file, $post_id);
                    if (is_wp_error($attachment_id)) {
                        $gift_form_errors[] = $attachment_id->get_error_message();
                        continue;
                    }

                    $attachment_ids[] = (int) $attachment_id;
                }

                if (empty($attachment_ids) && !$gift_form_is_edit_mode) {
                    // Avoid orphan posts when all image uploads fail.
                    wp_delete_post($post_id, true);
                    $gift_form_errors[] = 'Ingen bild kunde sparas, så annonsen publicerades inte.';
                } elseif (!empty($attachment_ids)) {
                    // Set featured + extra images from freshly uploaded files.
                    $featured_attachment_id = $attachment_ids[$featured_image_index] ?? $attachment_ids[0];
                    $gallery_attachment_ids = array_values(array_filter(
                        $attachment_ids,
                        function($attachment_id) use ($featured_attachment_id) {
                            return (int) $attachment_id !== (int) $featured_attachment_id;
                        }
                    ));

                    set_post_thumbnail($post_id, $featured_attachment_id);

                    if (!empty($gallery_attachment_ids[0])) {
                        update_post_meta($post_id, 'image_2', $gallery_attachment_ids[0]);
                    }

                    if (!empty($gallery_attachment_ids[1])) {
                        update_post_meta($post_id, 'image_3', $gallery_attachment_ids[1]);
                    }
                }

                if (empty($gift_form_errors)) {
                    // Persist taxonomy/meta even when edit-mode keeps current images.
                    wp_set_post_terms($post_id, $selected_terms, 'post_tag', false);
                    wp_set_post_terms($post_id, $selected_cat, 'category', false);

                    $location_value = 0 === $selected_locker ? $custom_location_value : 'Skåpet';
                    update_post_meta($post_id, 'location', $location_value);
                    update_post_meta($post_id, 'locker_id', $selected_locker);

                    // If new images were uploaded in edit mode, clear stale extra image meta.
                    if (!empty($attachment_ids)) {
                        if (empty($gallery_attachment_ids[0])) {
                            delete_post_meta($post_id, 'image_2');
                        }
                        if (empty($gallery_attachment_ids[1])) {
                            delete_post_meta($post_id, 'image_3');
                        }
                    }

                    $gift_form_success = true;

                    // Post/Redirect/Get to prevent duplicate submissions on refresh.
                    if ($gift_form_is_edit_mode) {
                        $redirect_url = get_permalink($post_id);
                    } else {
                        $redirect_url = add_query_arg(
                            array(
                                'gift_form_success' => '1',
                                'gift_post_id'      => (int) $post_id,
                                'gift_form_mode'    => 'create',
                            ),
                            $gift_form_action_url
                        );
                    }
                    if (wp_safe_redirect($redirect_url)) {
                        exit;
                    }
                }
            }
        }
    }
}
?>

<?php // Success feedback after redirect. ?>
<?php if (isset($_GET['gift_form_success'])) : ?>
    <div class="loopis-message success">
        <h5>✅ Klart!</h5>
        <hr>
        <p><?php if ('' !== $gift_form_created_post_url) : ?>
            🎁 Se din annons → <span class="big-link"><a href="<?php echo esc_url($gift_form_created_post_url); ?>"><?php echo esc_html($gift_form_created_post_title ?: 'Ny annons'); ?></a></span>
            <?php endif; ?><br>
            ⏳ Lottning sker imorgon klockan 12.<br>
            💚 Du kan skapa en till annons direkt.
        </p>
    </div>
<?php endif; ?>

<?php // Validation and upload errors from current submission attempt. ?>
<?php if (!empty($gift_form_errors)) : ?>
    <div class="loopis-message error" role="alert">
        <?php foreach ($gift_form_errors as $gift_form_error) : ?>
            <p>⚠ <?php echo esc_html($gift_form_error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php // Frontend gift form markup. ?>
<div class="loopis-form-wrapper">
    <form class="loopis-form" id="gift-form" action="" method="post" enctype="multipart/form-data">
        <?php wp_nonce_field('loopis_gift_form_action', 'loopis_gift_form_nonce'); ?>
        <input type="hidden" id="featured_image_index" name="featured_image_index" value="<?php echo esc_attr($featured_image_index); ?>">
        <?php if ($gift_form_is_edit_mode) : ?>
            <input type="hidden" name="edit_post_id" value="<?php echo esc_attr($gift_form_edit_post_id); ?>">
            <input type="hidden" id="gift-existing-images" value="<?php echo esc_attr(wp_json_encode($gift_form_existing_images)); ?>">
        <?php endif; ?>

            <div class="form-row">
                <label for="images">1⃣ Bilder</label>
                <button type="button" id="gift-images-button">Lägg till bilder</button>
                <input type="file" id="images" name="images[]" accept="image/*" multiple hidden>
                <p class="description">Du kan lägga till 1-3 bilder. Den med ⭐ visas i listor.</p>
                <div id="image-previews" aria-live="polite"></div>
            </div>

            <div class="form-row">
                <label for="post_title">2⃣ Rubrik</label>
                <input type="text" id="post_title" name="post_title" placeholder="Skriv en rubrik" value="<?php echo esc_attr($title_value); ?>" required>
                <p class="description">Ange tydligt vad du ger bort + storlek för kläder.</p>
            </div>

            <div class="form-row">
                <label for="post_content">3⃣ Beskrivning</label>
                <textarea id="post_content" name="post_content" placeholder="Skriv en beskrivning" rows="3" minlength="10" required><?php echo esc_textarea($content_value); ?></textarea>
                <p class="description">Beskriv med mått, märke, färg, skick etc.</p>
            </div>

            <div class="form-row" id="gift-terms-row">
                <label for="terms-search">4⃣ Kategori <span id="terms-count">0/3</span></label>
                <input type="search" id="terms-search" placeholder="Sök kategori..." autocomplete="off">
                <div id="terms-options" class="terms-options" role="group" aria-label="Kategorier">
                    <?php if (!empty($post_tags)) : ?>
                        <?php foreach ($post_tags as $tag) : ?>
                            <label class="term-option" data-term-label="<?php echo esc_attr($tag->name); ?>">
                                <input type="checkbox" name="terms[]" value="<?php echo esc_attr($tag->term_id); ?>" <?php checked(in_array((int) $tag->term_id, $selected_terms, true)); ?>>
                                <span><?php echo esc_html($tag->name); ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <p class="description">Välj 1-3 kategorier.</p>
                <p id="terms-error" class="error" hidden></p>
            </div>

            <div class="form-row">
                <label>5⃣ Överlämning</label>
                <label for="gift-exchange-locker">
                    <input type="radio" name="locker" id="gift-exchange-locker" value="1" <?php checked($selected_locker, 1); ?>>
                    Skåpet i Bagarmossen
                </label>
                <label for="gift-exchange-location">
                    <input type="radio" name="locker" id="gift-exchange-location" value="0" <?php checked($selected_locker, 0); ?>>
                    Annan adress
                </label>
                <p class="description">Skåpet rymmer saker som är upp till 80 cm långa.</p>
            </div>

            <div class="form-row" id="gift-location-wrapper" <?php echo 0 === $selected_locker ? '' : 'hidden'; ?>>
                <label for="custom_location">📍 Ange adress:</label>
                <input type="text" id="custom_location" name="custom_location" placeholder="Ange adress" maxlength="40" value="<?php echo esc_attr($custom_location_value); ?>">
                <p id="custom-location-error" class="error" hidden></p>
                <p class="description">Ange gatuadress eller plats (max 40 tecken).</p>
            </div>

            <?php if ($is_admin_user) : ?>
                <?php // Admin-only status selector. ?>
                <div class="admin-block">
                <div class="form-row">
                    <label for="cat">🐙 Admin</label>
                    <?php
                    wp_dropdown_categories(array(
                        'taxonomy'          => 'category',
                        'name'              => 'cat',
                        'id'                => 'cat',
                        'class'             => 'category-dropdown',
                        'hide_empty'        => false,
                        'selected'          => $selected_cat,
                        'show_option_none'   => 'Välj status',
                        'option_none_value'  => 0,
                    ));
                    ?>
                    <p class="description">Som admin kan du byta status (kategori) på annonsen.</p>
                </div>
                </div>
            <?php else : ?>
                <input type="hidden" name="cat" value="<?php echo esc_attr($selected_cat); ?>">
            <?php endif; ?>
        

            <div class="form-row">
                <button type="submit" name="submit_gift_post" value="1"><?php echo $gift_form_is_edit_mode ? 'Spara ändringar' : 'Publicera!'; ?></button>
            </div>
    </form>
</div>

<div id="gift-form-loading" class="loopis-form-loading" aria-hidden="true">
    <img class="loopis-form-loading-icon" src="<?php echo esc_url(LOOPIS_THEME_URI . '/assets/img/heart-green.svg'); ?>" alt="" aria-hidden="true">
    <span class="loopis-form-loading-text">Laddar...</span>
</div>