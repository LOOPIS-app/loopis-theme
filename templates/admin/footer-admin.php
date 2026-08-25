</div><!--container-->
<?php get_template_part('templates/general/scroll-to-top'); ?>

</div><!--wrapper-->

<?php $admin_url = home_url('/admin/'); ?>

<footer id="footer" class="footer-admin">
    <nav>
        <a href="<?php echo  esc_url($admin_url); ?>" class="footer-item">
        <span class="emoji">🦀</span>
        <span class="text">Översikt</span>
        </a>
        
        <a href="<?php echo esc_url( add_query_arg('view', 'more/post-search', $admin_url) ); ?>" class="footer-item">
        <span class="emoji">🔍</span>
        <span class="text">Sök</span>
        </a>
                
        <a href="<?php echo esc_url( add_query_arg('view', 'storage', $admin_url) ); ?>" class="footer-item">
        <span class="emoji">📦</span>
        <span class="text">Lager</span>
        </a>
    </nav>

<div class="footer-backdoor" onclick="location.href='<?php echo esc_url(home_url('/')); ?>'">🚪</div>

</footer><!--footer-->
<?php wp_footer(); ?>

</body>
</html>