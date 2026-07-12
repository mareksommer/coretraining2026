<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e('Přeskočit na obsah', 'coretraining'); ?></a>

<header class="site-header">
    <div class="container site-header__inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-header__brand" rel="home">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <span class="site-header__title">CoreTraining</span>
            <?php endif; ?>
        </a>

        <button
            type="button"
            class="site-header__toggle"
            aria-expanded="false"
            aria-controls="primary-navigation"
        >
            <span class="visually-hidden"><?php esc_html_e('Menu', 'coretraining'); ?></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </button>

        <nav id="primary-navigation" class="site-nav" aria-label="<?php esc_attr_e('Hlavní navigace', 'coretraining'); ?>">
            <?php if (has_nav_menu('primary')) : ?>
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'site-nav__list',
                    'fallback_cb'    => false,
                ]);
                ?>
            <?php else : ?>
                <ul class="site-nav__list">
                    <li><a href="<?php echo esc_url(home_url('/kurzy/')); ?>">Kurzy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/clanky/')); ?>">Články</a></li>
                    <li><a href="<?php echo esc_url(home_url('/sluzby/')); ?>">Služby</a></li>
                    <li><a href="<?php echo esc_url(home_url('/studio/')); ?>">Studio</a></li>
                    <li><a href="<?php echo esc_url(home_url('/o-martinovi/')); ?>">O Martinovi</a></li>
                    <li><a href="<?php echo esc_url(home_url('/kontakt/')); ?>">Kontakt</a></li>
                </ul>
            <?php endif; ?>
            <a href="<?php echo esc_url(home_url('/kurzy/')); ?>" class="btn btn--primary site-nav__cta">
                Přihlásit se na kurz
            </a>
        </nav>
    </div>
</header>

<main id="main-content" class="site-main">
