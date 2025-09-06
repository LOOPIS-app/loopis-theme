/*
    Specific script for single.php in LOOPIS theme 
*/

jQuery(document).ready(function($) {
    // Store the original post image HTML
    var originalPostImageHtml = $('.post-image').html();

    // Handle click event on div with class "extra-image"
    $('.extra-image').click(function() {
        // Get the HTML of the clicked extra image
        var extraImageHtml = $(this).html();

        // Replace the HTML of the extra image div with the original post image HTML
        $(this).html(originalPostImageHtml);

        // Set the extra image HTML as the new post image HTML
        $('.post-image').html(extraImageHtml);

        // Update the original post image HTML
        originalPostImageHtml = extraImageHtml;
    });
});