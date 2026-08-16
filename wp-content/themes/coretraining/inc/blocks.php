<?php
/**
 * Gutenberg blocks: quote, info-box
 */

add_action('init', function (): void {
    wp_register_script(
        'coretraining-blocks-editor',
        get_template_directory_uri() . '/assets/js/blocks-editor.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'],
        CORETRAINING_VERSION,
        true
    );

    $blocks = ['quote', 'info-box'];

    foreach ($blocks as $block) {
        register_block_type(get_template_directory() . '/blocks/' . $block);
    }
});

function coretraining_render_quote_block(array $attributes): string {
    $content = trim((string) ($attributes['content'] ?? ''));
    $author  = trim((string) ($attributes['author'] ?? ''));

    if ($content === '') {
        return '';
    }

    ob_start();
    ?>
    <blockquote class="wp-block-coretraining-quote ct-quote">
        <p><?php echo esc_html($content); ?></p>
        <?php if ($author !== '') : ?>
            <cite class="ct-quote__author"><?php echo esc_html($author); ?></cite>
        <?php endif; ?>
    </blockquote>
    <?php
    return (string) ob_get_clean();
}

function coretraining_render_info_box_block(array $attributes): string {
    $content = trim((string) ($attributes['content'] ?? ''));
    $variant = (string) ($attributes['variant'] ?? 'tip');

    if ($content === '') {
        return '';
    }

    $labels = [
        'tip'     => __('Tip', 'coretraining'),
        'warning' => __('Upozornění', 'coretraining'),
        'summary' => __('Shrnutí', 'coretraining'),
    ];
    $label = $labels[$variant] ?? $labels['tip'];

    ob_start();
    ?>
    <aside class="wp-block-coretraining-info-box ct-info-box ct-info-box--<?php echo esc_attr($variant); ?>" role="note">
        <p class="ct-info-box__label"><?php echo esc_html($label); ?></p>
        <div class="ct-info-box__content"><?php echo wp_kses_post(wpautop($content)); ?></div>
    </aside>
    <?php
    return (string) ob_get_clean();
}
