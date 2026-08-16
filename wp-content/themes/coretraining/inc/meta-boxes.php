<?php
/**
 * Post meta for kurz and reference CPTs
 */

const CORETRAINING_COURSE_META_KEYS = [
    'course_date'      => 'string',
    'course_date_end'  => 'string',
    'course_time'      => 'string',
    'course_location'  => 'string',
    'course_price'     => 'string',
];

const CORETRAINING_REFERENCE_META_KEYS = [
    'reference_name'   => 'string',
    'reference_rating' => 'integer',
];

add_action('init', function (): void {
    foreach (CORETRAINING_COURSE_META_KEYS as $key => $type) {
        register_post_meta('kurz', $key, [
            'type'              => $type,
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => $type === 'integer' ? 'absint' : 'sanitize_text_field',
            'auth_callback'     => static fn () => current_user_can('edit_posts'),
        ]);
    }

    foreach (CORETRAINING_REFERENCE_META_KEYS as $key => $type) {
        register_post_meta('reference', $key, [
            'type'              => $type,
            'single'            => true,
            'show_in_rest'      => true,
            'sanitize_callback' => $type === 'integer' ? 'absint' : 'sanitize_text_field',
            'auth_callback'     => static fn () => current_user_can('edit_posts'),
        ]);
    }
});

add_action('add_meta_boxes', function (): void {
    add_meta_box(
        'coretraining_course_details',
        __('Detaily kurzu', 'coretraining'),
        'coretraining_render_course_meta_box',
        'kurz',
        'normal',
        'high'
    );

    add_meta_box(
        'coretraining_reference_details',
        __('Detaily reference', 'coretraining'),
        'coretraining_render_reference_meta_box',
        'reference',
        'normal',
        'high'
    );
});

function coretraining_render_course_meta_box(WP_Post $post): void {
    wp_nonce_field('coretraining_save_course_meta', 'coretraining_course_meta_nonce');

    $fields = [
        'course_date'     => ['label' => __('Datum od', 'coretraining'), 'type' => 'date'],
        'course_date_end' => ['label' => __('Datum do (volitelné)', 'coretraining'), 'type' => 'date'],
        'course_time'     => ['label' => __('Čas', 'coretraining'), 'type' => 'text', 'placeholder' => '9:00 – 16:00'],
        'course_location' => ['label' => __('Místo', 'coretraining'), 'type' => 'text'],
        'course_price'    => ['label' => __('Cena', 'coretraining'), 'type' => 'text', 'placeholder' => '5 500 Kč'],
    ];

    echo '<table class="form-table"><tbody>';
    foreach ($fields as $key => $field) {
        $value = get_post_meta($post->ID, $key, true);
        echo '<tr><th scope="row"><label for="' . esc_attr($key) . '">' . esc_html($field['label']) . '</label></th><td>';
        printf(
            '<input type="%s" id="%s" name="%s" value="%s" class="regular-text"%s>',
            esc_attr($field['type']),
            esc_attr($key),
            esc_attr($key),
            esc_attr((string) $value),
            !empty($field['placeholder']) ? ' placeholder="' . esc_attr($field['placeholder']) . '"' : ''
        );
        echo '</td></tr>';
    }
    echo '</tbody></table>';
    echo '<p class="description">' . esc_html__('Krátký popis vyplňte v poli Perex. Plný program pište do editoru.', 'coretraining') . '</p>';
}

function coretraining_render_reference_meta_box(WP_Post $post): void {
    wp_nonce_field('coretraining_save_reference_meta', 'coretraining_reference_meta_nonce');

    $name   = get_post_meta($post->ID, 'reference_name', true);
    $rating = (int) get_post_meta($post->ID, 'reference_rating', true);
    ?>
    <table class="form-table"><tbody>
        <tr>
            <th scope="row"><label for="reference_name"><?php esc_html_e('Jméno (zobrazeno na webu)', 'coretraining'); ?></label></th>
            <td><input type="text" id="reference_name" name="reference_name" value="<?php echo esc_attr((string) $name); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th scope="row"><label for="reference_rating"><?php esc_html_e('Hodnocení (1–5)', 'coretraining'); ?></label></th>
            <td><input type="number" id="reference_rating" name="reference_rating" value="<?php echo esc_attr((string) $rating); ?>" min="1" max="5" step="1"></td>
        </tr>
    </tbody></table>
    <p class="description"><?php esc_html_e('Text citace pište do editoru. Pořadí nastavte přes Atributy stránky → Pořadí.', 'coretraining'); ?></p>
    <?php
}

add_action('save_post_kurz', 'coretraining_save_course_meta');
add_action('save_post_reference', 'coretraining_save_reference_meta');

function coretraining_save_course_meta(int $post_id): void {
    if (!isset($_POST['coretraining_course_meta_nonce'])
        || !wp_verify_nonce($_POST['coretraining_course_meta_nonce'], 'coretraining_save_course_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    foreach (array_keys(CORETRAINING_COURSE_META_KEYS) as $key) {
        if (!isset($_POST[$key])) {
            continue;
        }
        update_post_meta($post_id, $key, sanitize_text_field(wp_unslash($_POST[$key])));
    }
}

function coretraining_save_reference_meta(int $post_id): void {
    if (!isset($_POST['coretraining_reference_meta_nonce'])
        || !wp_verify_nonce($_POST['coretraining_reference_meta_nonce'], 'coretraining_save_reference_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['reference_name'])) {
        update_post_meta($post_id, 'reference_name', sanitize_text_field(wp_unslash($_POST['reference_name'])));
    }
    if (isset($_POST['reference_rating'])) {
        $rating = max(1, min(5, (int) $_POST['reference_rating']));
        update_post_meta($post_id, 'reference_rating', $rating);
    }
}
