<?php
/**
 * Template Name: Úspěšná registrace
 * Template: Stránka /uspesna-registrace/
 * Guard je implementován v functions.php (template_redirect).
 */

global $callup_page_title;
$callup_page_title = 'Registrace proběhla úspěšně – Callup';

get_header();
?>

<div class="container">
    <div class="success-page">
        <div class="success-page__icon" aria-hidden="true">✓</div>
        <h1 class="success-page__title">Registrace proběhla úspěšně!</h1>
        <p class="success-page__text">
            Tvůj účet byl vytvořen. Nyní se můžeš přihlásit a začít využívat naše nabídky.
        </p>
        <div class="success-page__actions">
            <a href="https://smeny.callup.stafio.cz" target="_blank" rel="noopener noreferrer" class="btn btn--primary btn--lg">
                Přihlásit se
            </a>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--outline btn--lg">
                Zpět na nabídky
            </a>
        </div>
    </div>
</div>

<?php get_footer(); ?>
