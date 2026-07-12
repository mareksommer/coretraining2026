<?php
/**
 * Template: 404
 */

global $coretraining_page_title;
$coretraining_page_title = __('Stránka nenalezena', 'coretraining') . ' – CoreTraining';

get_header();
?>

<section class="section section--centered">
    <div class="container">
        <p class="error-page__code" aria-hidden="true">404</p>
        <h1 class="error-page__title"><?php esc_html_e('Stránka nenalezena', 'coretraining'); ?></h1>
        <p class="error-page__text">
            <?php esc_html_e('Požadovaná stránka neexistuje nebo byla přesunuta.', 'coretraining'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">
            <?php esc_html_e('Zpět na úvod', 'coretraining'); ?>
        </a>
    </div>
</section>

<?php get_footer(); ?>
