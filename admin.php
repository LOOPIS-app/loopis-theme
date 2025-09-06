<?php
/* Template Name: Admin Template */

// ACCESS CONTROL
if (current_user_can('loopis_admin')) { ?>

<?php get_template_part('header-admin'); ?>

<div class="admin-content">
    <div class="page-padding">

    <?php
    // Dynamic admin page loader
    $admin_dir = get_template_directory() . '/admin/';
    $slug = get_post_field('post_name', get_post());
    $php_file = $admin_dir . $slug . '.php';
    if (file_exists($php_file)) {
        include $php_file;
    } else {
        echo '<h1>' . get_the_title() . '</h1><hr>';
        the_content();
        // include get_template_directory() . '/admin/404.php';
    }
    ?>
    <div class="clear"></div>

    </div><!--page-padding-->
</div><!--admin-content-->

<?php get_template_part('footer-admin'); ?>

<!-- NO ACCESS -->
<?php } else { include_once LOOPIS_THEME_DIR . '/assets/output/access/admin-only.php'; } ?>