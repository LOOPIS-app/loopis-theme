<?php
/**
 * Shop overview page
 * 
 * Dynamic content of page-shop.php
 * Reached on /shop
 * 
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get current user roles
$current_user = wp_get_current_user();
$user_roles = (array) $current_user->roles;
?>

<h1>🛒 Shoppen</h1>
<hr>
<p class="small">💡 Vår avdelning för hantering av vanliga pengar</p>

<h3>Dina alternativ</h3>
<hr>
<?php if (in_array('member', $user_roles, true)) : ?>
<p><span class="big-link"><a href="<?php echo esc_url(add_query_arg('option', 'coins-stripe', home_url('/shop/'))); ?>">👛 Köp regnbågsmynt</a></span></p>
<?php endif; ?>

<?php if (in_array('member_pending', $user_roles, true)) : ?>
<p><span class="big-link"><a href="<?php echo esc_url(add_query_arg('option', 'membership-stripe', home_url('/shop/'))); ?>">👤 Köp medlemskap</a></span></p>
<?php endif; ?>