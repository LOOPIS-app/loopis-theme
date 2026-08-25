<?php
/**
 * Content for locker page using url /locker
 */

get_header(); ?>

    <div class="page-padding">

        <h1>⏹ Skåpet</h1>
        <hr>
        <p class="small">💡 Information om skåpet i ditt område: <?php echo get_bloginfo('name'); ?></p>

        <!-- Output the content of the page -->
        <?php the_content(); ?>

        <!-- Output locker location -->
        <h3>🗺 Plats</h3>
        <hr>
        Här ser du skåpet på Google Maps:
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2033.0719349830272!2d18.128128941534737!3d59.27650217469448!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x465f790b7c0dd7b3%3A0x14de0966cfb56a11!2sLOOPIS!5e1!3m2!1sen!2sse!4v1783456249669!5m2!1sen!2sse" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
        <span class="big-link"><a href="https://maps.apple.com/p/obkTXRctGFhBHQ"><i class="fas fa-share"></i>Länk till Apples Kartor</a></span>

        <!-- Output locker photo -->
        <?php if (has_post_thumbnail()) : ?>
        <h3>🖼 Bild</h3>
        <hr>
            <div class="featured-image">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>
    
        <!-- Report problems -->
        <h3>🔥 Problem?</h3>
        <hr>
        <p>Kolla uppdateringar eller rapportera problem i <span class="big-link"><a href="<?php echo home_url( '/news' ); ?>">📡 Nyheter</a></span> och <span class="big-link"><a href="<?php echo home_url( '/forum' ); ?>">🗣 Forum</a></span></p>

        <!-- Lost items -->
        <h3>🫨 Saker på vift</h3>
        <hr>
        <!-- Things disappeared -->
        <div class="wrapped link" style="max-width: 500px;" onclick="location.href='<?php echo get_home_url( null, '/category/disappeared/' ); ?>'">
            <h5>💢 Försvunna saker</h5>
            <?php include LOOPIS_THEME_DIR . '/pages/area/panels/disappeared-latest.php'; ?>
        </div>

        <!-- Things cleaned out -->
        <div class="wrapped link" style="max-width: 500px;" onclick="location.href='<?php echo get_home_url( null, '/category/extracted/' ); ?>'">
            <h5>🧹 Bortplockade saker</h5>
            <?php include LOOPIS_THEME_DIR . '/pages/area/panels/extracted-latest.php'; ?>
        </div>

    </div><!--page-padding-->

<?php get_footer(); ?>