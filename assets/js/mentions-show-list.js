/*
    Scripts for showing mentions in list of comments
*/

// Replace @ in comments with bell emoji
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var paragraphs = document.querySelectorAll('.post-list-post .post-list-post-comment p');
        
        paragraphs.forEach(function (paragraph) {
            var html = paragraph.innerHTML;
            
            // Replace ALL @ symbols with 🔔, including those inside comment-mention links
            var newHTML = html.replace(/@/g, '🔔');
            
            paragraph.innerHTML = newHTML;
        });
        
    }, 200);
});