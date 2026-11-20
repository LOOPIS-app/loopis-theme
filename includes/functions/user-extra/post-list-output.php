<?php
/**
 * Functions for outputting lists of user posts.
 *
 * Included in:
 * pages/activity/posts-booked.php + posts-fetched.php + posts-submitted.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/** 
* Output title
*/	
function list_header_output($url_slug) {
    $url_slug = sanitize_text_field($url_slug); // Sanitize input
    switch ($url_slug) {
        case 'all':
            echo '💚 Mina annonser';
            break;
        case 'new':
            echo '⏳ Väntar på lottning';
            break;
        case 'old':
            echo '🟢 Väntar på paxning';
            break;
        case 'booked':
            echo '❤ Paxade annonser';
            break;
        case 'locker':
            echo '⏹ Lämnat i skåpet';
            break;
        case 'paused':
            echo '😎 Pausade annonser';
            break;
        case 'archived':
            echo '⭕ Arkiverade annonser';
            break;
        case 'removed':
            echo '❌ Borttagna annonser';
            break;
        case 'disappeared':
            echo '💢 Försvunna saker';
            break;
        case 'fetched':
            echo '✅ Lämnade saker';
            break;
        case 'others_fetched':
            echo '☑ Hämtade saker';
            break;
        case 'others_booked':
            echo '💞 Paxade saker';
            break;
        case '':
            echo '💚 Mina annonser';
            break;
        default:
            echo '💢 Status saknas';
            break;
    }
}

/** 
* Output instruction
*/	
function list_instruction_output($url_slug, $count) {
    global $user_ID; // Needed only for counting old posts that can be paused
    $url_slug = sanitize_text_field($url_slug);

    switch ($url_slug) {
        case 'all':
            echo '<p class="small">💡 Här är alla dina upplagda annonser.</p>';
            echo '<p>Tryck på en annons för mer info.<p>';
            break;
        
        case '':
            echo '<p class="small">💡 Här är alla dina upplagda annonser.</p>';
            echo '<p>Tryck på en annons för mer info.<p>';
            break;
        
        case 'new':
            echo '<p class="small">💡 Här är dina nya annonser som väntar på lottning.</p>';
            echo '<p>Tryck på en annons för mer info.<p>';
            break;

        case 'old':
            echo '<p class="small">💡 Här är dina aktuella annonser som inte paxades vid lottning.</p>';
            echo '<p>Tryck på <span class="label">❌</span> för att ta bort en annons.<p>';
            echo '<p class="small">PS. Vänta så länge som möjligt med att ta bort annonser. Plötsligt vill någon ny eller gammal medlem paxa! 🧘 </p>';
            break;

        case 'booked':
            echo '<p class="small">💡 Här är dina annonser som just nu är paxade.</p>';
            echo '<p>Tryck på en annons för mer info.<p>';
            break;

        case 'locker':
            echo '<p class="small">💡 Här är dina saker som just nu är i skåpet.</p>';
            echo '<p>Tryck på en annons för mer info.<p>';
            break;

        case 'paused':
            echo '<p class="small">💡 Här är dina pausade annonser.</p>';
            echo '<p>Ska du resa bort? Här kan du tillfälligt pausa dina annonser som är <span class="label">🟢 Först till kvarn</span></p>';
            echo '<p>Annonser som redan är paxade eller väntar på lottning kan inte pausas.</p>';
            // Output for paused ads
            if ($count > 0) {
                echo '<p>Tryck på <span class="label">🟢</span> för att aktivera en annons igen.</p>';
                echo '<p>Tryck på <span class="label">❌</span> för att ta bort en annons som inte är aktuell längre.</p>';
                if ($count > 2) {
                    if (isset($_POST['unpause_ads'])) { action_unpause_all(get_current_user_id()); }
                    echo '<form method="post" class="arb" action=""><button name="unpause_ads" type="submit" class="small" onclick="return confirm(\'Vill du aktivera alla annonser?\')">Aktivera alla</button></form><p class="info">Tryck på knappen för att aktivera alla dina pausade annonser.</p>';
                }
            }
            // Count old posts that can be paused
            $args = array(
                'author'     => $user_ID,
                'post_type'  => 'post',
                'cat'        => loopis_cats(['old']), 
                'posts_per_page' => -1,
                );
            $the_query = new WP_Query($args);
            $count_posts_old = $the_query->found_posts;
            // Count for old posts
            if ($count_posts_old > 0) {
                if (isset($_POST['pause_ads'])) { action_pause_all($user_ID); }
                echo '<form method="post" class="arb" action=""><button name="pause_ads" type="submit" class="yellow small" onclick="return confirm(\'Vill du pausa ' . $count_posts_old . ' annonser?\')">Pausa ' . $count_posts_old . ' annonser</button></form>';
                echo '<p class="info">Tryck på knappen för att pausa ' . $count_posts_old . ' aktiva annonser.</p>';
            }
            break;

        case 'archived':
            echo '<p class="small">💡 Här är dina arkiverade annonser.</p>';
            echo '<p>När en annons är 4 veckor gammal arkiveras den automatiskt.</p>';
            echo '<p>Tryck på <span class="label">🟢</span> för att aktivera en annons igen.</p>';
            echo '<p>Tryck på <span class="label">❌</span> för att ta bort en annons.</p>';
            echo '<p class="small">PS. Vänta så länge som möjligt med att ta bort annonser. Plötsligt vill någon ny eller gammal medlem paxa! 🧘 </p>';

            if ($count > 2) {
                if (isset($_POST['extend_ads'])) { action_extend_all(get_current_user_id()); }
                echo '<form method="post" class="arb" action=""><button name="extend_ads" type="submit" class="small" onclick="return confirm(\'Vill du aktivera alla annonser?\')">Aktivera alla</button></form><p class="info">Tryck på knappen för att aktivera alla dina pausade annonser.</p>';
            }
            break;

        case 'removed':
            echo '<p class="small">💡 Här är dina borttagna annonser.</p>';
            echo '<p>Tryck på <span class="label">🟢</span> för att aktivera en annons igen.</p>';
            break;
        
        case 'disappeared':
            echo '<p class="small">💡 Här är dina saker som försvunnit på vägen...</p>';
            echo '<p>Ibland händer det! 😯<p>';
            echo '<p>Tryck på en annons för mer info.<p>';
            break;

        case 'fetched':
            echo '<p class="small">💡 Här är dina saker som lämnats.</p>';
            echo '<p>Här finns inga alternativ för dig. Tack för att du loopar! 🙏</p>';
            break;

        case 'others_booked':
            echo '<p class="small">💡 Här är alla saker du just nu har paxade.</p>';
            echo '<p>Tryck på en annons för mer info.<p>';
            break;

        case 'others_fetched':
            echo '<p class="small">💡 Här är alla saker du hämtat.</p>';
            echo '<p>Tryck på <span class="label">💝</span> för att skicka vidare!</p>';
            break;

        default:
            echo '<p class="small">💡 För annonser med denna status finns ingen info.</p>';
            break;
    }
}

/** 
* Output buttons
*/	
function list_button_output($url_slug, $post_id) {
    // Sanitize inputs for safety
    $url_slug = sanitize_text_field($url_slug);
    $post_id = intval($post_id);

    if ($url_slug === 'old') {
        // PHP logic
        if (isset($_POST['remove' . $post_id])) { action_remove($post_id); }

        // Output buttons
        echo <<<HTML

        <form method="post" class="arb" action="">
            <button name="remove$post_id" type="submit" class="notif-button small grey" onclick="return confirm('Ta bort annonsen?')">❌</button>
        </form>
HTML;
    } else if ($url_slug === 'paused') {
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

	} elseif ($url_slug === 'archived') {
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

    } elseif ($url_slug === 'removed') {
        // PHP logic
        if (isset($_POST['unremove' . $post_id])) { action_unremove($post_id); }

        // Output buttons
        echo <<<HTML
        <form method="post" class="arb" action="">
            <button name="unremove$post_id" type="submit" class="notif-button small grey" onclick="return confirm('Aktivera annonsen igen?')">🟢</button>
        </form>
HTML;

    } elseif ($url_slug === 'others_fetched') {
        // PHP logic
        if (isset($_POST['forward' . $post_id])) { action_forward($post_id); }

        // Output buttons
        echo <<<HTML
        <form method="post" class="arb" action="">
            <button name="forward$post_id" type="submit" class="notif-button small grey" onclick="return confirm('Skicka vidare?')">💝</button>
        </form>
HTML;

    }elseif ($url_slug === 'mail_tips') {
    $taxonomy = 'admin-tag';
    $term = get_term_by('slug', 'mail_tips', $taxonomy);
    $button_name = 'toggle_mail_tips' . $post_id;

    if (!$term || is_wp_error($term)) {
        return;
    }
    if (isset($_POST[$button_name])) {
        check_admin_referer('toggle_mail_tips_' . $post_id);

        if (current_user_can('edit_post', $post_id)) {
            if (has_term($term->term_id, $taxonomy, $post_id)) {
                wp_remove_object_terms($post_id, $term->term_id, $taxonomy);
            } else {
                wp_set_object_terms($post_id, [$term->term_id], $taxonomy, true);
            }
        }
    }
    $has_mail_tips = has_term($term->term_id, $taxonomy, $post_id);
    $button_label = $has_mail_tips ? 'Ta bort från mailutskicket?' : 'Lägg till i mailutskicket?';
    $button_emoji = $has_mail_tips ? '❌' : '✅';
    echo '<form method="post" class="arb" action="">';
    wp_nonce_field('toggle_mail_tips_' . $post_id);
    echo '<button name="' . esc_attr($button_name) . '" type="submit" class="notif-button small grey"'
        . ' aria-label="' . esc_attr($button_label) . '"'
        . ' title="' . esc_attr($button_label) . '">'. $button_emoji.'</button>';
    echo '</form>';
}
}

/** 
* Adjusted output of category
*/	
function list_category_output($url_slug) {
    if ($url_slug === 'fetched') {
        echo '✅ Lämnad';
    } elseif ($url_slug === 'others_fetched') {
        echo '☑ Hämtad';
    } else {
        $category = $url_slug ? get_category_by_slug($url_slug) : null;
        if ($category) {
            echo esc_html($category->name);
        } else {
            echo '💢 Status saknas';
        }
    }
}