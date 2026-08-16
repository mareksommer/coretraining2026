<?php
/**
 * Custom post types: kurz, reference
 */

add_action('init', function (): void {
    register_post_type('kurz', [
        'labels' => [
            'name'               => __('Kurzy', 'coretraining'),
            'singular_name'      => __('Kurz', 'coretraining'),
            'add_new'            => __('Přidat kurz', 'coretraining'),
            'add_new_item'       => __('Přidat nový kurz', 'coretraining'),
            'edit_item'          => __('Upravit kurz', 'coretraining'),
            'new_item'           => __('Nový kurz', 'coretraining'),
            'view_item'          => __('Zobrazit kurz', 'coretraining'),
            'search_items'       => __('Hledat kurzy', 'coretraining'),
            'not_found'          => __('Žádné kurzy', 'coretraining'),
            'not_found_in_trash' => __('V koši nejsou žádné kurzy', 'coretraining'),
            'menu_name'          => __('Kurzy', 'coretraining'),
        ],
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => true,
        'has_archive'         => true,
        'rewrite'             => ['slug' => 'kurzy', 'with_front' => false],
        'menu_icon'           => 'dashicons-welcome-learn-more',
        'supports'            => ['title', 'editor', 'excerpt', 'thumbnail'],
        'capability_type'     => 'post',
    ]);

    register_post_type('reference', [
        'labels' => [
            'name'               => __('Reference', 'coretraining'),
            'singular_name'      => __('Reference', 'coretraining'),
            'add_new'            => __('Přidat referenci', 'coretraining'),
            'add_new_item'       => __('Přidat novou referenci', 'coretraining'),
            'edit_item'          => __('Upravit referenci', 'coretraining'),
            'new_item'           => __('Nová reference', 'coretraining'),
            'search_items'       => __('Hledat reference', 'coretraining'),
            'not_found'          => __('Žádné reference', 'coretraining'),
            'not_found_in_trash' => __('V koši nejsou žádné reference', 'coretraining'),
            'menu_name'          => __('Reference', 'coretraining'),
        ],
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => true,
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-format-quote',
        'supports'            => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'capability_type'     => 'post',
    ]);
});
