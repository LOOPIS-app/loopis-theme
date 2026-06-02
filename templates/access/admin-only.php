<?php
/**
 * Page for members and visitors in admin area.
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<?php get_header(); ?>

<div class="content">
	<div class="page-padding">

<div class="columns"><div class="column1"><h1>🚧 Hoppsan!</h1></div>
	<div class="column2 bottom"></div></div> 
    <hr>

<div class="loopis-message information">
	<p>Du har inte behörighet att se denna sida.</p>
	<?php include LOOPIS_THEME_DIR . '/templates/links/go-back.php'; ?>
</div>

	</div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>