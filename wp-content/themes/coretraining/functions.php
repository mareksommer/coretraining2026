<?php
/**
 * coretraining theme functions
 */

// ── Theme setup ───────────────────────────────────────────────────────────────

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    load_theme_textdomain('coretraining', get_template_directory() . '/languages');
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'coretraining-theme',
        get_template_directory_uri() . '/assets/css/theme.css',
        [],
        '1.0.0'
    );
    wp_enqueue_script(
        'coretraining-forms',
        get_template_directory_uri() . '/assets/js/forms.js',
        [],
        '1.0.0',
        true
    );
    wp_localize_script('coretraining-forms', 'coretrainingData', [
        'restUrl' => rest_url('coretraining/v1/'),
        'nonce'   => wp_create_nonce('wp_rest'),
    ]);
});

// ── Document title ────────────────────────────────────────────────────────────

add_filter('document_title_parts', function (array $parts): array {
    global $coretraining_page_title;
    if (!empty($coretraining_page_title)) {
        $parts['title'] = $coretraining_page_title;
    }
    return $parts;
});

add_action('wp_head', function () {
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

// ── GTM ───────────────────────────────────────────────────────────────────────

add_action('wp_head', function () {
    $gtm = getenv('GTM_CONTAINER_ID');
    if (!$gtm) return;
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

add_action('wp_body_open', function () {
    $gtm = getenv('GTM_CONTAINER_ID');
    if (!$gtm) return;
    ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($gtm); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <?php
});

// ── Rewrite rules ─────────────────────────────────────────────────────────────

add_action('init', function () {
    add_rewrite_rule(
        '^inzerat/([^/]+)/?$',
        'index.php?coretraining_page=inzerat&coretraining_job_id=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^mam-zajem/([^/]+)/?$',
        'index.php?coretraining_page=mam-zajem&coretraining_job_id=$matches[1]',
        'top'
    );
});

add_filter('query_vars', function (array $vars): array {
    $vars[] = 'coretraining_page';
    $vars[] = 'coretraining_job_id';
    return $vars;
});

add_filter('template_include', function (string $template): string {
    $page = get_query_var('coretraining_page');
    if ($page === 'inzerat') {
        return get_template_directory() . '/template-inzerat.php';
    }
    if ($page === 'mam-zajem') {
        return get_template_directory() . '/template-mam-zajem.php';
    }
    return $template;
});

// ── No-cache headers for dynamic pages ───────────────────────────────────────

add_action('template_redirect', function () {
    $coretraining_page = get_query_var('coretraining_page');
    $dynamic = $coretraining_page
        || is_front_page()
        || is_home()
        || is_page(['registrace', 'uspesna-registrace']);

    if ($dynamic) {
        nocache_headers();
    }

    // Guard: /uspesna-registrace/ requires registration cookie
    if (is_page('uspesna-registrace') && empty($_COOKIE['coretraining_reg_success'])) {
        wp_redirect(home_url('/registrace/'));
        exit;
    }
});

function coretraining_jwt_ttl(string $token): int {
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return 3600;
    }
    $padded  = str_pad(strtr($parts[1], '-_', '+/'), (int) ceil(strlen($parts[1]) / 4) * 4, '=');
    $payload = json_decode(base64_decode($padded), true);
    if (!empty($payload['exp'])) {
        return max(60, (int) $payload['exp'] - time() - 60);
    }
    return 3600;
}

// ── Rate limiting helper ──────────────────────────────────────────────────────

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

// ── Phone validation ──────────────────────────────────────────────────────────

function coretraining_validate_phone(string $phone): bool {
    return (bool) preg_match('/^\+?[0-9]{7,15}$/', $phone);
}

// ── REST API endpoints ────────────────────────────────────────────────────────

add_action('rest_api_init', function () {
    register_rest_route('coretraining/v1', '/job-response', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'coretraining_rest_job_response',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route('coretraining/v1', '/register', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'coretraining_rest_register',
        'permission_callback' => '__return_true',
    ]);
});

function coretraining_rest_job_response(WP_REST_Request $request): WP_REST_Response {
    if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
        return new WP_REST_Response(['success' => false, 'message' => 'Neplatný bezpečnostní token.'], 403);
    }

    // Honeypot
    if (!empty($request->get_param('website'))) {
        return new WP_REST_Response(['success' => true, 'message' => 'Vaše odpověď byla odeslána. Děkujeme!'], 200);
    }

    if (!coretraining_check_rate_limit()) {
        return new WP_REST_Response(['success' => false, 'message' => 'Příliš mnoho odeslaných formulářů. Zkuste to za chvíli.'], 429);
    }

    if (empty($request->get_param('gdpr'))) {
        return new WP_REST_Response(['success' => false, 'message' => 'Musíte souhlasit se zpracováním osobních údajů.'], 422);
    }

    $job_id     = sanitize_text_field((string) $request->get_param('job_id'));
    $first_name = sanitize_text_field((string) $request->get_param('first_name'));
    $last_name  = sanitize_text_field((string) $request->get_param('last_name'));
    $email      = sanitize_email((string) $request->get_param('email'));
    $mobile     = sanitize_text_field((string) $request->get_param('mobile'));
    $password   = (string) ($request->get_param('password') ?? '');
    $msg_text   = sanitize_textarea_field((string) ($request->get_param('response_text') ?? ''));

    if (!$job_id || !$first_name || !$last_name || !$email || !$mobile) {
        return new WP_REST_Response(['success' => false, 'message' => 'Vyplňte prosím všechna povinná pole.'], 422);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return new WP_REST_Response(['success' => false, 'message' => 'Neplatný formát e-mailové adresy.'], 422);
    }
    if (!coretraining_validate_phone($mobile)) {
        return new WP_REST_Response(['success' => false, 'message' => 'Neplatný formát telefonního čísla.'], 422);
    }
    if ($password !== '' && strlen($password) < 8) {
        return new WP_REST_Response(['success' => false, 'message' => 'Heslo musí mít alespoň 8 znaků.'], 422);
    }

    $result = coretraining_api_post('rpc/clp_job_response', [
        'job_id_'        => $job_id,
        'first_name_'    => $first_name,
        'last_name_'     => $last_name,
        'mobile_'        => $mobile,
        'email_'         => $email,
        'password_'      => $password,
        'response_text_' => $msg_text,
    ]);

    if (is_wp_error($result)) {
        return new WP_REST_Response(['success' => false, 'message' => $result->get_error_message()], 500);
    }

    $info = $result['info_text'] ?? $result['message'] ?? 'Vaše odpověď byla odeslána. Děkujeme!';
    return new WP_REST_Response(['success' => true, 'message' => $info], 200);
}

function coretraining_rest_register(WP_REST_Request $request): WP_REST_Response {
    if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
        return new WP_REST_Response(['success' => false, 'message' => 'Neplatný bezpečnostní token.'], 403);
    }

    // Honeypot
    if (!empty($request->get_param('website'))) {
        return new WP_REST_Response(['success' => true, 'redirect' => home_url('/uspesna-registrace/')], 200);
    }

    if (!coretraining_check_rate_limit()) {
        return new WP_REST_Response(['success' => false, 'message' => 'Příliš mnoho pokusů. Zkuste to za chvíli.'], 429);
    }

    if (empty($request->get_param('gdpr'))) {
        return new WP_REST_Response(['success' => false, 'message' => 'Musíte souhlasit se zpracováním osobních údajů.'], 422);
    }

    $first_name  = sanitize_text_field((string) $request->get_param('first_name'));
    $last_name   = sanitize_text_field((string) $request->get_param('last_name'));
    $email       = sanitize_email((string) $request->get_param('email'));
    $mobile      = sanitize_text_field((string) $request->get_param('mobile'));
    $password    = (string) ($request->get_param('password') ?? '');
    $cost_center = sanitize_text_field((string) ($request->get_param('cost_center') ?? ''));

    if (!$first_name || !$last_name || !$email || !$mobile || !$password || !$cost_center) {
        return new WP_REST_Response(['success' => false, 'message' => 'Vyplňte prosím všechna povinná pole.'], 422);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return new WP_REST_Response(['success' => false, 'message' => 'Neplatný formát e-mailové adresy.'], 422);
    }
    if (!coretraining_validate_phone($mobile)) {
        return new WP_REST_Response(['success' => false, 'message' => 'Neplatný formát telefonního čísla.'], 422);
    }
    if (strlen($password) < 8) {
        return new WP_REST_Response(['success' => false, 'message' => 'Heslo musí mít alespoň 8 znaků.'], 422);
    }

    $result = coretraining_api_post('rpc/clp_person_reg', [
        'first_name_'  => $first_name,
        'last_name_'   => $last_name,
        'mobile_'      => $mobile,
        'email_'       => $email,
        'password_'    => $password,
        'cost_center_' => $cost_center,
    ]);

    if (is_wp_error($result)) {
        return new WP_REST_Response(['success' => false, 'message' => $result->get_error_message()], 500);
    }

    setcookie('coretraining_reg_success', '1', [
        'expires'  => time() + 5 * MINUTE_IN_SECONDS,
        'path'     => '/',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);

    return new WP_REST_Response(['success' => true, 'redirect' => home_url('/uspesna-registrace/')], 200);
}

// ── Admin settings (Nastavení → Coretraining) ──────────────────────────────────────

add_action('admin_menu', function () {
    add_options_page(
        'Coretraining',
        'Coretraining',
        'manage_options',
        'coretraining-settings',
        'coretraining_settings_page'
    );
});

add_action('admin_init', function () {
    register_setting('coretraining_options', 'coretraining_cost_centers', [
        'sanitize_callback' => function (string $val): string {
            $decoded = json_decode($val, true);
            return is_array($decoded) ? wp_json_encode($decoded) : '[]';
        },
    ]);
});

function coretraining_settings_page(): void {
    $cost_centers = get_option('coretraining_cost_centers', '[]');
    ?>
    <div class="wrap">
        <h1>Nastavení Coretraining</h1>
        <form method="post" action="options.php">
            <?php settings_fields('coretraining_options'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="coretraining_cost_centers">Chci pracovat jako</label>
                    </th>
                    <td>
                        <textarea
                            id="coretraining_cost_centers"
                            name="coretraining_cost_centers"
                            rows="12"
                            cols="60"
                            class="large-text code"
                        ><?php echo esc_textarea($cost_centers); ?></textarea>
                        <p class="description">
                            JSON pole ve formátu <code>[{"id": "...", "label": "..."}]</code>
                        </p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
