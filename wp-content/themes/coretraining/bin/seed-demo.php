<?php
/**
 * Seed demo content for Martin presentation.
 *
 * Run inside WordPress container:
 *   wp eval-file wp-content/themes/coretraining/bin/seed-demo.php --allow-root
 *
 * Safe to re-run: skips items tagged with meta `_coretraining_seed`.
 */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run via: wp eval-file ...\n");
    exit(1);
}

$theme_dir = dirname(__DIR__); // .../themes/coretraining
$seed_tag  = '_coretraining_seed';

echo "=== CoreTraining demo seed ===\n";
echo "Theme dir: {$theme_dir}\n";

// ── Permalink structure ───────────────────────────────────────────────────────

update_option('permalink_structure', '/%postname%/');
flush_rewrite_rules(false);

$htaccess = ABSPATH . '.htaccess';
if (!file_exists($htaccess) || !str_contains((string) file_get_contents($htaccess), 'RewriteEngine')) {
    $rules = <<<'HTA'
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
HTA;
    file_put_contents($htaccess, $rules);
    echo "✓ .htaccess written\n";
}

echo "✓ Permalinks flushed\n";

// ── Theme + plugins ───────────────────────────────────────────────────────────

switch_theme('coretraining');
echo "✓ Theme activated\n";

foreach (['cookie-notice/cookie-notice.php', 'wordpress-seo/wp-seo.php'] as $plugin) {
    if (file_exists(WP_PLUGIN_DIR . '/' . $plugin) && !is_plugin_active($plugin)) {
        activate_plugin($plugin);
        echo "✓ Activated plugin: {$plugin}\n";
    }
}

// ── Static pages (ensure exist) ───────────────────────────────────────────────

$pages = [
    'home'          => 'Úvod',
    'sluzby'        => 'Služby',
    'studio'        => 'Studio',
    'kontakt'       => 'Kontakt',
    'o-martinovi'   => 'O Martinovi',
    'ochrana-udaju' => 'Ochrana údajů a cookies',
];

$page_ids = [];
foreach ($pages as $slug => $title) {
    $existing = get_page_by_path($slug);
    if ($existing) {
        $page_ids[$slug] = $existing->ID;
        continue;
    }
    $id = wp_insert_post([
        'post_title'  => $title,
        'post_name'   => $slug,
        'post_status' => 'publish',
        'post_type'   => 'page',
        'post_content'=> $slug === 'ochrana-udaju'
            ? "<!-- Demo text — před launchí nahradit schváleným zněním. -->\n\n<p>Správce osobních údajů: Martin Snášel, IČO 716 478 56, info@coretraining.cz.</p>\n<p>Kontaktní a přihlašovací formuláře zpracovávají údaje pouze za účelem odpovědi na váš dotaz / přihlášky na kurz.</p>"
            : '',
    ]);
    $page_ids[$slug] = $id;
    echo "✓ Created page /{$slug}/\n";
}

update_option('show_on_front', 'page');
update_option('page_on_front', $page_ids['home']);
echo "✓ Front page set to Úvod\n";

// ── Category clanky + typ_kurzu terms ─────────────────────────────────────────

if (!term_exists('clanky', 'category')) {
    wp_insert_term('Články', 'category', ['slug' => 'clanky']);
}
$clanky = get_term_by('slug', 'clanky', 'category');

$typ_terms = [
    'seminar'         => 'Seminář',
    'workshop'        => 'Workshop',
    'webinar'         => 'Webinář',
    'prednaska-hosta' => 'Přednáška hosta',
];
foreach ($typ_terms as $slug => $name) {
    if (!term_exists($slug, 'typ_kurzu')) {
        wp_insert_term($name, 'typ_kurzu', ['slug' => $slug]);
    }
}
echo "✓ Taxonomy terms ready\n";

// ── Helper: sideload local image ──────────────────────────────────────────────

$sideload = static function (string $absolute_path, int $parent_id = 0, string $title = ''): int {
    if (!file_exists($absolute_path)) {
        return 0;
    }
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $filename = basename($absolute_path);
    $tmp      = wp_tempnam($filename);
    copy($absolute_path, $tmp);

    $file_array = [
        'name'     => $filename,
        'tmp_name' => $tmp,
    ];

    $id = media_handle_sideload($file_array, $parent_id, $title ?: pathinfo($filename, PATHINFO_FILENAME));
    if (is_wp_error($id)) {
        @unlink($tmp);
        echo "  ! Image failed: {$filename} — {$id->get_error_message()}\n";
        return 0;
    }
    return (int) $id;
};

// ── Logo ──────────────────────────────────────────────────────────────────────

if (!get_theme_mod('custom_logo')) {
    $logo_id = $sideload($theme_dir . '/assets/images/logo.png', 0, 'CoreTraining logo');
    if ($logo_id) {
        set_theme_mod('custom_logo', $logo_id);
        echo "✓ Custom logo set\n";
    }
}

// ── Navigation menus ──────────────────────────────────────────────────────────

$ensure_menu = static function (string $location, string $name, array $items): void {
    $locations = get_theme_mod('nav_menu_locations', []);
    if (!empty($locations[$location])) {
        $menu = wp_get_nav_menu_object($locations[$location]);
        if ($menu) {
            return;
        }
    }

    $menu_id = wp_create_nav_menu($name);
    foreach ($items as $item) {
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'  => $item['title'],
            'menu-item-url'    => home_url($item['path']),
            'menu-item-status' => 'publish',
            'menu-item-type'   => 'custom',
        ]);
    }
    $locations[$location] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
};

$ensure_menu('primary', 'Hlavní menu', [
    ['title' => 'Kurzy', 'path' => '/kurzy/'],
    ['title' => 'Články', 'path' => '/clanky/'],
    ['title' => 'Služby', 'path' => '/sluzby/'],
    ['title' => 'Studio', 'path' => '/studio/'],
    ['title' => 'O Martinovi', 'path' => '/o-martinovi/'],
    ['title' => 'Kontakt', 'path' => '/kontakt/'],
]);

$ensure_menu('footer', 'Patička', [
    ['title' => 'Kurzy', 'path' => '/kurzy/'],
    ['title' => 'Články', 'path' => '/clanky/'],
    ['title' => 'Kontakt', 'path' => '/kontakt/'],
    ['title' => 'Ochrana údajů', 'path' => '/ochrana-udaju/'],
]);
echo "✓ Menus ready\n";

// ── References from CSV ───────────────────────────────────────────────────────

$csv_path = $theme_dir . '/bin/reference.csv';
$ref_dir  = $theme_dir . '/assets/images/references';
$existing_refs = get_posts([
    'post_type'      => 'reference',
    'posts_per_page' => -1,
    'meta_key'       => $seed_tag,
    'meta_value'     => '1',
    'fields'         => 'ids',
]);

if (empty($existing_refs) && file_exists($csv_path)) {
    $handle = fopen($csv_path, 'r');
    $header = fgetcsv($handle);
    $order  = 0;
    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) < 4) {
            continue;
        }
        [$name, $text, $rating, $foto] = $row;
        $order++;
        $id = wp_insert_post([
            'post_type'    => 'reference',
            'post_status'  => 'publish',
            'post_title'   => $name . ' — demo',
            'post_content' => $text,
            'menu_order'   => $order,
        ]);
        if (is_wp_error($id) || !$id) {
            continue;
        }
        update_post_meta($id, 'reference_name', $name);
        update_post_meta($id, 'reference_rating', (int) $rating);
        update_post_meta($id, $seed_tag, '1');
        $img = $sideload($ref_dir . '/' . $foto, $id, $name);
        if ($img) {
            set_post_thumbnail($id, $img);
        }
        echo "✓ Reference: {$name}\n";
    }
    fclose($handle);
} else {
    echo "· References already seeded (" . count($existing_refs) . ")\n";
}

// ── Live autumn 2026 courses (prefer bin/sync-live-courses.php for thumbnails) ─

$courses = [
    [
        'title'    => 'Prevence a rekondice ramenního kloubu a návrat do tréninku/sportu',
        'slug'     => 'prevence-a-rekondice-ramenniho-kloubu-a-navrat-do-treninku-sportu',
        'excerpt'  => 'Problematika bolestí a zranění ramenního kloubu u sportovců — prevence, biomechanika a postupný návrat do výkonu.',
        'content'  => "<p>Kurz o zraněních a bolestech ramenního kloubu u sportovců. Probíráme fáze rehabilitace, biomechaniku a baterii testů pro rekondici.</p>",
        'date'     => '2026-09-12',
        'date_end' => '',
        'time'     => '9:00 – 18:00',
        'location' => 'Praha',
        'price'    => '5 500 Kč',
        'type'     => 'seminar',
        'image'    => 'images/gallery/1.jpg',
    ],
    [
        'title'    => 'Spondylolýza/Spondylolistéza bez mýtů: Jak vrátit sportovce zpět do výkonu',
        'slug'     => 'spondylolyza-spondylolisteza-bez-mytu-jak-vratit-sportovce-zpet-do-vykonu',
        'excerpt'  => 'Praktický kurz o spondylolýze a spondylolistéze — diagnostika, mýty, load management a návrat ke sportu.',
        'content'  => "<p>Evidence-based kurz spojující silový trénink, rehab a rekondici u spondylolýzy a spondylolistézy.</p>",
        'date'     => '2026-09-19',
        'date_end' => '',
        'time'     => '9:00 – 16:00',
        'location' => 'Praha',
        'price'    => '5 500 Kč',
        'type'     => 'seminar',
        'image'    => 'images/gallery/5.jpg',
    ],
    [
        'title'    => 'ON-LINE WEBINÁŘ: Jak řešit bolest zad bez prášků na bolest',
        'slug'     => 'on-line-webinar-jak-resit-bolest-zad-bez-prasku-na-bolest',
        'excerpt'  => 'Moderní vědecký přístup k pochopení a zvládání bolesti zad — praktické strategie ihned použitelné.',
        'content'  => "<p>Online webinář o vědě o bolesti a praktických strategiích bez prášků.</p>",
        'date'     => '2026-09-20',
        'date_end' => '',
        'time'     => '9:00 – 12:30',
        'location' => 'Online webinář',
        'price'    => '1 800 Kč',
        'type'     => 'webinar',
        'image'    => 'images/gallery/7.jpg',
    ],
    [
        'title'    => 'ON-LINE WEBINÁŘ: Bolest, biomechanika a držení těla',
        'slug'     => 'on-line-webinar-bolest-biomechanika-a-drzeni-tela',
        'excerpt'  => 'Komplexní rozbor vědeckých důkazů i praxe na téma bolesti, biomechaniky a držení těla.',
        'content'  => "<p>Online webinář o držení těla, biomechanice a bolesti — mýty vs. evidence.</p>",
        'date'     => '2026-10-11',
        'date_end' => '',
        'time'     => '9:00 – 12:30',
        'location' => 'Online webinář',
        'price'    => '1 590 Kč',
        'type'     => 'webinar',
        'image'    => 'images/gallery/9.jpg',
    ],
    [
        'title'    => 'Prevence a rekondice základních silových cviků',
        'slug'     => 'prevence-a-rekondice-zakladnich-silovych-cviku',
        'excerpt'  => 'Celodenní kurz o dřepu, mrtvém tahu a bench-pressu — technika, biomechanika, prevence a návrat po bolesti.',
        'content'  => "<p>Celodenní kurz o prevenci zranění u dřepu, mrtvého tahu a bench-pressu.</p>",
        'date'     => '2026-10-24',
        'date_end' => '',
        'time'     => '9:00 – 18:00',
        'location' => 'Praha',
        'price'    => '5 900 Kč',
        'type'     => 'seminar',
        'image'    => 'images/gallery/2.jpg',
    ],
];

foreach ($courses as $course) {
    $existing = get_page_by_path($course['slug'], OBJECT, 'kurz');
    if ($existing) {
        echo "· Course exists: {$course['title']}\n";
        continue;
    }
    $id = wp_insert_post([
        'post_type'    => 'kurz',
        'post_status'  => 'publish',
        'post_title'   => $course['title'],
        'post_name'    => $course['slug'],
        'post_excerpt' => $course['excerpt'],
        'post_content' => $course['content'],
    ]);
    if (is_wp_error($id) || !$id) {
        continue;
    }
    update_post_meta($id, 'course_date', $course['date']);
    update_post_meta($id, 'course_date_end', $course['date_end']);
    update_post_meta($id, 'course_time', $course['time']);
    update_post_meta($id, 'course_location', $course['location']);
    update_post_meta($id, 'course_price', $course['price']);
    update_post_meta($id, $seed_tag, '1');
    wp_set_object_terms($id, $course['type'], 'typ_kurzu');
    $img = $sideload($theme_dir . '/assets/' . $course['image'], $id, $course['title']);
    if ($img) {
        set_post_thumbnail($id, $img);
    }
    echo "✓ Course: {$course['title']}\n";
}

// ── Sample articles ───────────────────────────────────────────────────────────

$articles = [
    [
        'title'   => 'Proč univerzální tréninkový plán nestačí',
        'slug'    => 'proc-univerzalni-plan-nestaci-demo',
        'excerpt' => 'Každý klient má jiné limity, historii a cíle. Proč šablony často selhávají.',
        'days'    => 3,
        'image'   => 'images/gallery/6.jpg',
        'content' => "<h2>Individuální přístup není luxus</h2><p>Univerzální programy vypadají atraktivně — jsou jednoduché na prodej i na vedení. V praxi ale často přehlížejí asymetrie, historii zranění i skutečné cíle klienta.</p><h2>Co dělat místo šablony</h2><p>Začněte diagnostikou a otázkami. Teprve potom stavte progresi. Cviky jsou až třetí krok.</p><!-- wp:coretraining/info-box {\"variant\":\"tip\",\"content\":\"Nejdřív pochopte, proč se tělo pohybuje tak, jak se pohybuje.\"} /-->",
    ],
    [
        'title'   => 'Bolest zad: kdy je silový trénink součástí řešení',
        'slug'    => 'bolest-zad-silovy-trenink-demo',
        'excerpt' => 'Strach z pohybu často škodí víc než samotná bolest. Jak přemýšlet o návratu k zátěži.',
        'days'    => 12,
        'image'   => 'images/gallery/10.jpg',
        'content' => "<h2>Bolest není diagnóza</h2><p>Bolest zad je symptom. Bez kontextu (anamnéza, pohybové vzory, zátěž) je těžké rozhodnout, co pomůže.</p><h3>Role trenéra</h3><p>Trenér není lékař. Může ale bezpečně vést klienta k pohybu, pokud spolupracuje s odborníky a rozumí limitům.</p><!-- wp:coretraining/quote {\"content\":\"Samotná síla nestačí. Stejně důležité je pochopit, jak tělo funguje.\",\"author\":\"Martin Snášel\"} /-->",
    ],
    [
        'title'   => 'Funkční diagnostika jako základ smysluplného tréninku',
        'slug'    => 'funkcni-diagnostika-zaklad-demo',
        'excerpt' => 'Co diagnostika je — a co není. Proč ji řadit hned za vstupní rozhovor.',
        'days'    => 20,
        'image'   => 'images/gallery/11.jpg',
        'content' => "<h2>Co zjišťujeme</h2><ul><li>Asymetrie</li><li>Dysbalance</li><li>Rizikové oblasti</li></ul><h2>Výstup</h2><p>Konkrétní doporučení pro trénink, rekondici nebo individuální spolupráci.</p>",
    ],
    [
        'title'   => 'Evidence-based přístup v každodenní praxi',
        'slug'    => 'evidence-based-praxe-demo',
        'excerpt' => 'Jak číst studie bez akademického snobismu — a jak je použít u klienta.',
        'days'    => 28,
        'image'   => 'images/gallery/13.jpg',
        'content' => "<h2>Věda není dogma</h2><p>Evidence-based přístup znamená vážit důkazy, zkušenost a kontext klienta. Ne kopírovat jednu studii do tréninku.</p>",
    ],
    [
        'title'   => 'Jak vybrat kurz CoreTraining',
        'slug'    => 'jak-vybrat-kurz-demo',
        'excerpt' => 'Seminář, workshop, webinář, nebo přednáška hosta? Stručný průvodce.',
        'days'    => 35,
        'image'   => 'images/gallery/1.jpg',
        'content' => "<h2>Formáty</h2><p>Prezenční semináře jdou do hloubky. Workshopy jsou praktičtější. Webináře šetří čas. Přednášky hostů otevírají nový úhel.</p><h2>Tip</h2><p>Pokud si nejste jistí, napište na info@coretraining.cz — rádi poradíme.</p>",
    ],
    [
        'title'   => 'CORE Centrum: místo, kde se praxe potkává se vzděláváním',
        'slug'    => 'core-centrum-demo',
        'excerpt' => 'Proč má CoreTraining fyzické zázemí v Letňanech a co tam najdete.',
        'days'    => 42,
        'image'   => 'images/gallery/2.jpg',
        'content' => "<h2>Jedna střecha</h2><p>Semináře, diagnostika i individuální práce probíhají v CORE Centru. Podrobnosti na stránce Studio.</p>",
    ],
];

foreach ($articles as $article) {
    $existing = get_page_by_path($article['slug'], OBJECT, 'post');
    if ($existing) {
        echo "· Article exists: {$article['title']}\n";
        continue;
    }
    $id = wp_insert_post([
        'post_type'     => 'post',
        'post_status'   => 'publish',
        'post_title'    => $article['title'],
        'post_name'     => $article['slug'],
        'post_excerpt'  => $article['excerpt'],
        'post_content'  => $article['content'],
        'post_date'     => wp_date('Y-m-d H:i:s', strtotime('-' . $article['days'] . ' days')),
        'post_category' => $clanky ? [(int) $clanky->term_id] : [],
    ]);
    if (is_wp_error($id) || !$id) {
        continue;
    }
    update_post_meta($id, $seed_tag, '1');
    $img = $sideload($theme_dir . '/assets/' . $article['image'], $id, $article['title']);
    if ($img) {
        set_post_thumbnail($id, $img);
    }
    echo "✓ Article: {$article['title']}\n";
}

flush_rewrite_rules(false);

echo "\n=== Hotovo ===\n";
echo "Otevři: " . home_url('/') . "\n";
echo "Admin:  " . admin_url() . "\n";
