<?php
/**
 * Send email to all managers when user submits a new support post.
 *
 * Included in support-form.php
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include mail templates
require_once get_template_directory() . '/templates/mail/mail-template.php';
require_once get_template_directory() . '/templates/mail/mail-headers.php';
require_once get_template_directory() . '/templates/mail/mail-footer.php';

function send_support_notification(int $post_id) {

    // Get data for email
    $post_author_id = get_post_field('post_author', $post_id);
    $post_author_name = get_userdata($post_author_id)->display_name;
    $post_author_email = get_userdata($post_author_id)->user_email;
    $post_author_link = get_author_posts_url($post_author_id);
    $post_link = get_permalink($post_id);
    $post_content = get_post_field('post_content', $post_id);
    $post_title = html_entity_decode(
        html_entity_decode(get_the_title($post_id), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        ENT_QUOTES | ENT_HTML5,
        'UTF-8'
    );

    // Set mail intro and outro
    $mail_intro = $post_author_name . " (" . $post_author_email . ") har skapat ett supportärende:";
    $mail_outro = "Se ärendet och svara här → <a href='".$post_link."'>".$post_title."</a>";

    // Use mail templates to set mail content
    $mail_content = loopis_mail_template($mail_intro, $mail_outro, $post_content);

    // Add mail footer
    $mail_content .= loopis_mail_footer('manager');

    // Set mail subject
    $mail_subject = $post_title;

    // Set mail headers
    $mail_headers = loopis_mail_headers();

    // Send email to all managers
    $recipients = get_users(
        array(
            'role'     => 'manager',
            'fields'   => array( 'ID', 'user_email' ),
        )
    );

    if ( empty( $recipients ) ) {
        return;
    }

    foreach ( $recipients as $user ) {
        $recipient = $user->user_email;

        if ( ! is_email( $recipient ) ) {
            continue;
        }

        wp_mail( $recipient, $mail_subject, $mail_content, $mail_headers );
    }
}