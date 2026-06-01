<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="site-header__inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-header__logo">
            <span class="site-header__logo-callup">Callup</span>
        </a>
        <nav class="site-header__nav">
            <a href="https://smeny.callup.stafio.cz" target="_blank" rel="noopener noreferrer" class="btn btn--outline btn--sm">
                Přihlásit se
            </a>
            <a href="<?php echo esc_url(home_url('/registrace/')); ?>" class="btn btn--primary btn--sm">
                Registrace
            </a>
        </nav>
    </div>
</header>
<main class="site-main">
