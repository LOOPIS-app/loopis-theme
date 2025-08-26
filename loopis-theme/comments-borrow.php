<div class="auto-respond">
<h3>Vill du låna?</h3>
<hr>
<?php if ( current_user_can('member') || current_user_can('administrator') ) { echo do_shortcode('[code_snippet id=16 php=true]'); ?>

<!-- NO ACCESS MESSAGE -->
<?php } else { echo do_shortcode('[code_snippet id=124 php]'); } ?>

</div><!--auto-respond-->
