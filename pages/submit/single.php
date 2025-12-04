<?php
/* Formerly named submit-single.php and defined as template "Submit-Single Template" */
?>


<div class="columns"><div class="column1"><h1>🎁 Skapa annons</h1></div>
<div class="column2 bottom"><a href="javascript:history.back()" onclick="return confirm('Det du fyllt i försvinner.')"><i class="fas fa-times-circle"></i>Avbryt</a></div></div>
<hr>

<!-- Access? -->
<?php if ( current_user_can('member') || current_user_can('administrator') ) { ?>

<p class="small">
💡 Lägg bara upp <u>en sak i varje annons</u><br>
💡 Fota gärna med ren bakgrund<br>
💡 Lägg inte upp <a href="/faq/restriktioner">otillåtna annonser</a>
</p>

<!-- WPUM Frontend Posting -->
<?php echo do_shortcode('[wpum_post_form form_id="1"]'); ?>

<!-- No access -->
<?php } else { 
    include LOOPIS_THEME_DIR . '/templates/access/message.php';
	include LOOPIS_THEME_DIR . '/templates/visitor/templates/general/faq-single.php';
 } ?>

<div class="clear"></div>

</div><!--page-padding-->
</div><!--content-->

<!-- Script for changing submit button -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $('input[name="submit_registration"]').on('click', function(event) {
    var button = $(this);
    var form = button.closest('form');
    var requiredFields = form.find('input[required]');
    var allFieldsFilled = true;

    requiredFields.each(function() {
      if ($(this).val() === '') {
        allFieldsFilled = false;
        $(this).get(0).reportValidity(); // Show browser's validation message
        return false; // Exit the loop if any required field is empty
      }
    });

    if (!allFieldsFilled) {
      form[0].reset(); // Reset the form
    } else {
      button.val('Vänta...');
      button.addClass('waiting');
    }
  });
});
</script>