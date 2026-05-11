/*
    Specific script for single.php in LOOPIS theme 
*/

jQuery(document).ready(function($) {
  $(document).on('click', '.extra-image', function() {
    var extraImageHtml = $(this).html();
    $(this).html($('.post-image').html());
    $('.post-image').html(extraImageHtml);
  });

  $(document).on('click', '.extra-image-2', function() {
    var extraImageHtml = $(this).html();
    $(this).html($('.post-image').html());
    $('.post-image').html(extraImageHtml);
  });
});