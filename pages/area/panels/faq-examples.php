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
<div class="wrapped link" style="min-width:250px" onclick="location.href='<?php echo network_home_url( '/faq' ); ?>'">
    <h5>💡 Vanliga frågor</h5>
<p class="small">↓ 3 exempel<span class="right blue">Se alla →</span></p>
<hr>
<p class="small">📌 Hur funkar lottning?</p>
<p class="small">📌 Hur funkar regnbågsmynt?</p>
<p class="small">📌 LOOPIS på hemskärmen?</p>
</div>