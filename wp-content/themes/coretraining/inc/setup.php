<?php
/**
 * Theme constants and setup (pages on activation)
 */

define('CORETRAINING_CONTACT_EMAIL', 'info@coretraining.cz');
define('CORETRAINING_CONTACT_PHONE', '+420 777 131 078');
define('CORETRAINING_CONTACT_PHONE_LINK', '+420777131078');
define('CORETRAINING_BANK_ACCOUNT', 'Mbank 670100-2211277834/6210');
define('CORETRAINING_ICO', '716 478 56');
define('CORETRAINING_STUDIO_ADDRESS', 'Kulturní dům Letňanka, 2. patro, Rýmařovská 561, 199 00 Praha–Letňany');
define('CORETRAINING_BILLING_ADDRESS', 'V Uličce 2291, 250 01 Brandýs nad Labem');
define('CORETRAINING_MAP_LAT', 50.1306);
define('CORETRAINING_MAP_LNG', 14.5178);
define('CORETRAINING_GDPR_URL', '/ochrana-udaju/');
define('CORETRAINING_FACEBOOK_URL', 'https://www.facebook.com/CORETRAININGCZ');

add_action('after_switch_theme', function (): void {
    $pages = [
        'sluzby'       => 'Služby',
        'studio'       => 'Studio',
        'kontakt'      => 'Kontakt',
        'o-martinovi'  => 'O Martinovi',
        'ochrana-udaju' => 'Ochrana údajů a cookies',
    ];

    foreach ($pages as $slug => $title) {
        if (!get_page_by_path($slug)) {
            wp_insert_post([
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
            ]);
        }
    }

    $home = get_page_by_path('home');
    if (!$home) {
        $home_id = wp_insert_post([
            'post_title'  => 'Úvod',
            'post_name'   => 'home',
            'post_status' => 'publish',
            'post_type'   => 'page',
        ]);
        if ($home_id && !is_wp_error($home_id)) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $home_id);
        }
    }
});

function coretraining_get_projects(): array {
    return [
        [
            'name' => 'CoreTraining',
            'url'  => 'https://www.coretraining.cz/',
            'logo' => '1.jpg',
        ],
        [
            'name' => 'CORE Centrum',
            'url'  => 'https://www.corecentrum.cz/',
            'logo' => '2.jpg',
        ],
        [
            'name' => 'Funkční diagnostika',
            'url'  => 'https://funkcnidiagnostika.cz/',
            'logo' => '3.png',
        ],
        [
            'name' => 'SleepCoach',
            'url'  => 'https://sleepcoach.cz/',
            'logo' => '4.png',
        ],
    ];
}

function coretraining_get_timeline(): array {
    return [
        [
            'period' => '90. léta',
            'title'  => 'Základy',
            'text'   => 'Sportovní cesta začala už na základní škole — karate a silově zaměřené disciplíny. Tehdy vznikl dlouhodobý zájem o silový trénink a pochopení pohybu.',
        ],
        [
            'period' => '90. léta',
            'title'  => 'Soutěžní zkušenosti',
            'text'   => 'Několik let thajský box, poté kulturistika. Soutěžní kariéra přinesla praxi i zdravotní komplikace — uvědomění, že síla sama o sobě nestačí.',
        ],
        [
            'period' => '1998',
            'title'  => 'Trenér',
            'text'   => 'Od roku 1998 působím jako certifikovaný trenér. Každodenní práce s lidmi se postupně stala důležitější než jakýkoli certifikát na zdi.',
        ],
        [
            'period' => '2000–2010',
            'title'  => 'Odborné vzdělávání',
            'text'   => 'Desítky kurzů a certifikací v ČR i zahraničí — silový trénink, biomechanika, fyzioterapie, rehabilitace. Studium pod světově uznávanými odborníky.',
        ],
        [
            'period' => '2010+',
            'title'  => 'Funkční diagnostika',
            'text'   => 'Těžiště práce se přesunulo ke komplexní funkční diagnostice pohybového aparátu a návratu klientů ke kvalitnímu pohybu bez bolesti.',
        ],
        [
            'period' => '—',
            'title'  => 'CoreTraining',
            'text'   => 'Vznik platformy propojující moderní vědecké poznatky s každodenní trenérskou praxí. Cíl: učit přemýšlet o pohybu v souvislostech.',
        ],
        [
            'period' => '—',
            'title'  => 'CORE Centrum',
            'text'   => 'Založení silového a pohybového centra CORE v Praze–Letňany — fyzické zázemí pro semináře, diagnostiku i individuální práci.',
        ],
        [
            'period' => 'Současnost',
            'title'  => 'Lektor a mentor',
            'text'   => 'Lektor odborných seminářů, autor stovky článků, organizátor vzdělávacích akcí. CoreTraining spojuje články, kurzy, diagnostiku i individuální spolupráci.',
        ],
    ];
}

function coretraining_get_gallery_images(): array {
    return ['1', '2', '5', '6', '7', '9', '10', '11', '13'];
}

function coretraining_enqueue_leaflet(): void {
    wp_enqueue_style(
        'leaflet',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
        [],
        '1.9.4'
    );
    wp_enqueue_script(
        'leaflet',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
        [],
        '1.9.4',
        true
    );
    wp_enqueue_script(
        'coretraining-map',
        get_template_directory_uri() . '/assets/js/map.js',
        ['leaflet'],
        CORETRAINING_VERSION,
        true
    );
    wp_localize_script('coretraining-map', 'coretrainingMap', [
        'lat'     => CORETRAINING_MAP_LAT,
        'lng'     => CORETRAINING_MAP_LNG,
        'title'   => 'CORE — Silové a pohybové centrum',
        'address' => CORETRAINING_STUDIO_ADDRESS,
    ]);
}

function coretraining_enqueue_forms(): void {
    wp_enqueue_script(
        'coretraining-forms',
        get_template_directory_uri() . '/assets/js/forms.js',
        [],
        CORETRAINING_VERSION,
        true
    );
    wp_localize_script('coretraining-forms', 'coretrainingForms', [
        'restUrl'  => esc_url_raw(rest_url('coretraining/v1')),
        'nonce'    => wp_create_nonce('wp_rest'),
        'gdprUrl'  => esc_url(home_url(CORETRAINING_GDPR_URL)),
        'messages' => [
            'success'      => __('Zpráva byla odeslána. Brzy se vám ozveme.', 'coretraining'),
            'courseSuccess' => __('Přihláška byla odeslána. Potvrzení jsme zaslali na váš e-mail.', 'coretraining'),
            'error'        => __('Odeslání se nezdařilo. Zkuste to prosím znovu.', 'coretraining'),
            'rateLimit'    => __('Příliš mnoho pokusů. Zkuste to prosím později.', 'coretraining'),
            'gdpr'         => __('Bez souhlasu se zpracováním údajů formulář nelze odeslat.', 'coretraining'),
        ],
    ]);
}
