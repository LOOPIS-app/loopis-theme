/**
 * Script for switching post images in single.php
 */

jQuery(document).ready(function($) {
  var thumbnailSelector = '.post-image-1, .post-image-2, .post-image-3';
  var selectedClass = 'is-selected';

  function setSelectedThumbnail($thumbnail) {
    $(thumbnailSelector).removeClass(selectedClass);
    $thumbnail.addClass(selectedClass);
  }

  // Featured image is preloaded in the large view, mirror that as active thumbnail.
  var $defaultSelected = $('.post-image-1').first();
  if ($defaultSelected.length === 0) {
    $defaultSelected = $(thumbnailSelector).first();
  }
  if ($defaultSelected.length) {
    setSelectedThumbnail($defaultSelected);
  }

  $(document).on('click', thumbnailSelector, function() {
    var $clicked = $(this);
    var clickedHtml = $(this).html();
    if (!clickedHtml || !$.trim(clickedHtml)) {
      return;
    }

    // One-way preview update: thumbnails remain static.
    $('.post-image').html(clickedHtml);
    setSelectedThumbnail($clicked);
  });
});