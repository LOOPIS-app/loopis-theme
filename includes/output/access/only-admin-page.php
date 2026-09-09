<?php
/**
 * Page for members and visitors in admin area
 * 
 * Complete page including header and footer!
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<?php get_header(); ?>

<div class="page-padding">

<h1>💢 Hoppsan!</h1>
<hr>

<div class="loopis-message information">
	<p>🚧 Du har inte behörighet att se denna sida.</p>
	<p><span class="big-link"><?php get_template_part('templates/links/go-back'); ?></span></p>
</div>

</div><!--page-padding-->

<?php get_footer(); ?>