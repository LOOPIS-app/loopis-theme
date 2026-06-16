</div><!--container-->
<?php get_template_part('templates/general/scroll-to-top'); ?>

</div><!--wrapper-->

<footer id="footer" class="footer-admin">
    <nav>
        <a href="<?php echo  esc_url(home_url('/admin/')); ?>" class="footer-item">
        <span class="emoji">🐙</span>
        <span class="text">Översikt</span>
        </a>

        <a href="<?php echo esc_url( add_query_arg('view', 'traffic-gifts', home_url('/admin/')) ); ?>" class="footer-item">
        <span class="emoji">⏰</span>
        <span class="text">Påminnelser</span>
        </a>
        
        <a href="<?php echo esc_url( add_query_arg('view', 'stats', home_url('/admin/')) ); ?>" class="footer-item">
        <span class="emoji">📊</span>
        <span class="text">Statistik</span>
        </a>

        <a href="<?php echo esc_url( add_query_arg('view', 'support', home_url('/admin/')) ); ?>" class="footer-item">
        <span class="emoji">🛟</span>
        <span class="text">Support</span>
        </a>
        
        <a href="<?php echo esc_url( add_query_arg('view', 'storage', home_url('/admin/')) ); ?>" class="footer-item">
        <span class="emoji">📦</span>
        <span class="text">Lager</span>
        </a>
    </nav>

<div class="footer-backdoor" onclick="location.href='<?php echo esc_url(home_url('/')); ?>'">🚪</div>

</footer><!--footer-->
<?php wp_footer(); ?>

</body>
</html>