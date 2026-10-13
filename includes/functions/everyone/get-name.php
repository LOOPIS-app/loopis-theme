<?php
/**
 * Helper functions for reading user names.
 */
function get_name(int $user_id){
    $first_name = get_user_meta($user_id, 'first_name', true) ?? '';
    $last_name = get_user_meta($user_id, 'last_name', true) ?? '';
    return ucfirst($first_name) . ' '.  ucfirst($last_name);
}