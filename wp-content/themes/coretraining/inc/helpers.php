<?php
/**
 * Theme helpers
 */

function coretraining_is_clanek(WP_Post|int $post): bool {
    $post = get_post($post);
    if (!$post || $post->post_type !== 'post') {
        return false;
    }
    return has_category('clanky', $post);
}

function coretraining_asset_url(string $path): string {
    return get_template_directory_uri() . '/assets/' . ltrim($path, '/');
}

function coretraining_get_typ_kurzu_label(string $slug): string {
    $labels = [
        'seminar'         => 'Seminář',
        'workshop'        => 'Workshop',
        'webinar'         => 'Webinář',
        'prednaska-hosta' => 'Přednáška hosta',
    ];
    return $labels[$slug] ?? $slug;
}

function coretraining_get_course_meta(int $post_id): array {
    return [
        'date'      => (string) get_post_meta($post_id, 'course_date', true),
        'date_end'  => (string) get_post_meta($post_id, 'course_date_end', true),
        'time'      => (string) get_post_meta($post_id, 'course_time', true),
        'location'  => (string) get_post_meta($post_id, 'course_location', true),
        'price'     => (string) get_post_meta($post_id, 'course_price', true),
    ];
}

function coretraining_format_course_date(string $date, string $date_end = ''): string {
    if ($date === '') {
        return '';
    }

    $ts = strtotime($date);
    if (!$ts) {
        return $date;
    }

    $formatted = wp_date('j. n. Y', $ts);

    if ($date_end !== '' && $date_end !== $date) {
        $ts_end = strtotime($date_end);
        if ($ts_end) {
            $formatted .= ' – ' . wp_date('j. n. Y', $ts_end);
        }
    }

    return $formatted;
}

function coretraining_is_course_upcoming(int $post_id): bool {
    $date = (string) get_post_meta($post_id, 'course_date', true);
    if ($date === '') {
        return false;
    }
    return $date >= wp_date('Y-m-d');
}

function coretraining_get_course_badge(WP_Post $post): ?array {
    $terms = get_the_terms($post, 'typ_kurzu');
    if (empty($terms) || is_wp_error($terms)) {
        return null;
    }
    $term = $terms[0];
    return [
        'slug'  => $term->slug,
        'label' => coretraining_get_typ_kurzu_label($term->slug),
    ];
}

function coretraining_query_upcoming_courses(int $limit = 6): WP_Query {
    return new WP_Query([
        'post_type'      => 'kurz',
        'posts_per_page' => $limit,
        'meta_key'       => 'course_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => [
            [
                'key'     => 'course_date',
                'value'   => wp_date('Y-m-d'),
                'compare' => '>=',
                'type'    => 'DATE',
            ],
        ],
    ]);
}

function coretraining_query_past_courses(): WP_Query {
    return new WP_Query([
        'post_type'      => 'kurz',
        'posts_per_page' => -1,
        'meta_key'       => 'course_date',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
        'meta_query'     => [
            [
                'key'     => 'course_date',
                'value'   => wp_date('Y-m-d'),
                'compare' => '<',
                'type'    => 'DATE',
            ],
        ],
    ]);
}

function coretraining_get_references(): WP_Query {
    return new WP_Query([
        'post_type'      => 'reference',
        'posts_per_page' => -1,
        'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ]);
}

function coretraining_get_latest_clanky(int $limit = 6): WP_Query {
    return new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => $limit,
        'category_name'  => 'clanky',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ]);
}

function coretraining_render_stars(int $rating): void {
    $rating = max(0, min(5, $rating));
    echo '<span class="stars" aria-label="' . esc_attr(sprintf(__('%d z 5 hvězdiček', 'coretraining'), $rating)) . '">';
    for ($i = 1; $i <= 5; $i++) {
        $class = $i <= $rating ? 'stars__item stars__item--filled' : 'stars__item';
        echo '<span class="' . esc_attr($class) . '" aria-hidden="true">★</span>';
    }
    echo '</span>';
}

/**
 * Parse H2/H3 headings from HTML content for table of contents.
 *
 * @return array<int, array{id: string, text: string, level: int}>
 */
function coretraining_parse_headings(string $content): array {
    if (!preg_match_all('/<h([23])([^>]*)>(.*?)<\/h\1>/is', $content, $matches, PREG_SET_ORDER)) {
        return [];
    }

    $headings = [];
    $used_ids = [];

    foreach ($matches as $match) {
        $level = (int) $match[1];
        $text  = wp_strip_all_tags($match[3]);
        if ($text === '') {
            continue;
        }

        $id = sanitize_title($text);
        if ($id === '') {
            $id = 'section-' . (count($headings) + 1);
        }

        $base = $id;
        $i    = 2;
        while (in_array($id, $used_ids, true)) {
            $id = $base . '-' . $i;
            $i++;
        }
        $used_ids[] = $id;

        $headings[] = [
            'id'    => $id,
            'text'  => $text,
            'level' => $level,
        ];
    }

    return $headings;
}

function coretraining_add_heading_ids(string $content): string {
    return (string) preg_replace_callback(
        '/<h([23])([^>]*)>(.*?)<\/h\1>/is',
        function (array $match): string {
            static $used_ids = [];
            $text = wp_strip_all_tags($match[3]);
            $id   = sanitize_title($text);
            if ($id === '') {
                $id = 'section-' . (count($used_ids) + 1);
            }
            $base = $id;
            $i    = 2;
            while (in_array($id, $used_ids, true)) {
                $id = $base . '-' . $i;
                $i++;
            }
            $used_ids[] = $id;

            if (preg_match('/\sid=["\'][^"\']*["\']/', $match[2])) {
                return $match[0];
            }

            return sprintf(
                '<h%s%s id="%s">%s</h%s>',
                $match[1],
                $match[2],
                esc_attr($id),
                $match[3],
                $match[1]
            );
        },
        $content
    );
}
