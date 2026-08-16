<?php
/**
 * Template: Kontakt (/kontakt/)
 */

global $coretraining_page_title, $coretraining_meta_description;
$coretraining_page_title       = 'Kontakt – CoreTraining';
$coretraining_meta_description = 'Kontaktujte CoreTraining — kurzy, diagnostika, individuální spolupráce.';

coretraining_enqueue_leaflet();
coretraining_enqueue_forms();

get_header();
?>

<section class="page-hero page-hero--compact">
    <div class="container">
        <h1 class="page-hero__title">Pojďme se spojit</h1>
        <p class="page-hero__lead text-muted">
            Máte dotaz ke kurzům, individuální spolupráci nebo diagnostice? Napište mi nebo zavolejte.
        </p>
    </div>
</section>

<section class="section">
    <div class="container contact-page">
        <div class="contact-page__grid">
            <div class="contact-page__info">
                <h2 class="section__title">Kontaktní údaje</h2>
                <p><strong>Martin Snášel</strong><br>CoreTraining</p>
                <dl class="contact-details">
                    <div>
                        <dt>Telefon</dt>
                        <dd><a href="tel:<?php echo esc_attr(CORETRAINING_CONTACT_PHONE_LINK); ?>"><?php echo esc_html(CORETRAINING_CONTACT_PHONE); ?></a></dd>
                    </div>
                    <div>
                        <dt>E-mail</dt>
                        <dd><a href="mailto:<?php echo esc_attr(CORETRAINING_CONTACT_EMAIL); ?>"><?php echo esc_html(CORETRAINING_CONTACT_EMAIL); ?></a></dd>
                    </div>
                    <div>
                        <dt>Facebook</dt>
                        <dd><a href="<?php echo esc_url(CORETRAINING_FACEBOOK_URL); ?>" target="_blank" rel="noopener noreferrer">CORETRAININGCZ</a></dd>
                    </div>
                </dl>

                <h3>Adresa studia</h3>
                <p><strong>CORE — Silové a pohybové centrum</strong><br><?php echo esc_html(CORETRAINING_STUDIO_ADDRESS); ?></p>

                <h3>Fakturační údaje</h3>
                <dl class="contact-details">
                    <div><dt>Jméno / firma</dt><dd>Martin Snášel / CoreTraining</dd></div>
                    <div><dt>IČO</dt><dd><?php echo esc_html(CORETRAINING_ICO); ?></dd></div>
                    <div><dt>Sídlo</dt><dd><?php echo esc_html(CORETRAINING_BILLING_ADDRESS); ?></dd></div>
                    <div><dt>Bankovní účet</dt><dd><?php echo esc_html(CORETRAINING_BANK_ACCOUNT); ?></dd></div>
                </dl>
            </div>

            <div class="contact-page__form">
                <h2 class="section__title">Kontaktní formulář</h2>
                <?php get_template_part('template-parts/contact-form'); ?>
            </div>
        </div>

        <div class="contact-page__map">
            <h2 class="section__title">Mapa</h2>
            <div id="coretraining-map" class="map" aria-label="<?php esc_attr_e('Mapa CORE Centra', 'coretraining'); ?>"></div>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <h2 class="section__title section__title--centered">Naše projekty</h2>
        <?php get_template_part('template-parts/projects-grid'); ?>
    </div>
</section>

<?php get_footer(); ?>
