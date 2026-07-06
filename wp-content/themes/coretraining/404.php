<?php
/**
 * Template: 404 stránka
 */

global $coretraining_page_title;
$coretraining_page_title = 'Stránka nenalezena – Coretraining';

get_header();
?>

<div class="container">
    <div class="error-page">
        <div class="error-page__code">404</div>
        <h1 class="error-page__title">Stránka nenalezena</h1>
        <p class="error-page__text">
            Požadovaná stránka nebo inzerát neexistuje, byl odstraněn nebo přesměrován.
        </p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary btn--lg">
            Zpět na nabídky
        </a>
    </div>
</div>

<?php get_footer(); ?>
