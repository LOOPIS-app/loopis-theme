<?php
/**
 * Overview for discover pages
 *
 * Dynamic content of page-discover.php
 * 
 * Reached on /discover (this view is set as default)
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<h1>🧭 Hitta</h1>
<hr>
<p class="small">💡 Här finns olika sätt att hitta saker du behöver - eller vill ha. 😻</p>

<h3>🔍 Sök</h3>
<hr style="margin: 0px;">
<p class="small">💡 Sök bland alla saker att få.</p>
<?php get_template_part('templates/forms/search-form'); ?>

<?php 
// Output popular tags
get_template_part('templates/discover/popular-tags');

// Output three random posts
get_template_part('templates/discover/random-posts');  
?>

<h3><span class="desaturate">🚪</span> Skåpet?</h3>
<hr style="margin: 0px;">
<p class="small">💡 Var finns skåpet och hur funkar det?</p>
<p>Titta på sidan om <span class="mega-link"><a href="<?php echo home_url( '/locker' ); ?>">⏹️ Skåpet</a></span></p>