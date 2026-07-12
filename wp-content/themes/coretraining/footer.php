</main>

<footer class="site-footer">
    <div class="container site-footer__inner">
        <?php if (has_nav_menu('footer')) : ?>
            <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Patička', 'coretraining'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'site-footer__list',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ]);
                ?>
            </nav>
        <?php else : ?>
            <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Patička', 'coretraining'); ?>">
                <ul class="site-footer__list">
                    <li><a href="<?php echo esc_url(home_url('/kurzy/')); ?>">Kurzy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/clanky/')); ?>">Články</a></li>
                    <li><a href="<?php echo esc_url(home_url('/kontakt/')); ?>">Kontakt</a></li>
                    <li><a href="<?php echo esc_url(home_url('/ochrana-udaju/')); ?>">Ochrana údajů</a></li>
                </ul>
            </nav>
        <?php endif; ?>
        <p class="site-footer__copy">
            &copy; <?php echo esc_html((string) gmdate('Y')); ?>
            <a href="<?php echo esc_url(home_url('/')); ?>">CoreTraining</a>.
            <?php esc_html_e('Všechna práva vyhrazena.', 'coretraining'); ?>
        </p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
