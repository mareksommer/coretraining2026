<?php
/**
 * Replace demo articles with the latest posts from live coretraining.cz.
 *
 *   wp eval-file wp-content/themes/coretraining/bin/sync-live-articles.php --allow-root
 */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run via: wp eval-file ...\n");
    exit(1);
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$seed_tag  = '_coretraining_seed';
$json_path = __DIR__ . '/live-articles.json';

if (!file_exists($json_path)) {
    fwrite(STDERR, "Missing {$json_path}\n");
    exit(1);
}

$articles = json_decode((string) file_get_contents($json_path), true);
if (!is_array($articles) || !$articles) {
    fwrite(STDERR, "Invalid live-articles.json\n");
    exit(1);
}

if (!term_exists('clanky', 'category')) {
    wp_insert_term('Články', 'category', ['slug' => 'clanky']);
}
$clanky = get_term_by('slug', 'clanky', 'category');
$cat_id = $clanky ? (int) $clanky->term_id : 0;

$sideload_url = static function (string $url, int $parent_id, string $title): int {
    $tmp = download_url($url);
    if (is_wp_error($tmp)) {
        echo "  ! Image download failed: {$url} — {$tmp->get_error_message()}\n";
        return 0;
    }
    $path = parse_url($url, PHP_URL_PATH) ?: 'article.jpg';
    $name = basename(rawurldecode($path));
    $name = preg_replace('/[^a-zA-Z0-9._-]/', '-', $name) ?: 'article.jpg';
    $id = media_handle_sideload([
        'name'     => $name,
        'tmp_name' => $tmp,
    ], $parent_id, $title);
    if (is_wp_error($id)) {
        @unlink($tmp);
        echo "  ! Image sideload failed: {$name} — {$id->get_error_message()}\n";
        return 0;
    }
    return (int) $id;
};

// Remove seed / demo articles so homepage shows only live ones.
$remove = get_posts([
    'post_type'      => 'post',
    'post_status'    => 'any',
    'posts_per_page' => -1,
    'meta_key'       => $seed_tag,
]);
foreach ($remove as $post) {
    // Keep if slug is in the live import list (e.g. regenerace already seeded).
    $keep = false;
    foreach ($articles as $a) {
        if ($post->post_name === $a['slug']) {
            $keep = true;
            break;
        }
    }
    if ($keep) {
        continue;
    }
    wp_delete_post($post->ID, true);
    echo "✓ Removed seed article #{$post->ID} {$post->post_title}\n";
}

foreach (['ahoj-vsichni'] as $slug) {
    $existing = get_page_by_path($slug, OBJECT, 'post');
    if ($existing) {
        wp_delete_post($existing->ID, true);
        echo "✓ Removed leftover /{$slug}/\n";
    }
}

foreach ($articles as $article) {
    $existing = get_page_by_path($article['slug'], OBJECT, 'post');
    $datetime = $article['datetime'] ?? ($article['date'] . ' 10:00:00');

    $postarr = [
        'post_type'     => 'post',
        'post_status'   => 'publish',
        'post_title'    => $article['title'],
        'post_name'     => $article['slug'],
        'post_excerpt'  => $article['excerpt'] ?? '',
        'post_content'  => $article['content'] ?? '',
        'post_date'     => $datetime,
        'post_date_gmt' => get_gmt_from_date($datetime),
        'post_category' => $cat_id ? [$cat_id] : [],
    ];

    if ($existing) {
        $postarr['ID'] = $existing->ID;
        $id = wp_update_post($postarr, true);
        echo "· Updated: {$article['title']}\n";
    } else {
        $id = wp_insert_post($postarr, true);
        echo "✓ Created: {$article['title']}\n";
    }

    if (is_wp_error($id) || !$id) {
        echo "! Failed: {$article['title']}\n";
        continue;
    }

    update_post_meta($id, $seed_tag, 'live-articles');
    if ($cat_id) {
        wp_set_post_categories($id, [$cat_id]);
    }

    if (!empty($article['img']) && !has_post_thumbnail($id)) {
        $img = $sideload_url($article['img'], (int) $id, $article['title']);
        if ($img) {
            set_post_thumbnail($id, $img);
            echo "  ✓ Thumbnail\n";
        }
    } elseif (!empty($article['img']) && has_post_thumbnail($id)) {
        // Refresh featured if regenerace was using a different image — leave as-is.
        echo "  · Thumbnail exists\n";
    }
}

echo "\n=== Články synchronizovány ===\n";
$q = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 10,
    'category_name'  => 'clanky',
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
while ($q->have_posts()) {
    $q->the_post();
    echo get_the_date('Y-m-d') . ' | ' . get_the_title() . "\n";
}
wp_reset_postdata();
echo "Homepage: " . home_url('/') . "\n";
echo "Archiv: " . home_url('/clanky/') . "\n";
