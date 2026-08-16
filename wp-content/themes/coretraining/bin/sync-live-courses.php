<?php
/**
 * Replace demo kurzy with the live autumn 2026 schedule.
 *
 *   wp eval-file wp-content/themes/coretraining/bin/sync-live-courses.php --allow-root
 */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run via: wp eval-file ...\n");
    exit(1);
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$seed_tag = '_coretraining_seed';

$sideload_url = static function (string $url, int $parent_id, string $title): int {
    $tmp = download_url($url);
    if (is_wp_error($tmp)) {
        echo "  ! Image download failed: {$url} — {$tmp->get_error_message()}\n";
        return 0;
    }
    $name = basename(parse_url($url, PHP_URL_PATH) ?: 'course.jpg');
    $name = preg_replace('/[^a-zA-Z0-9._-]/', '-', $name) ?: 'course.jpg';
    $file_array = [
        'name'     => $name,
        'tmp_name' => $tmp,
    ];
    $id = media_handle_sideload($file_array, $parent_id, $title);
    if (is_wp_error($id)) {
        @unlink($tmp);
        echo "  ! Image sideload failed: {$name} — {$id->get_error_message()}\n";
        return 0;
    }
    return (int) $id;
};

// Remove previous demo / seed courses (and any leftover demos by slug).
$to_remove = get_posts([
    'post_type'      => 'kurz',
    'post_status'    => 'any',
    'posts_per_page' => -1,
    'meta_key'       => $seed_tag,
]);
foreach ($to_remove as $post) {
    wp_delete_post($post->ID, true);
    echo "✓ Removed seed course #{$post->ID} {$post->post_title}\n";
}

$demo_slugs = [
    'funkcni-diagnostika-demo',
    'silovy-trenink-bez-bolesti-demo',
    'webinar-bolest-zad-demo',
    'prednaska-biomechanika-behu-demo',
    'uvod-do-fms-archiv-demo',
];
foreach ($demo_slugs as $slug) {
    $existing = get_page_by_path($slug, OBJECT, 'kurz');
    if ($existing) {
        wp_delete_post($existing->ID, true);
        echo "✓ Removed leftover demo /{$slug}/\n";
    }
}

$courses = [
    [
        'title'    => 'Prevence a rekondice ramenního kloubu a návrat do tréninku/sportu',
        'slug'     => 'prevence-a-rekondice-ramenniho-kloubu-a-navrat-do-treninku-sportu',
        'excerpt'  => 'Problematika bolestí a zranění ramenního kloubu u sportovců — prevence, biomechanika a postupný návrat do výkonu.',
        'content'  => "<p>Kurz o zraněních a bolestech ramenního kloubu u sportovců, především silových. Probíráme fáze rehabilitace a návratu do výkonu, cvičení a nastavení tréninku, která vedou k přetížení, i baterii testů pro rekondici.</p><h2>Program kurzu</h2><ul><li>Funkční anatomie a biomechanika ramenního kloubu a lopatek</li><li>Nejčastější zranění u sportovců</li><li>Provokační a manuální testy</li><li>Biomechanika tlaků, přítahů a shybu</li><li>Load management a návrat k bench-pressu a dalším cvikům</li></ul>",
        'date'     => '2026-09-12',
        'date_end' => '',
        'time'     => '9:00 – 18:00',
        'location' => 'Praha',
        'price'    => '5 500 Kč',
        'type'     => 'seminar',
        'image'    => 'https://coretraining.cz/wp-content/uploads/2020/10/122723166_288527622230708_6886792542550850780_n.jpg',
    ],
    [
        'title'    => 'Spondylolýza/Spondylolistéza bez mýtů: Jak vrátit sportovce zpět do výkonu',
        'slug'     => 'spondylolyza-spondylolisteza-bez-mytu-jak-vratit-sportovce-zpet-do-vykonu',
        'excerpt'  => 'Praktický kurz o spondylolýze a spondylolistéze — diagnostika, mýty, load management a návrat ke sportu.',
        'content'  => "<p>Jedinečný praktický kurz spojující evidence-based poznatky se silovým tréninkem, rehabem a rekondicí. Téma je v praxi často podhodnocené a plné mýtů.</p><h2>Na kurzu se dozvíte</h2><ul><li>Jak vzniká spondylolýza a jak interpretovat MRI/CT/RTG</li><li>Kdy má smysl korzet a jak dávkovat zátěž</li><li>Úpravy silového tréninku a návrat k běhu i sportu</li><li>Vztah FAI, kyčle a spondylolýzy</li><li>Reálné kazuistiky z praxe</li></ul>",
        'date'     => '2026-09-19',
        'date_end' => '',
        'time'     => '9:00 – 16:00',
        'location' => 'Praha',
        'price'    => '5 500 Kč',
        'type'     => 'seminar',
        'image'    => 'https://coretraining.cz/wp-content/uploads/2026/06/ChatGPT-Image-10.-6.-2026-18_20_31.png',
    ],
    [
        'title'    => 'ON-LINE WEBINÁŘ: Jak řešit bolest zad bez prášků na bolest',
        'slug'     => 'on-line-webinar-jak-resit-bolest-zad-bez-prasku-na-bolest',
        'excerpt'  => 'Moderní vědecký přístup k pochopení a zvládání bolesti zad — praktické strategie ihned použitelné.',
        'content'  => "<p>Prakticky zaměřený online webinář o současných vědeckých přístupech k bolesti a konkrétních postupech, které můžete začít používat ihned.</p><h2>Naučíte se</h2><ul><li>Proč bolest vzniká a proč někdy přetrvává</li><li>Práci se strachem z pohybu a stresem</li><li>Neuroplasticitu a vědu o bolesti v praxi</li><li>Relaxační techniky a mindfulness</li><li>Vliv spánku, životního stylu a vztahů</li></ul>",
        'date'     => '2026-09-20',
        'date_end' => '',
        'time'     => '9:00 – 12:30',
        'location' => 'Online webinář',
        'price'    => '1 800 Kč',
        'type'     => 'webinar',
        'image'    => 'https://coretraining.cz/wp-content/uploads/2020/10/pain-science.jpg',
    ],
    [
        'title'    => 'ON-LINE WEBINÁŘ: Bolest, biomechanika a držení těla',
        'slug'     => 'on-line-webinar-bolest-biomechanika-a-drzeni-tela',
        'excerpt'  => 'Komplexní rozbor vědeckých důkazů i praxe na téma bolesti, biomechaniky a držení těla.',
        'content'  => "<p>Online webinář o teorii „správného držení těla“, biomechanice a jejich vztahu k bolesti — s poznatky z praxe i evidence.</p><h2>Program</h2><ul><li>Historie a mýty správného držení těla</li><li>Zkřížené syndromy, křivky páteře, předsun hlavy</li><li>Asymetrie, svalové dysbalance a výkon</li><li>Kdy na biomechanice záleží víc a kdy méně</li><li>Kazuistiky z funkčních diagnostik</li></ul>",
        'date'     => '2026-10-11',
        'date_end' => '',
        'time'     => '9:00 – 12:30',
        'location' => 'Online webinář',
        'price'    => '1 590 Kč',
        'type'     => 'webinar',
        'image'    => 'https://coretraining.cz/wp-content/uploads/2015/10/Posture-Athletes.jpg',
    ],
    [
        'title'    => 'Prevence a rekondice základních silových cviků',
        'slug'     => 'prevence-a-rekondice-zakladnich-silovych-cviku',
        'excerpt'  => 'Celodenní kurz o dřepu, mrtvém tahu a bench-pressu — technika, biomechanika, prevence a návrat po bolesti.',
        'content'  => "<p>Komplexní celodenní kurz o prevenci zranění u dřepu, mrtvého tahu a bench-pressu — technika, biomechanika a postup při bolesti ramene, lokte, kyčle, kolene či zad.</p><h2>Cena zahrnuje</h2><ul><li>Manuál</li><li>Diplom o účasti</li><li>Praktický nácvik</li></ul>",
        'date'     => '2026-10-24',
        'date_end' => '',
        'time'     => '9:00 – 18:00',
        'location' => 'Praha',
        'price'    => '5 900 Kč',
        'type'     => 'seminar',
        'image'    => 'https://coretraining.cz/wp-content/uploads/2021/03/121017216_669135910667991_5799854916099251391_n.jpg',
    ],
];

foreach ($courses as $course) {
    $existing = get_page_by_path($course['slug'], OBJECT, 'kurz');
    if ($existing) {
        $id = $existing->ID;
        wp_update_post([
            'ID'           => $id,
            'post_title'   => $course['title'],
            'post_excerpt' => $course['excerpt'],
            'post_content' => $course['content'],
            'post_status'  => 'publish',
        ]);
        echo "· Updated existing: {$course['title']}\n";
    } else {
        $id = wp_insert_post([
            'post_type'    => 'kurz',
            'post_status'  => 'publish',
            'post_title'   => $course['title'],
            'post_name'    => $course['slug'],
            'post_excerpt' => $course['excerpt'],
            'post_content' => $course['content'],
        ]);
        if (is_wp_error($id) || !$id) {
            echo "! Failed: {$course['title']}\n";
            continue;
        }
        echo "✓ Created: {$course['title']}\n";
    }

    update_post_meta($id, 'course_date', $course['date']);
    update_post_meta($id, 'course_date_end', $course['date_end']);
    update_post_meta($id, 'course_time', $course['time']);
    update_post_meta($id, 'course_location', $course['location']);
    update_post_meta($id, 'course_price', $course['price']);
    update_post_meta($id, $seed_tag, 'live-2026-autumn');
    wp_set_object_terms($id, $course['type'], 'typ_kurzu');

    if (!has_post_thumbnail($id)) {
        $img = $sideload_url($course['image'], $id, $course['title']);
        if ($img) {
            set_post_thumbnail($id, $img);
            echo "  ✓ Thumbnail\n";
        }
    }
}

echo "\n=== Kurzy synchronizovány ===\n";
$list = get_posts([
    'post_type'      => 'kurz',
    'posts_per_page' => -1,
    'meta_key'       => 'course_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
]);
foreach ($list as $p) {
    $d = get_post_meta($p->ID, 'course_date', true);
    $l = get_post_meta($p->ID, 'course_location', true);
    echo "{$d} | {$l} | {$p->post_title}\n";
}
echo "Nejbližší na homepage: " . home_url('/') . "\n";
echo "Archiv: " . home_url('/kurzy/') . "\n";
