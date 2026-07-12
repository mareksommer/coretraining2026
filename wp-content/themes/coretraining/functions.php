<?php
/**
 * CoreTraining theme functions
 */

define('CORETRAINING_VERSION', '2.0.0');

// ── Theme setup ───────────────────────────────────────────────────────────────

add_action('after_setup_theme', function (): void {
    add_theme_support('title-tag');
    add_theme_support('custom-logo', [
        'height'      => 48,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    register_nav_menus([
        'primary' => __('Hlavní menu', 'coretraining'),
        'footer'  => __('Patička', 'coretraining'),
    ]);

    load_theme_textdomain('coretraining', get_template_directory() . '/languages');
});

// ── Assets ────────────────────────────────────────────────────────────────────

add_action('wp_enqueue_scripts', function (): void {
    wp_enqueue_style(
        'coretraining-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'coretraining-theme',
        get_template_directory_uri() . '/assets/css/theme.css',
        ['coretraining-fonts'],
        CORETRAINING_VERSION
    );

    wp_enqueue_script(
        'coretraining-theme',
        get_template_directory_uri() . '/assets/js/theme.js',
        [],
        CORETRAINING_VERSION,
        true
    );
});

// ── Document title & meta (per-template overrides via globals) ────────────────

add_filter('document_title_parts', function (array $parts): array {
    global $coretraining_page_title;
    if (!empty($coretraining_page_title)) {
        $parts['title'] = $coretraining_page_title;
    }
    return $parts;
});

add_action('wp_head', function (): void {
    global $coretraining_meta_description, $coretraining_og_title, $coretraining_og_description;

    if (!empty($coretraining_meta_description)) {
        echo '<meta name="description" content="' . esc_attr($coretraining_meta_description) . '">' . "\n";
    }
    if (!empty($coretraining_og_title)) {
        echo '<meta property="og:title" content="' . esc_attr($coretraining_og_title) . '">' . "\n";
    }
    if (!empty($coretraining_og_description)) {
        echo '<meta property="og:description" content="' . esc_attr($coretraining_og_description) . '">' . "\n";
    }
}, 5);

// ── GTM (GTM_CONTAINER_ID env) ────────────────────────────────────────────────

add_action('wp_head', function (): void {
    $gtm = getenv('GTM_CONTAINER_ID');
    if (!$gtm) {
        return;
    }
    ?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js($gtm); ?>');</script>
<!-- End Google Tag Manager -->
    <?php
}, 1);

add_action('wp_body_open', function (): void {
    $gtm = getenv('GTM_CONTAINER_ID');
    if (!$gtm) {
        return;
    }
    ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($gtm); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <?php
});

// ── Helpers (pro budoucí formuláře) ─────────────────────────────────────────

function coretraining_check_rate_limit(): bool {
    $ip    = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0')[0]);
    $key   = 'coretraining_rl_' . md5($ip);
    $count = (int) get_transient($key);
    if ($count >= 5) {
        return false;
    }
    set_transient($key, $count + 1, 10 * MINUTE_IN_SECONDS);
    return true;
}

function coretraining_validate_phone(string $phone): bool {
    return (bool) preg_match('/^\+?[0-9]{7,15}$/', $phone);
}
