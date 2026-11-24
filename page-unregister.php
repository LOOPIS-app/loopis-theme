<?php
/**
 * Page which unregisters user.
 * 
 * 
 * @package LOOPIS_Theme
 * @subpackage Frontend
 */

get_header();
$user_id = absint( $_GET['uid'] ?? $_POST['uid'] ?? 0 );
$token   = sanitize_text_field(
    wp_unslash( $_GET['token'] ?? $_POST['token'] ?? '' )
);

if (
    ! $user_id ||
    ! $token ||
    ! hash_equals( loopis_unsubscribe_token( $user_id ), $token )
) {
    return '<p>This link is invalid.</p>';
}

if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
    $nonce = sanitize_text_field(
        wp_unslash( $_POST['_wpnonce'] ?? '' )
    );

    if ( ! wp_verify_nonce( $nonce, 'loopis_unsubscribe_' . $user_id ) ) {
        return false;
    }

    if ( ! get_userdata( $user_id ) ) {
        return false;
    }
    
    loopis_unsubscribe_user($user_id);
        $home_url = add_query_arg(
        'mail_unsubscribed',
        '1',
        home_url( '/' )
    );

    wp_safe_redirect( $home_url );
    exit;
}

$action_url = add_query_arg(
    [
        'uid'   => $user_id,
        'token' => $token,
    ],
    get_permalink()
);
?>
    <div class="page-padding">
   <h1> 💌 Avprenumerera på tipsmejlet</h1>
   <hr>    
   <p class="small">💡Du kan prenumerera på nytt under min aktivitet -> inställningar</p>
        <div class="loopis-form-wrapper">
    <form class="loopis-form" method="post" action="<?php echo esc_url( $action_url ); ?>">
        <input type="hidden" name="uid" value="<?php echo esc_attr( $user_id ); ?>">
        <input type="hidden" name="token" value="<?php echo esc_attr( $token ); ?>">
        <?php wp_nonce_field( 'loopis_unsubscribe_' . $user_id ); ?>
        <label>Klicka här för att avprenumerera på tipsmejlet:</label>
        <button type="submit">Avprenumerera</button>
    </form>



        <div class="clear"></div>
    </div>
    </div><!--page-padding-->

<?php get_footer(); ?>

