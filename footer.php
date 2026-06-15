</div><!--container-->
<?php get_template_part('templates/general/scroll-to-top'); ?>

<!-- Support form for on all pages except support posts -->
<?php if ((current_user_can('member') || current_user_can('loopis_support')) && !is_singular('support')) : ?>
    <?php get_template_part('templates/forms/support-form'); ?>
<?php endif; ?>

</div><!--wrapper-->

<footer id="footer">
    <nav>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-item">
                <span class="emoji">️🎁️</span>
                <span class="text">Saker</span>
            </a>
            <a href="<?php echo esc_url(home_url('/?s=')); ?>" class="footer-item">
                <span class="emoji">🔍️</span>
                <span class="text">Sök</span>
            </a>

            <a href="<?php echo esc_url(home_url('/submit/')); ?>" class="footer-item">
                <span class="emoji">💚</span>
                <span class="text">Ge bort</span>
            </a>

            <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo esc_url(home_url('/activity/')); ?>" class="footer-item">
                    <span class="emoji">🔔</span>
                    <span class="text">Min aktivitet</span>
                </a>

                <a href="<?php echo esc_url(home_url('/user/')); ?>" class="footer-item">
                    <span class="emoji">👤️</span>
                    <span class="text">Min profil</span>
                </a>
                
            <?php else : ?>
                <a href="<?php echo esc_url(network_home_url( '/faq/' )); ?>" class="footer-item">
                    <span class="emoji">💡</span>
                    <span class="text">Hur funkar det?</span>
                </a>

                <a href="<?php echo esc_url(get_loopis_login_url()); ?>" class="footer-item">
                    <span class="emoji">👤️</span>
                    <span class="text">Logga in</span>
                </a>
            <?php endif; ?>
    </nav>

<?php if (current_user_can('loopis_admin') || current_user_can('manage_options')) : ?>
    <div class="footer-backdoor" onclick="location.href='<?php echo esc_url(home_url('/admin/')); ?>'">🐙</div>
<?php endif; ?>

</footer><!--footer-->

<?php wp_footer(); ?>
</body>
</html>