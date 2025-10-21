/*
    Scripts for comments.php in LOOPIS theme
*/

// Add "@comment_author" to comment field
document.addEventListener('DOMContentLoaded', function() {
    // Get all the existing comments on the page
    var comments = document.querySelectorAll('.comment');

    // Iterate through each comment
    comments.forEach(function(comment) {
        // Get the <cite class="fn">Username</cite> element
        var usernameElement = comment.querySelector('.comment-author .fn');

        if (usernameElement) {
            // Get the username
            var username = usernameElement.textContent.trim();

            // Create a "Pinga" link element
            var pingLink = document.createElement('a');
            pingLink.className = 'comment-ping';
            pingLink.href = '#comment';
            pingLink.innerHTML = ' – <span class="small-link">🔔 Pinga</span>';

            // Insert the "ping" link after the username element
            usernameElement.parentNode.insertBefore(pingLink, usernameElement.nextSibling);

            // Add a click event listener
            pingLink.addEventListener('click', function(event) {
                event.preventDefault();

                // Get the comment field
                var commentField = document.getElementById('comment');

                if (commentField) {
                    // Get the current value of the comment field
                    var currentValue = commentField.value.trim();

                    // Add "@username " to the comment field if it's not already present
                    if (!currentValue.includes('@' + username)) {
                        var newValue = currentValue + ' @' + username + ' ';
                        commentField.value = newValue;
                    }
                }
            });
        }
    });
});

// Add "@post_author" to comment field
document.addEventListener('DOMContentLoaded', function() {
    var insertUsernameLink = document.getElementById('tag-author');
    var commentTextArea = document.getElementById('comment');

    if (insertUsernameLink && commentTextArea) {
        insertUsernameLink.addEventListener('click', function(event) {
            event.preventDefault();
            
            // Note: author username is passed from PHP to JavaScript
            var authorUsername = window.authorUsername || '@author'; // Fallback
            commentTextArea.value += authorUsername;
        });
    }
});

// Replace @ in comments with bell emoji
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var paragraphs = document.querySelectorAll('.commentlist .comment-body p');
        
        paragraphs.forEach(function (paragraph) {
            var html = paragraph.innerHTML;
            
            // Replace ALL @ symbols with 🔔, including those inside comment-mention links
            var newHTML = html.replace(/@/g, '🔔');
            
            paragraph.innerHTML = newHTML;
        });
        
    }, 200);
});