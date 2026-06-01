<?php
/**
 * Template: Odpověď na inzerát (/mam-zajem/{id}/)
 */

$job_id = sanitize_text_field(get_query_var('callup_job_id'));

if (!$job_id) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part('404');
    exit;
}

// Načti detail inzerátu pro kontext
$result = callup_api_post('rpc/clp_job_get_detail', ['job_id_' => $job_id]);

if (is_wp_error($result) || empty($result)) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part('404');
    exit;
}

$job = is_array($result) && isset($result[0]) ? $result[0] : $result;

global $callup_page_title;
$callup_page_title = 'Mám zájem — ' . ($job['title'] ?? '');

get_header();
?>

<div class="container">
    <a href="<?php echo esc_url(home_url('/inzerat/' . esc_attr($job_id) . '/')); ?>" class="back-link">← Zpět na detail nabídky</a>

    <div class="form-page">
        <h1 class="form-page__title">Mám zájem</h1>
        <?php if (!empty($job['title'])): ?>
            <p class="form-page__subtitle"><?php echo esc_html($job['title']); ?></p>
        <?php endif; ?>

        <div id="form-message" class="alert" style="display:none"></div>

        <form id="job-response-form" class="callup-form" novalidate>
            <?php wp_nonce_field('callup_job_response', 'callup_nonce'); ?>
            <input type="hidden" name="job_id" value="<?php echo esc_attr($job_id); ?>">

            <!-- Honeypot -->
            <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">

            <div class="form-row">
                <div class="form-group">
                    <label for="first_name" class="form-label">Jméno <span class="required">*</span></label>
                    <input type="text" id="first_name" name="first_name" class="form-input" required autocomplete="given-name">
                    <span class="form-error" id="first_name-error"></span>
                </div>
                <div class="form-group">
                    <label for="last_name" class="form-label">Příjmení <span class="required">*</span></label>
                    <input type="text" id="last_name" name="last_name" class="form-input" required autocomplete="family-name">
                    <span class="form-error" id="last_name-error"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">E-mail <span class="required">*</span></label>
                <input type="email" id="email" name="email" class="form-input" required autocomplete="email">
                <span class="form-error" id="email-error"></span>
            </div>

            <div class="form-group">
                <label for="mobile" class="form-label">Telefon <span class="required">*</span></label>
                <input type="tel" id="mobile" name="mobile" class="form-input" required autocomplete="tel"
                       placeholder="+420XXXXXXXXX">
                <span class="form-hint">Formát: +420XXXXXXXXX nebo 9 číslic bez předvolby</span>
                <span class="form-error" id="mobile-error"></span>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Heslo <span class="form-optional">(nepovinné – pro vytvoření účtu)</span></label>
                <input type="password" id="password" name="password" class="form-input" autocomplete="new-password"
                       minlength="8">
                <span class="form-hint">Minimálně 8 znaků. Vyplňte, pokud chcete zároveň vytvořit účet.</span>
                <span class="form-error" id="password-error"></span>
            </div>

            <div class="form-group">
                <label for="response_text" class="form-label">Zpráva <span class="form-optional">(nepovinná)</span></label>
                <textarea id="response_text" name="response_text" class="form-input form-textarea" rows="4"></textarea>
            </div>

            <div class="form-group form-group--checkbox">
                <label class="form-checkbox-label">
                    <input type="checkbox" name="gdpr" id="gdpr" class="form-checkbox" required>
                    <span>Souhlasím se <a href="<?php echo esc_url(home_url('/podminky-pouzivani-sluzeb/')); ?>" target="_blank">zpracováním osobních údajů</a></span>
                </label>
                <span class="form-error" id="gdpr-error"></span>
            </div>

            <button type="submit" id="submit-btn" class="btn btn--primary btn--lg btn--full">
                Odeslat zájem
            </button>
        </form>
    </div>
</div>

<?php get_footer(); ?>
