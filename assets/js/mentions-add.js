/*
    Scripts for creating mentions in comments
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
            var username = usernameElement.dataset.login || usernameElement.textContent.trim();

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
                    var mention = '@' + username;
                    var start = commentField.selectionStart;
                    var end = commentField.selectionEnd;
                    // Add "@username " to the comment field if it's not already present
                    if (!currentValue.includes(mention)) {
                        var before = currentValue.slice(0,start);
                        var after = currentValue.slice(end);

                        var addition = (before && !/\s$/.test(before) ? ' ' : '') + mention + (after && !/^\s/.test(after) ? ' ' : ' ');

                        commentField.setRangeText(addition,start,end,'end');
                        commentField.focus();
                    }
                }
            });
        }
    });
});

// Add "@post_author" to comment field
document.addEventListener('DOMContentLoaded', function() {
    var insertUsernameLink = document.getElementById('tag-author');
    var commentField = document.getElementById('comment');

    if (insertUsernameLink && commentField) {
        insertUsernameLink.addEventListener('click', function(event) {
            event.preventDefault();

            // Note: author username is passed from PHP to JavaScript
            var username = window.authorUsername || 'author'; // Fallback
            
            // Get the current value of the comment field
            var currentValue = commentField.value.trim();
            var mention = '@' + username;
            var start = commentField.selectionStart;
            var end = commentField.selectionEnd;
            // Add "@username " to the comment field if it's not already present
            if (!currentValue.includes(mention)) {
                var before = currentValue.slice(0,start);
                var after = currentValue.slice(end);

                var addition = (before && !/\s$/.test(before) ? ' ' : '') + mention + (after && !/^\s/.test(after) ? ' ' : ' ');

                commentField.setRangeText(addition,start,end,'end');
                commentField.focus();
            }
        });
    }
});

// Add "@admin" to comment field
document.addEventListener('DOMContentLoaded', function() {
    var insertAdminLink = document.getElementById('tag-admin');
    var commentField = document.getElementById('comment');

    if (insertAdminLink && commentField) {
        insertAdminLink.addEventListener('click', function(event) {
            event.preventDefault();

            var username = window.adminUsername || 'admin';

            // Get the current value of the comment field
            var currentValue = commentField.value.trim();
            var mention = '@' + username;
            var start = commentField.selectionStart;
            var end = commentField.selectionEnd;
            // Add "@username " to the comment field if it's not already present
            if (!currentValue.includes(mention)) {
                var before = currentValue.slice(0,start);
                var after = currentValue.slice(end);

                var addition = (before && !/\s$/.test(before) ? ' ' : '') + mention + (after && !/^\s/.test(after) ? ' ' : ' ');

                commentField.setRangeText(addition,start,end,'end');
                commentField.focus();
            }
        });
    }
});


// Add "💚 Admin" to comment field
document.addEventListener('DOMContentLoaded', function() {
    var insertAdminLink = document.getElementById('sign-admin');
    var commentTextArea = document.getElementById('comment');

    if (insertAdminLink && commentTextArea) {
        insertAdminLink.addEventListener('click', function(event) {
            event.preventDefault();

            var heartAdmin = '💚 Admin';
            var currentValue = commentTextArea.value.trim();

            commentTextArea.value = currentValue ? currentValue + '\n' + heartAdmin + ' ' : heartAdmin + ' ';
        });
    }
});