<?php
/**
 * List of possible tips posts.
 * 
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$admin_url = home_url('/admin/');
require_once LOOPIS_THEME_DIR . '/includes/functions/admin-extra/update-setting.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mail-test'])) {
    if (
        !current_user_can('manage_options') ||
        !isset($_POST['send_mail_nonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['send_mail_nonce'])), 'send_mail')
    ) {
        wp_die('You are not allowed to send this email.');
    }

    $token = isset($_POST['mail_send_token'])
        ? sanitize_text_field(wp_unslash($_POST['mail_send_token']))
        : '';

    if (!$token || !add_option('mail_send_' . $token, 'processing', '', false)) {
        wp_die('This email submission has already been processed.');
    }

    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    $subject = sanitize_text_field(wp_unslash($_POST['subject'] ?? ''));
    $recipients = sanitize_text_field(wp_unslash($_POST['recipients'] ?? ''));
    if(!function_exists('loopis_send_en_masse_images')){
        loopis_get_mail();
    }
    
    if ($recipients === '1') {
        loopis_send_en_masse_images($message, $subject);
    } else {
        loopis_send_en_masse_images_test($message, $subject);
    }
    // Redirect so refreshing the result page won't resubmit the form.
    wp_safe_redirect(add_query_arg('mail_sent', '1', wp_get_referer()));
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mail-save'])) {
    if (
        !current_user_can('manage_options') ||
        !isset($_POST['send_mail_nonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['send_mail_nonce'])), 'send_mail')
    ) {
        wp_die('You are not allowed to save this email.');
    }

    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
    $subject = sanitize_text_field(wp_unslash($_POST['subject'] ?? ''));

    loopis_update_setting('mail_tips_subject',$subject);

    loopis_update_setting('mail_tips_message',$message);
  
    // Redirect so refreshing the result page won't resubmit the form.
    wp_safe_redirect(add_query_arg('mail_sent', '0', wp_get_referer()));
    exit;
}

$unsubscribed_ids = get_option('unsubscribed_mail_ids', []);
$unsubscribed_ids = is_array($unsubscribed_ids)
    ? array_map('absint', $unsubscribed_ids)
    : [];

$query = new WP_User_Query([
    'blog_id'     => get_current_blog_id(),
    'exclude'     => $unsubscribed_ids,
    'count_total' => true,
    'fields'      => 'ID',
]);

$user_count = $query->get_total();

$message = $_POST['message'] ?? loopis_get_setting('mail_tips_message');
$subject = $_POST['subject'] ?? loopis_get_setting('mail_tips_subject');
?>
   <h1>💌 Skicka tipsmail</h1>
   <hr>
    <p class="small">💡Skriv, se och skicka ett tipsmail</p>
    <p><span class="mega-link"><a href="<?php echo esc_url( add_query_arg('view', 'pages/tips', $admin_url) ); ?>">
        Markera tipsannonser till mejlet!
    </a></span></p>
    <div class="loopis-form-wrapper">
    <form id="email-form" class="loopis-form" method="post">
        <input type="hidden" name="mail_send_token"value="<?php echo esc_attr(wp_generate_uuid4()); ?>">
        <?php wp_nonce_field('send_mail', 'send_mail_nonce'); ?>
        <label>Mottagare</label>
        <select id="recipients" name="recipients">
            <option value="0">Du</option>
            <option value="1">Användare(<?php echo esc_textarea($user_count); ?> Personer)</option>
        </select>

        <label>Ämnesrad</label>
        <input type="text" id="subject" name="subject" value="<?php echo esc_textarea($subject); ?>" required maxlength="150"> 

        <label>Medelande</label>
        <textarea id="message" name="message" style="height: 500px ; width: 400px ;" value="" required><?php echo esc_textarea($message); ?></textarea>

        <div class="actions">
            <button type="button" id="preview-button">Förhandsvisa</button>
            <button type="submit" class="blue" id="mail-test" name="mail-save" value="0">Spara</button>
            <button type="submit" class="red" id="mail-test" name="mail-test" value="1">Skicka</button>
        </div>

        <p class="status" id="status" role="status" aria-live="polite"></p>
    </form>
    </div>

    <section class="preview" id="preview" aria-labelledby="preview-heading" hidden>
        <h2 id="preview-heading">Mejl Förhandsvisning</h2>
        <p><strong>Till:</strong> <span id="preview-recipients"></span></p>
        <p><strong>Subjekt:</strong> <span id="preview-subject"></span></p>
        <hr>
        <div style="max-width: 500px; margin: 0 auto; padding: 5px; text-align: center; font-size: 16px;">
        <div style="margin: 10px;"><img src="https://loopis.app/wp-content/themes/loopis-theme/assets/img/LOOPIS_mail_header.png" alt="LOOPIS logotyp" width="240" /></div>
        <div id="preview-message" style="white-space: pre-wrap;"></div>
        </div>
        <div style="max-width: 240px; margin: 0px auto; padding: 0px;"></div>
        <div style="margin: 0 0 20px 0; padding: 0px; background: #f5f5f5; max-width: 240px; margin-left: auto; margin-right: auto;">
        <?php
        $taxonomy = 'admin-tag';
        $term = get_term_by('slug', 'mail_tips', $taxonomy);

        if ($term && !is_wp_error($term)) {
            $posts = get_posts([
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'tax_query'      => [[
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => 'mail_tips',
                ]],
            ]);

            foreach ($posts as $post) {
                $thumbnail = get_the_post_thumbnail(
                    $post->ID,
                    'medium',
                    [
                        'alt'   => get_the_title( $post->ID ),
                        'style' => 'display:block;width:240px;height:240px;object-fit:cover;',
                    ]
                );
                $name = esc_html($post->post_title ?? '');
                $cat = array_values(get_the_category($post->ID));
                $cat_name = esc_html(!empty($cat) ? $cat[0]->name : '');

                echo '<div style="height: 240px; width: 240px;">'.$thumbnail.'</div>
                <div style="padding: 7px;">
                <p style="margin: 0 0 5px 0; font-size: 24px; color: #1e73be;">'.$name.'</p>
                <p style="margin: 0px; font-size: 15px; color: #999999;">'.$cat_name.'</p>

                </div>';
            }
        }
        ?>
        </div>
        <div style="margin: 0 auto; padding: 0px; text-align: center; font-size: 16px;">

        Se alla annonser → <a href="https://loopis.app">LOOPIS.app</a>

        <hr style="border: none; border-top: 5px dotted; border-color: #eee;" />

        <img src="https://loopis.app/wp-content/themes/loopis-theme/assets/img/LOOPIS_mail_footer.png" alt="" width="300" />

        </div>
    </section>
    <script>
    const form = document.querySelector("#email-form");
    const preview = document.querySelector("#preview");
    const status = document.querySelector("#status");

    document.querySelector("#preview-button").addEventListener("click", () => {
        if (!form.reportValidity()) return;

        const recipientSelect = document.querySelector("#recipients");
        document.querySelector("#preview-recipients").textContent =
            recipientSelect.options[recipientSelect.selectedIndex].text;
        document.querySelector("#preview-subject").textContent =
            document.querySelector("#subject").value;
        document.querySelector("#preview-message").textContent =
            document.querySelector("#message").value;

        preview.hidden = false;
        status.textContent = "Förhandsvisning updaterad.";
        document.querySelector("#preview-heading").focus();
    });

document.getElementById('email-form').addEventListener('submit', function (event) {
    const recipients = document.getElementById('recipients');
    const button = event.submitter;
    if (button?.value === "1") {
        const message = recipients.value === '0'
            ? 'Skicka till dig själv?'
            : 'Skicka till <?php echo esc_js($user_count); ?> användare?';

        if (!confirm(message)) {
            event.preventDefault();
        }
    }
});
  </script>