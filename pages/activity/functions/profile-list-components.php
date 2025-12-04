<?php
/**
 * List handling functions for LOOPIS user.
 *
 * Included in profil/posts.php
 * Included in profil/fetched.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** 
* Output title
*/	
function list_header_output($category_slug) {
    $category_slug = sanitize_text_field($category_slug); // Sanitize input
    switch ($category_slug) {
        case 'paused':
            echo '😎 Pausade annonser';
            break;
        case 'archived':
            echo '⭕ Arkiverade annonser';
            break;
        case 'removed':
            echo '❌ Borttagna annonser';
            break;
        case 'first':
            echo '🟢 Först till kvarn';
            break;
        case 'fetched':
            echo '✅ Lämnade saker';
            break;
        case 'forward':
            echo '☑ Hämtade saker';
            break;
        default:
            echo '💢 Status saknas';
            break;
    }
}

/** 
* Output instruction
*/	
function list_instruction_output($category_slug, $count) {
    if ($category_slug === 'paused') {
		echo '<p class="small">💡 Pausade annonser listas inte på LOOPIS och kan inte paxas.</p>';
        echo '<p>Tryck på <span class="label">🟢</span> för att aktivera en annons igen.</p>';
		echo '<p>Tryck på <span class="label">❌</span> för att ta bort en annons som inte är aktuell längre.</p>';
		if ($count > 2) {
		if (isset($_POST['unpause_ads'])) { action_unpause_all(get_current_user_id()); }
		echo '<form method="post" class="arb" action=""><button name="unpause_ads" type="submit" class="small" onclick="return confirm(\'Vill du aktivera alla annonser?\')">Aktivera alla</button></form><p class="info">Tryck på knappen för att aktivera alla dina pausade annonser.</p>'; }
	} elseif ($category_slug === 'archived') {
		echo '<p class="small">💡 När en annons är 4 veckor gammal arkiveras den automatiskt.</p>';
        echo '<p>Tryck på <span class="label">🟢</span> för att aktivera en annons igen.</p>';
		echo '<p>Tryck på <span class="label">❌</span> för att ta bort en annons.</p>';
        echo '<p class="small">⚠ Vänta gärna så länge som möjligt med att ta bort annonser. Plötsligt kanske en ny eller gammal medlem behöver det du ger bort. 🧘 </p>';
        if ($count > 2) {
		if (isset($_POST['extend_ads'])) { action_extend_all(get_current_user_id()); }
        echo '<form method="post" class="arb" action=""><button name="extend_ads" type="submit" class="small" onclick="return confirm(\'Vill du aktivera alla annonser?\')">Aktivera alla</button></form><p class="info">Tryck på knappen för att aktivera alla dina pausade annonser.</p>'; }
	} elseif ($category_slug === 'removed') {
		echo '<p class="small">💡 Här ser du dina borttagna annonser.</p>';	
        echo '<p>Tryck på <span class="label">🟢</span> för att aktivera en annons igen.</p>';
	} elseif ($category_slug === 'first') {
		echo '<p class="small">💡 Här är dina aktuella annonser som inte har paxats vid lottning.</p>';
        echo '<p>Tryck på <span class="label">❌</span> för att ta bort en annons.<p>';
        echo '<p class="small">PS. Vänta gärna så länge som möjligt med att ta bort annonser. Plötsligt behöver någon ny eller gammal medlem det du ger bort. 🧘 </p>';
    } elseif ($category_slug === 'fetched') {	
		echo '<p class="small">💡 Här är dina annonser som paxats och hämtats.</p>';
		echo '<p>Här finns inga alternativ för dig. Tack för att du loopar! 🙏</p>';
	} elseif ($category_slug === 'forward') {	
		echo '<p class="small">💡 Här visas alla saker du paxat och hämtat.</p>';
		echo '<p>Tryck på <span class="label">💝</span> för att skicka vidare!</p>';
	} else {
		echo '<p>För annonser med denna status finns ingen info.</p>';
	}
}

/** 
* Output buttons
*/	
function list_button_output($category_slug, $post_id) {
    // Sanitize inputs for safety
    $category_slug = sanitize_text_field($category_slug);
    $post_id = intval($post_id);

    if ($category_slug === 'paused') {
        // PHP logic
        if (isset($_POST['unpause' . $post_id])) { action_unpause($post_id); }
        if (isset($_POST['remove' . $post_id])) { action_remove($post_id); }

        // Output buttons
        echo <<<HTML
        <form method="post" class="arb" action="">
            <button name="unpause$post_id" type="submit" class="notif-button small grey" style="right:55px;" onclick="return confirm('Aktivera annonsen igen?')">🟢</button>
        </form>
		
        <form method="post" class="arb" action="">
            <button name="remove$post_id" type="submit" class="notif-button small grey" onclick="return confirm('Ta bort annonsen?')">❌</button>
        </form>
HTML;

	} elseif ($category_slug === 'archived') {
        // PHP logic
        if (isset($_POST['extend' . $post_id])) { action_extend($post_id); }
        if (isset($_POST['remove' . $post_id])) { action_remove($post_id); }

        // Output buttons
        echo <<<HTML
        <form method="post" class="arb" action="">
            <button name="extend$post_id" type="submit" class="notif-button small grey" style="right:55px;" onclick="return confirm('Aktivera annonsen igen?')">🟢</button>
        </form>
		
        <form method="post" class="arb" action="">
            <button name="remove$post_id" type="submit" class="notif-button small grey" onclick="return confirm('Ta bort annonsen?')">❌</button>
        </form>
HTML;

    } elseif ($category_slug === 'removed') {
        // PHP logic
        if (isset($_POST['unremove' . $post_id])) { action_unremove($post_id); }

        // Output buttons
        echo <<<HTML
        <form method="post" class="arb" action="">
            <button name="unremove$post_id" type="submit" class="notif-button small grey" onclick="return confirm('Aktivera annonsen igen?')">🟢</button>
        </form>
HTML;

    } elseif ($category_slug === 'first') {
        // PHP logic
        if (isset($_POST['remove' . $post_id])) { action_remove($post_id); }

        // Output buttons
        echo <<<HTML
        <form method="post" class="arb" action="">
            <button name="remove$post_id" type="submit" class="notif-button small grey" onclick="return confirm('Ta bort annonsen?')">❌</button>
        </form>
HTML;
		
    } elseif ($category_slug === 'forward') {
        // PHP logic
        if (isset($_POST['forward' . $post_id])) { action_forward($post_id); }

        // Output buttons
        echo <<<HTML
        <form method="post" class="arb" action="">
            <button name="forward$post_id" type="submit" class="notif-button small grey" onclick="return confirm('Skicka vidare?')">💝</button>
        </form>
HTML;

    }
}

/** 
* Output category
*/	
function list_category_output($category_slug) {
    if ($category_slug === 'fetched') {
        echo '✅ Lämnad';
    } elseif ($category_slug === 'forward') {
        echo '☑ Hämtad';
    } else {
        $category = $category_slug ? get_category_by_slug($category_slug) : null;
        if ($category) {
            echo esc_html($category->name);
        } else {
            echo '💢 Status saknas';
        }
    }
}