<?php
/**
 * Permalinks: /clanky/{slug}/ and /clanky/ archive
 */

add_action('init', function (): void {
    add_rewrite_tag('%clanky_archive%', '([0-1])');
    add_rewrite_rule('^clanky/page/([0-9]+)/?$', 'index.php?clanky_archive=1&paged=$matches[1]', 'top');
    add_rewrite_rule('^clanky/?$', 'index.php?clanky_archive=1', 'top');
    add_rewrite_rule('^clanky/([^/]+)/?$', 'index.php?name=$matches[1]', 'top');
});

add_filter('query_vars', function (array $vars): array {
    $vars[] = 'clanky_archive';
    return $vars;
});

add_filter('post_link', function (string $permalink, WP_Post $post, bool $leavename): string {
    if ($post->post_type !== 'post' || $post->post_status === 'draft') {
        return $permalink;
    }

    if (!coretraining_is_clanek($post)) {
        return $permalink;
    }

    $slug = $leavename ? '%postname%' : $post->post_name;
    return home_url(user_trailingslashit('clanky/' . $slug));
}, 10, 3);

add_action('pre_get_posts', function (WP_Query $query): void {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if ((int) get_query_var('clanky_archive') === 1) {
        $query->set('post_type', 'post');
        $query->set('category_name', 'clanky');
        $query->set('posts_per_page', 12);
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
    }

    if ($query->is_search()) {
        $query->set('post_type', ['post', 'kurz']);
    }
});

add_filter('template_include', function (string $template): string {
    if ((int) get_query_var('clanky_archive') === 1) {
        $custom = locate_template('archive-clanky.php');
        if ($custom) {
            return $custom;
        }
    }
    return $template;
});

add_action('after_switch_theme', function (): void {
    if (!term_exists('clanky', 'category')) {
        wp_insert_term('Články', 'category', ['slug' => 'clanky']);
    }
    flush_rewrite_rules();
});
