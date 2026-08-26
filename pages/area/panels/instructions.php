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

<h6>Har du problem med en annons?</h6>
<p>→ <span class="big-label white">🔔 pinga @admin</span> i en kommentar</p>

<h6>Har du frågor om LOOPIS eller skåpet?</h6>
<p>→ <span class="big-link white"><a href="<?php echo network_home_url( '/faq' ); ?>">💡 Vanliga frågor</a></span>
<span class="big-link white"><a href="<?php echo home_url( '/locker' ); ?>">⏹ Skåpet</a></span></p>

<h6>Har du övriga frågor och feedback?</h6>
<p>→ <span class="big-link white"><a href="<?php echo home_url( '/support' ); ?>">🛟 Supportforum</a></span></p>

</div>