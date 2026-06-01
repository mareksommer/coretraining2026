<?php
/**
 * Arenacallup theme functions
 */

define('CALLUP_API_BASE',   'https://alpha.stafio.cz/api/');
define('CALLUP_APP_DOMAIN', 'prilezitosti.stafio.cz');

// ── Theme setup ───────────────────────────────────────────────────────────────

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    load_theme_textdomain('arenacallup', get_template_directory() . '/languages');
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'callup-theme',
        get_template_directory_uri() . '/assets/css/theme.css',
        [],
        '1.0.0'
    );
    wp_enqueue_script(
        'callup-forms',
        get_template_directory_uri() . '/assets/js/forms.js',
        [],
        '1.0.0',
        true
    );
    wp_localize_script('callup-forms', 'callupData', [
        'restUrl' => rest_url('callup/v1/'),
        'nonce'   => wp_create_nonce('wp_rest'),
    ]);
});

// ── Document title ────────────────────────────────────────────────────────────

add_filter('document_title_parts', function (array $parts): array {
    global $callup_page_title;
    if (!empty($callup_page_title)) {
        $parts['title'] = $callup_page_title;
    }
    return $parts;
});

add_action('wp_head', function () {
    global $callup_meta_description, $callup_og_title, $callup_og_description;
    if (!empty($callup_meta_description)) {
        echo '<meta name="description" content="' . esc_attr($callup_meta_description) . '">' . "\n";
    }
    if (!empty($callup_og_title)) {
        echo '<meta property="og:title" content="' . esc_attr($callup_og_title) . '">' . "\n";
    }
    if (!empty($callup_og_description)) {
        echo '<meta property="og:description" content="' . esc_attr($callup_og_description) . '">' . "\n";
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
        'index.php?callup_page=inzerat&callup_job_id=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^mam-zajem/([^/]+)/?$',
        'index.php?callup_page=mam-zajem&callup_job_id=$matches[1]',
        'top'
    );
});

add_filter('query_vars', function (array $vars): array {
    $vars[] = 'callup_page';
    $vars[] = 'callup_job_id';
    return $vars;
});

add_filter('template_include', function (string $template): string {
    $page = get_query_var('callup_page');
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
    $callup_page = get_query_var('callup_page');
    $dynamic = $callup_page
        || is_front_page()
        || is_home()
        || is_page(['registrace', 'uspesna-registrace']);

    if ($dynamic) {
        nocache_headers();
    }

    // Guard: /uspesna-registrace/ requires registration cookie
    if (is_page('uspesna-registrace') && empty($_COOKIE['callup_reg_success'])) {
        wp_redirect(home_url('/registrace/'));
        exit;
    }
});

// ── JWT helper ────────────────────────────────────────────────────────────────

function callup_get_jwt(): string|WP_Error {
    $cached = get_transient('callup_jwt_token');
    if ($cached !== false) {
        return $cached;
    }

    $response = wp_remote_post(CALLUP_API_BASE . 'rpc/proxy_jwt_token_generate8', [
        'headers' => [
            'Content-Type'    => 'application/json',
            'Content-Profile' => 'platform',
            'Prefer'          => 'params=single-object',
        ],
        'body'    => wp_json_encode(['app_domain' => CALLUP_APP_DOMAIN]),
        'timeout' => 10,
    ]);

    if (is_wp_error($response)) {
        return $response;
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($code !== 200 || empty($body['success']) || empty($body['token'])) {
        $msg = $body['message'] ?? $body['info_text'] ?? 'Nepodařilo se získat přístupový token.';
        return new WP_Error('jwt_failed', $msg);
    }

    $token = $body['token'];
    $ttl   = callup_jwt_ttl($token);
    set_transient('callup_jwt_token', $token, $ttl);

    return $token;
}

function callup_jwt_ttl(string $token): int {
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

// ── API call helper ───────────────────────────────────────────────────────────

function callup_api_post(string $endpoint, array $params, string $profile = 'work_agency'): array|WP_Error {
    $jwt = callup_get_jwt();
    if (is_wp_error($jwt)) {
        return $jwt;
    }

    $response = wp_remote_post(CALLUP_API_BASE . $endpoint, [
        'headers' => [
            'Content-Type'    => 'application/json',
            'Content-Profile' => $profile,
            'Prefer'          => 'params=single-object',
            'Authorization'   => 'Bearer ' . $jwt,
        ],
        'body'    => wp_json_encode($params),
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        return $response;
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($code >= 400) {
        $msg = $body['message'] ?? $body['info_text'] ?? 'Chyba při komunikaci se serverem.';
        return new WP_Error('api_error', $msg);
    }

    return $body ?? [];
}

// ── Rate limiting helper ──────────────────────────────────────────────────────

function callup_check_rate_limit(): bool {
    $ip    = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0')[0]);
    $key   = 'callup_rl_' . md5($ip);
    $count = (int) get_transient($key);
    if ($count >= 5) {
        return false;
    }
    set_transient($key, $count + 1, 10 * MINUTE_IN_SECONDS);
    return true;
}

// ── Phone validation ──────────────────────────────────────────────────────────

function callup_validate_phone(string $phone): bool {
    return (bool) preg_match('/^\+?[0-9]{7,15}$/', $phone);
}

// ── REST API endpoints ────────────────────────────────────────────────────────

add_action('rest_api_init', function () {
    register_rest_route('callup/v1', '/job-response', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'callup_rest_job_response',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route('callup/v1', '/register', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'callup_rest_register',
        'permission_callback' => '__return_true',
    ]);
});

function callup_rest_job_response(WP_REST_Request $request): WP_REST_Response {
    if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
        return new WP_REST_Response(['success' => false, 'message' => 'Neplatný bezpečnostní token.'], 403);
    }

    // Honeypot
    if (!empty($request->get_param('website'))) {
        return new WP_REST_Response(['success' => true, 'message' => 'Vaše odpověď byla odeslána. Děkujeme!'], 200);
    }

    if (!callup_check_rate_limit()) {
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
    if (!callup_validate_phone($mobile)) {
        return new WP_REST_Response(['success' => false, 'message' => 'Neplatný formát telefonního čísla.'], 422);
    }
    if ($password !== '' && strlen($password) < 8) {
        return new WP_REST_Response(['success' => false, 'message' => 'Heslo musí mít alespoň 8 znaků.'], 422);
    }

    $result = callup_api_post('rpc/clp_job_response', [
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

function callup_rest_register(WP_REST_Request $request): WP_REST_Response {
    if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
        return new WP_REST_Response(['success' => false, 'message' => 'Neplatný bezpečnostní token.'], 403);
    }

    // Honeypot
    if (!empty($request->get_param('website'))) {
        return new WP_REST_Response(['success' => true, 'redirect' => home_url('/uspesna-registrace/')], 200);
    }

    if (!callup_check_rate_limit()) {
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
    if (!callup_validate_phone($mobile)) {
        return new WP_REST_Response(['success' => false, 'message' => 'Neplatný formát telefonního čísla.'], 422);
    }
    if (strlen($password) < 8) {
        return new WP_REST_Response(['success' => false, 'message' => 'Heslo musí mít alespoň 8 znaků.'], 422);
    }

    $result = callup_api_post('rpc/clp_person_reg', [
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

    setcookie('callup_reg_success', '1', [
        'expires'  => time() + 5 * MINUTE_IN_SECONDS,
        'path'     => '/',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);

    return new WP_REST_Response(['success' => true, 'redirect' => home_url('/uspesna-registrace/')], 200);
}

// ── Admin settings (Nastavení → Callup) ──────────────────────────────────────

add_action('admin_menu', function () {
    add_options_page(
        'Callup',
        'Callup',
        'manage_options',
        'callup-settings',
        'callup_settings_page'
    );
});

add_action('admin_init', function () {
    register_setting('callup_options', 'callup_cost_centers', [
        'sanitize_callback' => function (string $val): string {
            $decoded = json_decode($val, true);
            return is_array($decoded) ? wp_json_encode($decoded) : '[]';
        },
    ]);
});

function callup_settings_page(): void {
    $cost_centers = get_option('callup_cost_centers', '[]');
    ?>
    <div class="wrap">
        <h1>Nastavení Callup</h1>
        <form method="post" action="options.php">
            <?php settings_fields('callup_options'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="callup_cost_centers">Chci pracovat jako</label>
                    </th>
                    <td>
                        <textarea
                            id="callup_cost_centers"
                            name="callup_cost_centers"
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
