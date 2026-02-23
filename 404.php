<?php get_header(); ?>

<div class="content">
    <div class="page-padding">
        <h1>💢 Hoppsan!</h1>
        <hr>
        <p>Här fanns ingen sida...</p>
        <p><span><a href="javascript:history.back()"><i class="fas fa-chevron-left"></i> Gå tillbaka</a></span></p>
        
        <?php if (is_user_logged_in()) : ?>
            <div class="wpum-message information">
                <h5>Har du hittat ett fel i LOOPIS.app?</h5>
                <hr>
                <p>⬇ Berätta gärna hur du hamnade här i formuläret nedan!</p>
            </div>

            <div class="clear"></div>
            <?php get_template_part('templates/user/support/support-form'); ?>
            
        <?php endif; ?>
    </div><!--page-padding-->
</div><!--content-->

<?php get_footer(); ?>