<?php
/**
 * Taxonomy: typ_kurzu
 */

add_action('init', function (): void {
    register_taxonomy('typ_kurzu', 'kurz', [
        'labels' => [
            'name'          => __('Typ kurzu', 'coretraining'),
            'singular_name' => __('Typ kurzu', 'coretraining'),
            'search_items'  => __('Hledat typy', 'coretraining'),
            'all_items'     => __('Všechny typy', 'coretraining'),
            'edit_item'     => __('Upravit typ', 'coretraining'),
            'update_item'   => __('Aktualizovat typ', 'coretraining'),
            'add_new_item'  => __('Přidat typ', 'coretraining'),
            'new_item_name' => __('Nový typ', 'coretraining'),
            'menu_name'     => __('Typ kurzu', 'coretraining'),
        ],
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'typ-kurzu', 'with_front' => false],
    ]);
});

add_action('after_switch_theme', function (): void {
    $terms = [
        'seminar'          => 'Seminář',
        'workshop'         => 'Workshop',
        'webinar'          => 'Webinář',
        'prednaska-hosta'  => 'Přednáška hosta',
    ];

    foreach ($terms as $slug => $name) {
        if (!term_exists($slug, 'typ_kurzu')) {
            wp_insert_term($name, 'typ_kurzu', ['slug' => $slug]);
        }
    }
});
