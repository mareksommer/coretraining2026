<?php
/**
 * Template: Studio (/studio/)
 */

global $coretraining_page_title, $coretraining_meta_description;
$coretraining_page_title       = 'Studio CORE – CoreTraining';
$coretraining_meta_description = 'Silové a pohybové centrum CORE v Praze–Letňany. Prostor pro trénink, semináře i individuální práci.';

coretraining_enqueue_leaflet();

get_header();
?>

<section class="page-hero page-hero--compact">
    <div class="container">
        <h1 class="page-hero__title">CORE — Silové a pohybové centrum</h1>
        <p class="page-hero__lead text-muted">
            Prostor, kde se setkává odbornost CoreTraining s každodenní praxí. Trénink, semináře i individuální práce pod jednou střechou.
        </p>
        <div class="hero__actions">
            <a href="https://www.corecentrum.cz" class="btn btn--primary" target="_blank" rel="noopener noreferrer">Navštívit web centra</a>
            <a href="<?php echo esc_url(home_url('/kontakt/')); ?>" class="btn btn--secondary">Kontaktovat</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container content-block">
        <p>CORE Centrum je fyzické zázemí značky CoreTraining v Praze–Letňany. Vzniklo jako místo pro kvalitní silový a pohybový trénink, funkční diagnostiku a vzdělávací akce — s důrazem na odbornost, bez kompromisů vůči „fitness rutině“.</p>
        <p>Semináře a kurzy CoreTraining se konají právě zde. Stejný prostor slouží i pro individuální spolupráci s klienty, kteří chtějí pracovat na pohybu, síle nebo návratu k výkonu s promyšleným, individuálním přístupem.</p>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <h2 class="section__title">Co v centru najdete</h2>
        <div class="grid grid--services">
            <article class="service-card">
                <h3 class="service-card__title">Vzdělávací akce</h3>
                <p class="service-card__text">Prezenční semináře, workshopy a praktická školení pro trenéry, fyzioterapeuty i pokročilou veřejnost. Většina kurzů CoreTraining probíhá přímo v CORE Centru.</p>
                <a href="<?php echo esc_url(home_url('/kurzy/')); ?>" class="service-card__link">Prohlédnout kurzy →</a>
            </article>
            <article class="service-card">
                <h3 class="service-card__title">Individuální práce</h3>
                <p class="service-card__text">Prostor pro osobní trénink, konzultace a funkční diagnostiku. Každý klient jiný — proto žádné univerzální programy, ale práce postavená na pochopení konkrétního problému.</p>
                <a href="<?php echo esc_url(home_url('/sluzby/#spoluprace')); ?>" class="service-card__link">Domluvit spolupráci →</a>
            </article>
            <article class="service-card">
                <h3 class="service-card__title">Silový a pohybový trénink</h3>
                <p class="service-card__text">Vybavení pro kvalitní silový trénink i práci s pohybovými vzory. Prostředí odpovídá standardu odbornosti CoreTraining — klidné, profesionální, bez rušivého fitness marketingu.</p>
            </article>
        </div>
    </div>
</section>

<section class="section" id="kde-nas-najdete">
    <div class="container">
        <h2 class="section__title">Kde nás najdete</h2>
        <div class="contact-layout">
            <dl class="contact-details">
                <div><dt>Název</dt><dd>CORE — Silové a pohybové centrum</dd></div>
                <div><dt>Adresa</dt><dd><?php echo esc_html(CORETRAINING_STUDIO_ADDRESS); ?></dd></div>
                <div><dt>Telefon</dt><dd><a href="tel:<?php echo esc_attr(CORETRAINING_CONTACT_PHONE_LINK); ?>"><?php echo esc_html(CORETRAINING_CONTACT_PHONE); ?></a></dd></div>
                <div><dt>E-mail</dt><dd><a href="mailto:<?php echo esc_attr(CORETRAINING_CONTACT_EMAIL); ?>"><?php echo esc_html(CORETRAINING_CONTACT_EMAIL); ?></a></dd></div>
                <div><dt>Web centra</dt><dd><a href="https://www.corecentrum.cz" target="_blank" rel="noopener noreferrer">corecentrum.cz</a></dd></div>
            </dl>
            <div id="coretraining-map" class="map" aria-label="<?php esc_attr_e('Mapa CORE Centra', 'coretraining'); ?>"></div>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container content-block">
        <h2 class="section__title">Vztah CoreTraining ↔ CORE Centrum</h2>
        <ul>
            <li><strong>CoreTraining</strong> — vzdělávání, články, kurzy, odbornost Martina Snášela</li>
            <li><strong>CORE Centrum</strong> — fyzický prostor, kde se vzdělávání a praxe setkávají</li>
        </ul>
        <p>Obojí spolu úzce souvisí, ale každé má svůj web: podrobnosti o centru, otevírací době a aktuální nabídce najdete na <a href="https://www.corecentrum.cz" target="_blank" rel="noopener noreferrer">corecentrum.cz</a>.</p>
    </div>
</section>

<section class="section section--centered">
    <div class="container">
        <h2 class="section__title">Chcete se potkat v centru?</h2>
        <p class="text-muted">Ať už hledáte kurz, individuální spolupráci, nebo chcete navštívit CORE Centrum osobně, ozvěte se. Rádi vám poradíme s výběrem.</p>
        <div class="hero__actions" style="justify-content: center;">
            <a href="<?php echo esc_url(home_url('/kontakt/')); ?>" class="btn btn--primary">Kontaktovat</a>
            <a href="https://www.corecentrum.cz" class="btn btn--secondary" target="_blank" rel="noopener noreferrer">Web CORE Centra</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
