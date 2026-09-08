<?php
/**
 * Support notification mail content template.
 * 
 * @param string $mail_intro The introductory text for the mail
 * @param string $mail_outro The concluding text for the mail
 * @param string $post_content The content of the post
 * @return string HTML wrapped content
 */

if (!defined('ABSPATH')) {
    exit;
}

function loopis_mail_template(string $mail_intro='', string|array $mail_outro='', string $post_content='', string $ingress=''): string {
    if(is_array($mail_outro)){
        $mail_outro = loopis_mail_template_outro_array($mail_outro);
    }
    $mail = '';
    if(!empty($mail_intro)){
        $mail .= '<h3>' . $mail_intro . '</h3>';
    }
    if(!empty($ingress)){
        $mail .= '<p>'.$ingress.'</p>';
    }
    if(!empty($post_content)){
        $mail .= '<p style="padding: 10px;font-size: 18px;font-style: italic;background: #f5f5f5;border-radius: 10px">' . $post_content . '</p>';
    }
    if(!empty($mail_outro)){
        $mail .= '<p style="font-size: 14px">' . $mail_outro . '</p>';
    }
    return $mail;
}

function loopis_mail_template_outro_array(array $outro_entries){
    return  implode('</p><p style="font-size: 14px">', $outro_entries);
}

function loopis_mail_ping(string $name = 'LOOPIS', string $post_link='/', string $post_title='från hemsidan'){
    return '<strong>'.$name.'</strong> pingade dig → <a href="'.$post_link.'">'.$post_title.'</a>';
}

function loopis_mail_location(string $location_name = 'Skåpet'){
    return '📍 Plats för överlämning: ' . $location_name;
}