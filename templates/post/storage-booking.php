<?php
/**
 * Show interaction for post category 'storage'.
 *
 * Included in post-actions.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Extra functions
include_once LOOPIS_THEME_DIR . '/functions/user-extra/post-action-storage.php';
?>

<div class="admin-block">
    <?php include_once LOOPIS_THEME_DIR . '/templates/admin/links/admin-label.php'; ?>
    <?php $event_name = function_exists('loopis_get_setting') ? loopis_get_setting('event_name', '📍 Inget event angivet') : '📍 Inget event angivet'; ?>
    <p class="small">💡 Du har behörighet att markera hämtning på <span class="small-label"><?php echo $event_name; ?></span></p>
    <?php $member_users = get_users(array('role' => 'member')); ?>	
    
    <form method="post" class="arb" action="" style="display: flex; align-items: center;">
        <div style="position: relative; margin-right: 10px;">
            <input type="text" 
                   id="member_search" 
                   name="member_search"
                   placeholder="❤ Sök medlem..."
                   autocomplete="off"
                   style="max-width: 185px;">
            <input type="hidden" id="selected_member" name="selected_member" value="">
            
            <!-- Search results dropdown -->
            <div id="search_results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; z-index: 1000; display: none;">
            </div>
        </div>
        
        <button name="book_storage" type="submit" class="blue" style="display:none;">☑ Hämtad</button>
    </form>
    <p class="info">Skriv namnet på den medlem som hämtar.</p>
        
    <?php if(isset($_POST['book_storage']) && isset($_POST['selected_member'])) { $user_id = $_POST['selected_member']; admin_action_book_storage($user_id, $post_id); } ?>

    <script>
    // Get all members for search
    var members = [
        <?php foreach ($member_users as $member_user) { ?>
        {id: <?php echo $member_user->ID; ?>, name: "<?php echo esc_js($member_user->display_name); ?>"},
        <?php } ?>
    ];
    
    var searchInput = document.getElementById('member_search');
    var hiddenInput = document.getElementById('selected_member');
    var resultsDiv = document.getElementById('search_results');
    var bookButton = document.querySelector('button[name="book_storage"]');
    
    // Search function
    searchInput.addEventListener('input', function() {
        var query = this.value.toLowerCase();
        resultsDiv.innerHTML = '';
        
        if (query.length < 1) {
            resultsDiv.style.display = 'none';
            hiddenInput.value = '';
            bookButton.style.display = 'none';
            return;
        }
        
        var matches = members.filter(function(member) {
            return member.name.toLowerCase().includes(query);
        });
        
        if (matches.length > 0) {
            resultsDiv.style.display = 'block';
            matches.forEach(function(member) {
                var div = document.createElement('div');
                div.textContent = member.name;
                div.style.padding = '8px 12px';
                div.style.cursor = 'pointer';
                div.style.borderBottom = '1px solid #eee';
                
                div.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f5f5f5';
                });
                
                div.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'white';
                });
                
                div.addEventListener('click', function() {
                    searchInput.value = member.name;
                    hiddenInput.value = member.id;
                    resultsDiv.style.display = 'none';
                    bookButton.style.display = 'inline-block';
                });
                
                resultsDiv.appendChild(div);
            });
        } else {
            resultsDiv.style.display = 'none';
        }
    });
    
    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.style.display = 'none';
        }
    });
    
    // Clear selection if input is manually changed
    searchInput.addEventListener('keydown', function() {
        hiddenInput.value = '';
        bookButton.style.display = 'none';
    });
    </script>
</div>