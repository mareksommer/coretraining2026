</main>
<footer class="site-footer">
    <div class="site-footer__inner">
        <p class="site-footer__copy">&copy; <?php echo date('Y'); ?> Coretraining. Všechna práva vyhrazena.</p>
        <nav class="site-footer__nav">
            <a href="<?php echo esc_url(home_url('/nase-dovednosti/')); ?>">Naše dovednosti</a>
            <a href="<?php echo esc_url(home_url('/podminky-pouzivani-sluzeb/')); ?>">Podmínky použití</a>
        </nav>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
