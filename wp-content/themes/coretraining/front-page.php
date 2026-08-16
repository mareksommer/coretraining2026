<?php
/**
 * Template: Homepage
 */

global $coretraining_page_title, $coretraining_meta_description;
$coretraining_page_title       = 'CoreTraining – Pomáháme lidem lépe porozumět pohybu';
$coretraining_meta_description = 'CoreTraining propojuje moderní poznatky o pohybu s dlouholetou praxí. Kurzy, články a individuální spolupráce pro trenéry, fyzioterapeuty i aktivní veřejnost.';

get_header();

$courses      = coretraining_query_upcoming_courses(6);
$articles     = coretraining_get_latest_clanky(6);
$references   = coretraining_get_references();
$next_course  = coretraining_query_upcoming_courses(1);
$latest_post  = coretraining_get_latest_clanky(1);
?>

<section class="hero hero--home">
    <div class="container hero__inner">
        <div class="hero__content">
            <h1 class="hero__title">Pomáháme lidem lépe porozumět pohybu.</h1>
            <p class="hero__text">
                CoreTraining propojuje moderní poznatky o pohybu s dlouholetou praxí. Vzděláváme trenéry,
                fyzioterapeuty i aktivní veřejnost prostřednictvím odborných kurzů, individuální spolupráce
                a vzdělávacích článků.
            </p>
            <p class="hero__text hero__text--emphasis">
                Naším cílem není učit další cviky. Učíme přemýšlet o pohybu.
            </p>
            <div class="hero__actions">
                <a href="<?php echo esc_url(home_url('/kurzy/')); ?>" class="btn btn--primary">Prohlédnout kurzy</a>
                <a href="<?php echo esc_url(home_url('/clanky/')); ?>" class="btn btn--secondary">Články</a>
            </div>
        </div>
        <div class="hero__media">
            <img
                src="<?php echo esc_url(coretraining_asset_url('images/hero.jpg')); ?>"
                alt="<?php esc_attr_e('Martin Snášel při výuce', 'coretraining'); ?>"
                width="800"
                height="600"
                loading="eager"
            >
        </div>
    </div>

            <?php if ($next_course->have_posts() || $latest_post->have_posts()) : ?>
        <div class="container hero__spotlight">
            <?php if ($next_course->have_posts()) : ?>
                <?php $next_course->the_post(); ?>
                <?php
                $meta = coretraining_get_course_meta(get_the_ID());
                $date = coretraining_format_course_date($meta['date'], $meta['date_end']);
                ?>
                <a href="<?php the_permalink(); ?>" class="hero-spot hero-spot--course">
                    <?php if (has_post_thumbnail()) : ?>
                        <span class="hero-spot__thumb">
                            <?php the_post_thumbnail('thumbnail', ['loading' => 'lazy']); ?>
                        </span>
                    <?php endif; ?>
                    <span class="hero-spot__body">
                        <span class="hero-spot__label"><?php esc_html_e('Příští kurz', 'coretraining'); ?></span>
                        <span class="hero-spot__title"><?php the_title(); ?></span>
                        <?php if ($date) : ?>
                            <span class="hero-spot__meta"><?php echo esc_html($date); ?><?php echo $meta['location'] ? ' · ' . esc_html($meta['location']) : ''; ?></span>
                        <?php endif; ?>
                    </span>
                </a>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>

            <?php if ($latest_post->have_posts()) : ?>
                <?php $latest_post->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="hero-spot hero-spot--article">
                    <?php if (has_post_thumbnail()) : ?>
                        <span class="hero-spot__thumb">
                            <?php the_post_thumbnail('thumbnail', ['loading' => 'lazy']); ?>
                        </span>
                    <?php endif; ?>
                    <span class="hero-spot__body">
                        <span class="hero-spot__label"><?php esc_html_e('Nejnovější článek', 'coretraining'); ?></span>
                        <span class="hero-spot__title"><?php the_title(); ?></span>
                        <span class="hero-spot__meta"><?php echo esc_html(get_the_date('j. n. Y')); ?></span>
                    </span>
                </a>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<section class="section section--stats">
    <div class="container">
        <ul class="stats" data-stats>
            <li class="stats__item">
                <span class="stats__value" data-count="25" data-suffix="+">0+</span>
                <span class="stats__label">let praxe</span>
            </li>
            <li class="stats__item">
                <span class="stats__value" data-count="350" data-suffix="+">0+</span>
                <span class="stats__label">odborných článků</span>
            </li>
            <li class="stats__item">
                <span class="stats__value" data-count="40" data-suffix="+">0+</span>
                <span class="stats__label">seminářů a kurzů</span>
            </li>
            <li class="stats__item">
                <span class="stats__value" data-count="1000" data-suffix="+">0+</span>
                <span class="stats__label">účastníků vzdělávání</span>
            </li>
            <li class="stats__item stats__item--text">
                <span class="stats__value">Evidence-based</span>
                <span class="stats__label">přístup</span>
            </li>
        </ul>
    </div>
</section>

<section class="section">
    <div class="container">
        <header class="section__header">
            <h2 class="section__title">Komu pomáháme</h2>
        </header>
        <div class="grid grid--cards">
            <article class="info-card">
                <span class="info-card__icon"><?php echo coretraining_icon('users'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                <h3 class="info-card__title">Trenéři a kouči</h3>
                <p class="info-card__text">Kurzy a články, které propojují silový trénink, biomechaniku a každodenní praxi. Cílem není naučit další cviky, ale kriticky přemýšlet o pohybu — s každým klientem jinak.</p>
            </article>
            <article class="info-card">
                <span class="info-card__icon"><?php echo coretraining_icon('pulse'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                <h3 class="info-card__title">Fyzioterapeuti</h3>
                <p class="info-card__text">Semináře a materiály na pomezí rehabilitace a tréninku. Pomohou vám propojit diagnostiku s konkrétním postupem, dávkováním zátěže a návratem klienta k pohybu.</p>
            </article>
            <article class="info-card">
                <span class="info-card__icon"><?php echo coretraining_icon('zap'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                <h3 class="info-card__title">Sportovci</h3>
                <p class="info-card__text">Pro ty, kdo chtějí pochopit, proč se tělo pohybuje tak, jak se pohybuje — a jak bezpečně znovu získat výkon po zranění nebo přetížení. Individuální přístup, ne univerzální program.</p>
            </article>
            <article class="info-card">
                <span class="info-card__icon"><?php echo coretraining_icon('person'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                <h3 class="info-card__title">Každý, kdo se hýbe</h3>
                <p class="info-card__text">Pro každého, kdo chce lépe rozumět vlastnímu tělu, bolesti a pohybu. Srozumitelné, evidence-based informace bez fitness mýtů a univerzálních řešení.</p>
            </article>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <header class="section__header">
            <h2 class="section__title">Služby</h2>
        </header>
        <div class="grid grid--services">
            <article class="service-card">
                <div class="service-card__heading">
                    <span class="service-card__icon"><?php echo coretraining_icon('book'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <h3 class="service-card__title">Kurzy a semináře</h3>
                </div>
                <p class="service-card__text">Prezenční semináře, workshopy i online webináře pro trenéry, fyzioterapeuty i pokročilou veřejnost. Propojujeme vědecké poznatky s praxí — abyste rozuměli principům, ne jen postupům.</p>
                <a href="<?php echo esc_url(home_url('/kurzy/')); ?>" class="service-card__link">Prohlédnout kurzy →</a>
            </article>
            <article class="service-card">
                <div class="service-card__heading">
                    <span class="service-card__icon"><?php echo coretraining_icon('scan'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <h3 class="service-card__title">Funkční diagnostika</h3>
                </div>
                <p class="service-card__text">Komplexní vyšetření pohybového aparátu jako základ smysluplného tréninku. Odhalíme asymetrie, dysbalance a rizika — a na tom postavíme konkrétní postup pro každého klienta.</p>
                <a href="<?php echo esc_url(home_url('/sluzby/#diagnostika')); ?>" class="service-card__link">Více o diagnostice →</a>
            </article>
            <article class="service-card">
                <div class="service-card__heading">
                    <span class="service-card__icon"><?php echo coretraining_icon('message'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <h3 class="service-card__title">Individuální spolupráce</h3>
                </div>
                <p class="service-card__text">Osobní konzultace, poradenství a spolupráce na míru — ať už řešíte bolest, návrat k výkonu, nebo nastavení tréninku. Žádné univerzální šablony, vždy individuální přístup.</p>
                <a href="<?php echo esc_url(home_url('/sluzby/#spoluprace')); ?>" class="service-card__link">Domluvit spolupráci →</a>
            </article>
            <article class="service-card">
                <div class="service-card__heading">
                    <span class="service-card__icon"><?php echo coretraining_icon('home'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <h3 class="service-card__title">Studio CORE</h3>
                </div>
                <p class="service-card__text">Silové a pohybové centrum v Praze–Letňany. Prostor pro trénink, semináře i individuální práci — vybavení a prostředí odpovídající odbornosti CoreTraining.</p>
                <a href="<?php echo esc_url(home_url('/studio/')); ?>" class="service-card__link">Prohlédnout studio →</a>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="about-teaser">
            <div class="about-teaser__content">
                <div class="about-teaser__heading">
                    <span class="about-teaser__icon"><?php echo coretraining_icon('layers'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <h2 class="section__title">Za každým kurzem stojí roky zkušeností.</h2>
                </div>
                <p>Více než dvacet let se věnuji pohybu, silovému tréninku a vzdělávání odborníků.</p>
                <p>Během své praxe jsem spolupracoval s trenéry, fyzioterapeuty, sportovci i běžnou veřejností. CoreTraining vznikl s cílem propojit vědecké poznatky s každodenní praxí.</p>
                <p>Nevěřím univerzálním metodám. Věřím pochopení principů, kritickému myšlení a celoživotnímu vzdělávání.</p>
                <a href="<?php echo esc_url(home_url('/o-martinovi/')); ?>" class="btn btn--primary">Více o Martinovi</a>
            </div>
            <div class="about-teaser__media">
                <img
                    src="<?php echo esc_url(coretraining_asset_url('images/hero-o-martinovi.jpg')); ?>"
                    alt="<?php esc_attr_e('Martin Snášel', 'coretraining'); ?>"
                    width="640"
                    height="800"
                    loading="lazy"
                >
            </div>
        </div>
    </div>
</section>

<?php if ($courses->have_posts()) : ?>
<section class="section section--alt">
    <div class="container">
        <header class="section__header section__header--row">
            <h2 class="section__title">Aktuální kurzy</h2>
            <a href="<?php echo esc_url(home_url('/kurzy/')); ?>" class="section__link">Všechny kurzy →</a>
        </header>
        <div class="grid grid--courses">
            <?php while ($courses->have_posts()) : $courses->the_post(); ?>
                <?php get_template_part('template-parts/course-card'); ?>
            <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>

<?php if ($references->have_posts()) : ?>
<section class="section">
    <div class="container">
        <header class="section__header">
            <h2 class="section__title">Reference</h2>
        </header>
        <div class="carousel" data-carousel>
            <button type="button" class="carousel__btn carousel__btn--prev" data-carousel-prev aria-label="<?php esc_attr_e('Předchozí', 'coretraining'); ?>">←</button>
            <div class="carousel__track" data-carousel-track>
                <?php while ($references->have_posts()) : $references->the_post(); ?>
                    <div class="carousel__slide">
                        <?php get_template_part('template-parts/reference-card'); ?>
                    </div>
                <?php endwhile; ?>
            </div>
            <button type="button" class="carousel__btn carousel__btn--next" data-carousel-next aria-label="<?php esc_attr_e('Další', 'coretraining'); ?>">→</button>
        </div>
        <?php wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>

<?php if ($articles->have_posts()) : ?>
<section class="section section--alt">
    <div class="container">
        <header class="section__header section__header--row">
            <h2 class="section__title">Nejnovější články</h2>
            <a href="<?php echo esc_url(home_url('/clanky/')); ?>" class="section__link">Všechny články →</a>
        </header>
        <div class="grid grid--articles">
            <?php while ($articles->have_posts()) : $articles->the_post(); ?>
                <?php get_template_part('template-parts/article-card'); ?>
            <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <header class="section__header section__header--centered">
            <h2 class="section__title">Naše projekty</h2>
        </header>
        <?php get_template_part('template-parts/projects-grid'); ?>
    </div>
</section>

<?php get_footer(); ?>
