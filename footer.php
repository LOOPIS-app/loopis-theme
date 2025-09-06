</div><!--container-->
<?php include_once LOOPIS_THEME_DIR . '/assets/output/general/scroll-to-top.php'; ?>
<?php if (is_single() && (current_user_can('member') || current_user_can('administrator'))) { ?>
    <?php include_once LOOPIS_THEME_DIR . '/assets/output/user/support-form.php'; ?>
<?php } ?>
</div><!--wrapper-->

<footer id="footer">
<div class="footer-menu">
    <nav>
        <a href="/" class="footer-button">
        <span class="emoji">️🎁️</span>
        <span class="text">Saker att få</span>
        </a>
        <a href="/search/" class="footer-button">
        <span class="emoji">🔍️</span>
        <span class="text">Sök</span>
        </a>

        <a href="/submit/" class="footer-button">
        <span class="emoji">💚</span>
        <span class="text">Ge bort</span>
        </a>

<?php if (is_user_logged_in()) { ?>
        <a href="/profil/activity/" class="footer-button">
        <span class="emoji">🔔</span>
        <span class="text">Min aktivitet</span>
        </a>
<?php } else { ?>
    	<a href="/faq/" class="footer-button">
        <span class="emoji">💡</span>
        <span class="text">Hur funkar det?</span>
    </a>
<?php } ?>

<?php if (is_user_logged_in()) {
    if (current_user_can('administrator')) { ?>
        <a href="/admin/" class="footer-button">
        <span class="emoji">🐙️</span>
        <span class="text">Admin</span>
        </a>
    <?php } else { ?>
        <a href="/profile/" class="footer-button">
        <span class="emoji">👤️</span>
        <span class="text">Min profil</span>
        </a>
<?php } } else { ?>
    <a href="/logga-in/" class="footer-button">
        <span class="emoji">👤️</span>
        <span class="text">Logga in</span>
    </a>
<?php } ?>
    </nav>
</div>

</footer><!--footer-->
<?php wp_footer(); ?>
</body>
</html>