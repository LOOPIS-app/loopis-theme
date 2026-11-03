<?php
/**
 * Show roles and users with access to the admin area of sub site
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<h1>🚧 Tillgång</h1>
<hr>
<p>💡 Roller och användare med tillgång till adminpanelen.</p>

<h3>🤓 Local admin (manager)</h3>
<hr>
<p>
<?php
$users = get_users(array('role' => 'manager'));
foreach ($users as $user) {
    $author_link = get_author_posts_url($user->ID);
    echo '<span class="big-link"><a href="' . esc_url($author_link) . '">👤 ' . esc_html($user->display_name) . '</a></span> &nbsp;';
}
?>
</p>

<h3>🕵 Styrelse (board)</h3>
<hr>
<p>
<?php
$users = get_users(array('role' => 'board'));
foreach ($users as $user) {
    $author_link = get_author_posts_url($user->ID);
    echo '<span class="big-link"><a href="' . esc_url($author_link) . '">👤 ' . esc_html($user->display_name) . '</a></span> &nbsp;';
}
?>
</p>

<h3>🧑‍💻 Develooper (develooper)</h3>
<hr>
<p>
<?php
$users = get_users(array('role' => 'develooper'));
foreach ($users as $user) {
    $author_link = get_author_posts_url($user->ID);
    echo '<span class="big-link"><a href="' . esc_url($author_link) . '">👤 ' . esc_html($user->display_name) . '</a></span> &nbsp;';
}
?>
</p>

<h3>👽 WP-Admin (administrator)</h3>
<hr>
<p>
<?php
$users = get_users(array('role' => 'administrator'));
foreach ($users as $user) {
    $author_link = get_author_posts_url($user->ID);
    echo '<span class="big-link"><a href="' . esc_url($author_link) . '">👽 ' . esc_html($user->display_name) . '</a></span> &nbsp;';
}
?>
</p>
