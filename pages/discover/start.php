<?php
/**
 * Discover: START (page-discover.php)
 * Content overview.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>♻ Upptäck</h1>
<hr>
<p class="small">💡 Här finns olika sätt att hitta saker du behöver - eller vill ha 😻</p>

<?php 
// Output three random posts
get_template_part('templates/discover/random-posts');

// Insert spacer
insert_spacer(20);

// Output popular tags
get_template_part('templates/discover/popular-tags');