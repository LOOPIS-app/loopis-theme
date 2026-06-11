<?php
/**
 * Page for members and visitors in admin area.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<?php get_header(); ?>

<div class="page-padding">

<div class="columns"><div class="column1"><h1>🚧 Hoppsan!</h1></div>
	<div class="column2"></div></div> 
    <hr>

<div class="loopis-message information">
	<p>Du har inte behörighet att se denna sida.</p>
	<p><span class="big-link"><?php get_template_part('templates/links/go-back'); ?></span></p>
</div>

</div><!--page-padding-->

<?php get_footer(); ?>