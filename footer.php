</div><!--container-->
<?php get_template_part('templates/general/scroll-to-top'); ?>

<?php if ((current_user_can('member') || current_user_can('administrator'))) : ?>
    <?php get_template_part('templates/user/support/support-form'); ?>
<?php endif; ?>

</div><!--wrapper-->

<footer id="footer">
    <div class="footer-menu">
        <nav>
            <a href="/" class="footer-button">
                <span class="emoji">️🎁️</span>
                <span class="text">Saker att få</span>
            </a>
            <a href="/?s=" class="footer-button">
                <span class="emoji">🔍️</span>
                <span class="text">Sök</span>
            </a>

            <a href="/submit/" class="footer-button">
                <span class="emoji">💚</span>
                <span class="text">Ge bort</span>
            </a>

            <?php if (is_user_logged_in()) : ?>
                <a href="/activity/" class="footer-button">
                    <span class="emoji">🔔</span>
                    <span class="text">Min aktivitet</span>
                </a>
            <?php else : ?>
                <a href="/faq/" class="footer-button">
                    <span class="emoji">💡</span>
                    <span class="text">Hur funkar det?</span>
                </a>
            <?php endif; ?>

            <?php if (is_user_logged_in()) : ?>
                <?php if (current_user_can('administrator')) : ?>
                    <a href="/admin/" class="footer-button">
                        <span class="emoji">🐙️</span>
                        <span class="text">Admin</span>
                    </a>
                <?php else : ?>
                    <a href="/profile/" class="footer-button">
                        <span class="emoji">👤️</span>
                        <span class="text">Min profil</span>
                    </a>
                <?php endif; ?>
            <?php else : ?>
                <a href="/log-in/" class="footer-button">
                    <span class="emoji">👤️</span>
                    <span class="text">Logga in</span>
                </a>
            <?php endif; ?>
        </nav>
    </div>
</footer><!--footer-->

<?php wp_footer(); ?>
</body>
</html>