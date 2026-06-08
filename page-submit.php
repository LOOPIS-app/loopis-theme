<?php
/**
 * Dynamic content for pages using url /submit/?option=
 */

get_header(); ?>

<?php 
// Get current user roles
$current_user = wp_get_current_user();
$user_roles = (array) $current_user->roles;
?>

<div class="page-padding">

<?php if (in_array('member', $user_roles, true) || in_array('administrator', $user_roles, true)) : 

    // Member: Dynamic page loader
    $page_dir = get_template_directory() . '/pages/submit/';

    // Get the 'option' parameter from URL
    $page_option = isset($_GET['option']) ? sanitize_file_name($_GET['option']) : 'start';

    $php_file = $page_dir . $page_option . '.php';

    if (file_exists($php_file)) {
        include $php_file;
    } else {
        echo '<h1>💚 Ge bort</h1><hr>';
        echo '<p>💢 Filen hittades inte: <b>' . esc_html($php_file) . '</b></p>';
    }

    // Pending member
     elseif (in_array('member_pending', $user_roles, true)) :
        echo '<h1>💚 Ge bort</h1><hr>';
        include LOOPIS_THEME_DIR . '/templates/access/member-only.php';
        include LOOPIS_THEME_DIR . '/templates/faq/questions-visitor.php';
        get_footer();

    // Not logged in
    else :
        echo '<h1>💚 Ge bort</h1><hr>';
        include LOOPIS_THEME_DIR . '/templates/access/logged-in-only.php';
        include LOOPIS_THEME_DIR . '/templates/faq/questions-visitor.php';
        get_footer();
    
    endif; ?>

<!-- Closing div for page-padding + footer is inserted in the dynamic content files, to avoid footer on gift form. -->