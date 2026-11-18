<?php
/** Validate the ordered image selection submitted by the gift form. */
if (!defined('ABSPATH')) {
    exit;
}

function loopis_gift_validate_image_selection($json, $existing_ids, $files, $featured_index) {
    $selection = is_string($json) ? json_decode($json, true) : null;
    if (!is_array($selection) || count($selection) < 1 || count($selection) > 3
        || array_keys($selection) !== range(0, count($selection) - 1)
        || $featured_index < 0 || $featured_index >= count($selection)) {
        return new WP_Error('invalid_images', 'Välj mellan en och tre bilder och försök igen.');
    }

    $seen_ids = array();
    $seen_uploads = array();
    foreach ($selection as $item) {
        if (!is_array($item) || count($item) !== 1) {
            return new WP_Error('invalid_images', 'Bildvalet är ogiltigt. Ladda om sidan och försök igen.');
        }
        if (isset($item['id']) && is_int($item['id']) && $item['id'] > 0
            && in_array($item['id'], $existing_ids, true) && !in_array($item['id'], $seen_ids, true)) {
            $seen_ids[] = $item['id'];
        } elseif (isset($item['upload']) && is_int($item['upload']) && $item['upload'] >= 0
            && array_key_exists($item['upload'], $files) && !in_array($item['upload'], $seen_uploads, true)) {
            $seen_uploads[] = $item['upload'];
        } else {
            return new WP_Error('invalid_images', 'Bildvalet är ogiltigt. Ladda om sidan och försök igen.');
        }
    }

    if (count($seen_uploads) !== count($files)) {
        return new WP_Error('invalid_images', 'Bildvalet är ogiltigt. Ladda om sidan och försök igen.');
    }
    return $selection;
}

/** Update image references without deleting attachments that may be shared. */
function loopis_gift_set_image_selection($post_id, $image_ids, $featured_index) {
    $previous = array(
        (int) get_post_thumbnail_id($post_id),
        (int) get_post_meta($post_id, 'image_2', true),
        (int) get_post_meta($post_id, 'image_3', true),
    );
    $featured = $image_ids[$featured_index];
    unset($image_ids[$featured_index]);
    $gallery = array_values($image_ids);
    $desired = array($featured, $gallery[0] ?? 0, $gallery[1] ?? 0);

    set_post_thumbnail($post_id, $desired[0]);
    foreach (array('image_2', 'image_3') as $index => $key) {
        if ($desired[$index + 1]) {
            update_post_meta($post_id, $key, $desired[$index + 1]);
        } else {
            delete_post_meta($post_id, $key);
        }
    }
    $actual = array(
        (int) get_post_thumbnail_id($post_id),
        (int) get_post_meta($post_id, 'image_2', true),
        (int) get_post_meta($post_id, 'image_3', true),
    );
    if ($actual === $desired) {
        return true;
    }

    if ($previous[0]) {
        set_post_thumbnail($post_id, $previous[0]);
    } else {
        delete_post_thumbnail($post_id);
    }
    foreach (array('image_2', 'image_3') as $index => $key) {
        if ($previous[$index + 1]) {
            update_post_meta($post_id, $key, $previous[$index + 1]);
        } else {
            delete_post_meta($post_id, $key);
        }
    }
    error_log('LOOPIS gift form: failed to update image references for post ' . $post_id . '; attempted to restore the previous selection.');
    return new WP_Error('image_update_failed', 'Bilderna kunde inte sparas. Kontrollera annonsen innan du försöker igen.');
}
