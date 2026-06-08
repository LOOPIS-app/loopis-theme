</div><!--container-->
<?php get_template_part('templates/general/scroll-to-top'); ?>

<!-- Support form for on all pages except support posts -->
<?php if ((current_user_can('member') || current_user_can('loopis_support')) && !is_singular('support')) : ?>
    <?php get_template_part('templates/forms/support-form'); ?>
<?php endif; ?>
<?php $site_url = home_url("/"); ?>

</div><!--wrapper-->

<footer id="footer">
    <nav>
            <a href="<?php echo $site_url ?>" class="footer-item">
                <span class="emoji">️🎁️</span>
                <span class="text">Saker att få</span>
            </a>
            <a href="<?php echo $site_url . '?s=' ?>" class="footer-item">
                <span class="emoji">🔍️</span>
                <span class="text">Sök</span>
            </a>

            <a href="<?php echo esc_url($site_url . 'submit/'); ?>" class="footer-item">
                <span class="emoji">💚</span>
                <span class="text">Ge bort</span>
            </a>

            <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo $site_url . 'activity/' ?>" class="footer-item">
                    <span class="emoji">🔔</span>
                    <span class="text">Min aktivitet</span>
                </a>
            <?php else : ?>
                <a href="<?php echo $site_url . 'faq/' ?>" class="footer-item">
                    <span class="emoji">💡</span>
                    <span class="text">Hur funkar det?</span>
                </a>
            <?php endif; ?>

            <?php if (is_user_logged_in()) : ?>
                <?php if (current_user_can('administrator')) : ?>
                    <a href="<?php echo $site_url . 'admin/' ?>" class="footer-item">
                        <span class="emoji">🐙️</span>
                        <span class="text">Admin</span>
                    </a>
                <?php else : ?>
                    <a href="<?php echo $site_url . 'profile/' ?>" class="footer-item">
                        <span class="emoji">👤️</span>
                        <span class="text">Min profil</span>
                    </a>
                <?php endif; ?>
            <?php else : ?>
                <a href="<?php echo $site_url . 'log-in/' ?>" class="footer-item">
                    <span class="emoji">👤️</span>
                    <span class="text">Logga in</span>
                </a>
            <?php endif; ?>
    </nav>
</footer><!--footer-->

<?php wp_footer(); ?>
</body>
</html>