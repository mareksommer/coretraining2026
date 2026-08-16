<?php
/**
 * Template: O Martinovi (/o-martinovi/)
 */

global $coretraining_page_title, $coretraining_meta_description;
$coretraining_page_title       = 'O Martinovi – CoreTraining';
$coretraining_meta_description = 'Martin Snášel — více než 25 let praxe v silovém tréninku, funkční diagnostice a vzdělávání odborníků.';

$references = coretraining_get_references();
$timeline   = coretraining_get_timeline();
$gallery    = coretraining_get_gallery_images();

get_header();
?>

<section class="hero hero--page">
    <div class="container hero__inner">
        <div class="hero__content">
            <h1 class="hero__title">Rozumět pohybu znamená rozumět lidem.</h1>
            <p class="hero__text">
                Více než 25 let se věnuji silovému tréninku, funkční diagnostice a vzdělávání trenérů,
                fyzioterapeutů i aktivních lidí. CoreTraining vznikl s cílem propojit moderní vědecké
                poznatky s každodenní praxí a nabídnout vzdělávání, které pomáhá skutečně porozumět pohybu.
            </p>
        </div>
        <div class="hero__media">
            <img
                src="<?php echo esc_url(coretraining_asset_url('images/hero-o-martinovi.jpg')); ?>"
                alt="<?php esc_attr_e('Martin Snášel', 'coretraining'); ?>"
                width="800"
                height="600"
                loading="eager"
            >
        </div>
    </div>
</section>

<section class="section">
    <div class="container content-block">
        <h2 class="section__title">Můj příběh</h2>
        <p>Moje cesta ke sportu začala už na základní škole, kdy jsem se věnoval karate a silově zaměřeným disciplínám. Právě tehdy vznikl můj dlouhodobý zájem o silový trénink.</p>
        <p>Později jsem několik let závodil v thajském boxu a následně se naplno věnoval kulturistice. Soutěžní kariéra mi přinesla mnoho zkušeností, ale také zdravotní komplikace, které zásadně změnily můj pohled na trénink. Uvědomil jsem si, že samotná síla nestačí. Stejně důležité je pochopit, jak lidské tělo funguje a proč se pohybuje právě tak, jak se pohybuje.</p>
        <p>Tato zkušenost se stala základem mé další profesní cesty.</p>
    </div>
</section>

<section class="section section--alt">
    <div class="container content-block">
        <h2 class="section__title">Od trenéra k diagnostice pohybu</h2>
        <p>Jako trenér působím od roku 1998.</p>
        <p>Během let jsem absolvoval desítky odborných kurzů a certifikací v České republice i v zahraničí. Měl jsem možnost studovat pod vedením světově uznávaných odborníků v oblasti silového tréninku, biomechaniky, fyzioterapie i rehabilitace.</p>
        <p>Největší hodnotu však nepřinesly samotné certifikáty, ale každodenní práce s lidmi.</p>
        <p>Postupně se těžiště mé práce přesunulo od klasického silového tréninku ke komplexní funkční diagnostice pohybového aparátu a návratu klientů ke kvalitnímu pohybu bez bolesti.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section__title">Časová osa</h2>
        <ol class="timeline">
            <?php foreach ($timeline as $item) : ?>
                <li class="timeline__item">
                    <span class="timeline__period"><?php echo esc_html($item['period']); ?></span>
                    <h3 class="timeline__title"><?php echo esc_html($item['title']); ?></h3>
                    <p class="timeline__text"><?php echo esc_html($item['text']); ?></p>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>

<section class="section section--alt">
    <div class="container content-block">
        <h2 class="section__title">Jak vznikl CoreTraining</h2>
        <p>CoreTraining vznikl z jednoduché myšlenky.</p>
        <p>V českém prostředí chyběla platforma, která by propojovala moderní vědecké poznatky s každodenní trenérskou praxí.</p>
        <p>Nešlo mi vytvořit další systém nebo univerzální metodiku. Chtěl jsem vytvořit místo, kde se lidé naučí přemýšlet o pohybu v souvislostech a kde budou schopni získané znalosti využít při práci s každým jednotlivým klientem.</p>
        <p>Proto dnes CoreTraining spojuje odborné články, vzdělávací kurzy, semináře, diagnostiku i individuální spolupráci.</p>
    </div>
</section>

<section class="section">
    <div class="container content-block">
        <h2 class="section__title">Čemu věřím</h2>
        <ul class="beliefs-list">
            <li>Evidence-based přístup</li>
            <li>Kritické myšlení</li>
            <li>Individuální přístup</li>
            <li>Neustálé vzdělávání</li>
            <li>Dlouhodobá praxe</li>
        </ul>
        <p><strong>Každý člověk je jiný. Proto neexistuje univerzální řešení.</strong></p>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <h2 class="section__title">Zkušenosti a vzdělávání</h2>
        <div class="content-columns">
            <div>
                <h3>Certifikace</h3>
                <ul class="cert-list">
                    <li><span class="badge">NCSC</span></li>
                    <li><span class="badge">CFSC Level 2</span></li>
                    <li><span class="badge">FMS</span></li>
                    <li><span class="badge">DNS</span></li>
                    <li><span class="badge">McGill Method</span></li>
                    <li><span class="badge">Pain Free Performance</span></li>
                    <li><span class="badge">další odborné certifikace</span></li>
                </ul>
            </div>
            <div>
                <h3>Profesní zkušenosti</h3>
                <ul>
                    <li>Trenér od roku 1998</li>
                    <li>Zakladatel CoreTraining</li>
                    <li>Zakladatel CORE Centra</li>
                    <li>Lektor odborných seminářů</li>
                    <li>Organizátor vzdělávacích akcí</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section__title">Galerie</h2>
        <div class="grid grid--gallery">
            <?php foreach ($gallery as $image) : ?>
                <figure class="gallery-item">
                    <img
                        src="<?php echo esc_url(coretraining_asset_url('images/gallery/' . $image . '.jpg')); ?>"
                        alt=""
                        loading="lazy"
                        width="400"
                        height="300"
                    >
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if ($references->have_posts()) : ?>
<section class="section section--alt">
    <div class="container">
        <h2 class="section__title">Reference</h2>
        <div class="grid grid--references">
            <?php while ($references->have_posts()) : $references->the_post(); ?>
                <?php get_template_part('template-parts/reference-card'); ?>
            <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>

<section class="section section--centered">
    <div class="container">
        <p class="text-muted">Pokud hledáte vzdělávání postavené na zkušenostech, kritickém myšlení a moderních poznatcích o pohybu, budu rád, když se potkáme na některém z kurzů nebo při individuální spolupráci.</p>
        <a href="<?php echo esc_url(home_url('/kurzy/')); ?>" class="btn btn--primary">Prohlédnout kurzy</a>
    </div>
</section>

<?php get_footer(); ?>
