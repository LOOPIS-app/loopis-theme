<?php
/**
* Compact output of the three 'faq' examples
**/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!--Output-->
<div class="attention-block">

<h6 style="font-weight: 400;">Problem med en annons?</h6>
<p>→ <span class="mega-link white"><a href="<?php echo esc_url( add_query_arg('view', 'admin', home_url('/area/')) ); ?>">🔔 pinga @admin</a></span><br><span class="small">i en kommentar på annonsen.</span></p>

<h6 style="font-weight: 400;">Frågor om skåpet?</h6>
<p>→ <span class="mega-link white"><a href="<?php echo home_url( '/locker' ); ?>">⏹ Skåpet</a></span></p>

<h6 style="font-weight: 400;">Frågor om LOOPIS?</h6>
<p>→ <span class="mega-link white"><a href="<?php echo network_home_url( '/faq' ); ?>">💡 Vanliga frågor</a></span></p>

<h6 style="font-weight: 400;">Övriga frågor och feedback?</h6>
<p>→ <span class="mega-link white"><a href="<?php echo home_url( '/support' ); ?>">🛟 Supportforum</a></span></p>

</div>