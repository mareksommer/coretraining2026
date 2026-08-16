<?php
/**
 * REST API: contact + course registration forms
 */

add_action('rest_api_init', function (): void {
    register_rest_route('coretraining/v1', '/contact', [
        'methods'             => 'POST',
        'callback'            => 'coretraining_rest_contact',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('coretraining/v1', '/course-registration', [
        'methods'             => 'POST',
        'callback'            => 'coretraining_rest_course_registration',
        'permission_callback' => '__return_true',
    ]);
});

function coretraining_rest_verify_request(WP_REST_Request $request): true|WP_Error {
    if (!coretraining_check_rate_limit()) {
        return new WP_Error('rate_limit', __('Příliš mnoho pokusů. Zkuste to prosím později.', 'coretraining'), ['status' => 429]);
    }

    $honeypot = trim((string) $request->get_param('website'));
    if ($honeypot !== '') {
        return new WP_Error('spam', __('Neplatný požadavek.', 'coretraining'), ['status' => 400]);
    }

    if (!$request->get_param('gdpr_consent')) {
        return new WP_Error('gdpr_required', __('Bez souhlasu se zpracováním údajů formulář nelze odeslat.', 'coretraining'), ['status' => 422]);
    }

    return true;
}

function coretraining_rest_contact(WP_REST_Request $request): WP_REST_Response|WP_Error {
    $check = coretraining_rest_verify_request($request);
    if (is_wp_error($check)) {
        return $check;
    }

    $name    = sanitize_text_field((string) $request->get_param('name'));
    $email   = sanitize_email((string) $request->get_param('email'));
    $phone   = preg_replace('/\s+/', '', sanitize_text_field((string) $request->get_param('phone')));
    $subject = sanitize_text_field((string) $request->get_param('subject'));
    $message = sanitize_textarea_field((string) $request->get_param('message'));

    if ($name === '' || $email === '' || $message === '') {
        return new WP_Error('validation', __('Vyplňte prosím všechna povinná pole.', 'coretraining'), ['status' => 422]);
    }
    if (!is_email($email)) {
        return new WP_Error('validation', __('Zadejte platný e-mail.', 'coretraining'), ['status' => 422]);
    }
    if ($phone !== '' && !coretraining_validate_phone($phone)) {
        return new WP_Error('validation', __('Zadejte platné telefonní číslo.', 'coretraining'), ['status' => 422]);
    }

    $mail_subject = $subject !== ''
        ? sprintf('[CoreTraining] %s — %s', $subject, $name)
        : sprintf('[CoreTraining] Kontakt — %s', $name);

    $body = "Jméno: {$name}\n";
    $body .= "E-mail: {$email}\n";
    if ($phone !== '') {
        $body .= "Telefon: {$phone}\n";
    }
    if ($subject !== '') {
        $body .= "Předmět: {$subject}\n";
    }
    $body .= "\nZpráva:\n{$message}\n";

    $sent = wp_mail(
        CORETRAINING_CONTACT_EMAIL,
        $mail_subject,
        $body,
        [
            'Content-Type: text/plain; charset=UTF-8',
            'Reply-To: ' . $name . ' <' . $email . '>',
        ]
    );

    if (!$sent) {
        return new WP_Error('mail_failed', __('Odeslání se nezdařilo. Zkuste to prosím znovu.', 'coretraining'), ['status' => 500]);
    }

    return new WP_REST_Response(['success' => true], 200);
}

function coretraining_rest_course_registration(WP_REST_Request $request): WP_REST_Response|WP_Error {
    $check = coretraining_rest_verify_request($request);
    if (is_wp_error($check)) {
        return $check;
    }

    $course_id = (int) $request->get_param('course_id');
    $name      = sanitize_text_field((string) $request->get_param('name'));
    $email     = sanitize_email((string) $request->get_param('email'));
    $phone     = preg_replace('/\s+/', '', sanitize_text_field((string) $request->get_param('phone')));
    $address   = sanitize_textarea_field((string) $request->get_param('address'));
    $note      = sanitize_textarea_field((string) $request->get_param('note'));

    if ($course_id <= 0 || get_post_type($course_id) !== 'kurz') {
        return new WP_Error('validation', __('Neplatný kurz.', 'coretraining'), ['status' => 422]);
    }
    if ($name === '' || $email === '' || $phone === '' || $address === '') {
        return new WP_Error('validation', __('Vyplňte prosím všechna povinná pole.', 'coretraining'), ['status' => 422]);
    }
    if (!is_email($email)) {
        return new WP_Error('validation', __('Zadejte platný e-mail.', 'coretraining'), ['status' => 422]);
    }
    if (!coretraining_validate_phone($phone)) {
        return new WP_Error('validation', __('Zadejte platné telefonní číslo.', 'coretraining'), ['status' => 422]);
    }

    $course_title = get_the_title($course_id);
    $meta         = coretraining_get_course_meta($course_id);
    $date         = coretraining_format_course_date($meta['date'], $meta['date_end']);

    $admin_body  = "Nová přihláška na kurz\n\n";
    $admin_body .= "Kurz: {$course_title}\n";
    if ($date) {
        $admin_body .= "Termín: {$date}\n";
    }
    if ($meta['location']) {
        $admin_body .= "Místo: {$meta['location']}\n";
    }
    if ($meta['price']) {
        $admin_body .= "Cena: {$meta['price']}\n";
    }
    $admin_body .= "\nJméno: {$name}\n";
    $admin_body .= "E-mail: {$email}\n";
    $admin_body .= "Telefon: {$phone}\n";
    $admin_body .= "Adresa: {$address}\n";
    if ($note !== '') {
        $admin_body .= "Poznámka: {$note}\n";
    }

    $admin_sent = wp_mail(
        CORETRAINING_CONTACT_EMAIL,
        sprintf('[CoreTraining] Přihláška — %s — %s', $course_title, $name),
        $admin_body,
        [
            'Content-Type: text/plain; charset=UTF-8',
            'Reply-To: ' . $name . ' <' . $email . '>',
        ]
    );

    $user_body  = "Dobrý den,\n\n";
    $user_body .= "potvrzujeme přijetí vaší přihlášky na kurz:\n\n";
    $user_body .= "{$course_title}\n";
    if ($date) {
        $user_body .= "Termín: {$date}\n";
    }
    if ($meta['time']) {
        $user_body .= "Čas: {$meta['time']}\n";
    }
    if ($meta['location']) {
        $user_body .= "Místo: {$meta['location']}\n";
    }
    if ($meta['price']) {
        $user_body .= "Cena: {$meta['price']}\n";
    }
    $user_body .= "\nPlatební údaje:\n";
    $user_body .= CORETRAINING_BANK_ACCOUNT . "\n";
    $user_body .= "Splatnost: 7 dní před konáním kurzu.\n\n";
    $user_body .= "V případě dotazů nás kontaktujte na " . CORETRAINING_CONTACT_EMAIL . ".\n\n";
    $user_body .= "S pozdravem,\nMartin Snášel\nCoreTraining\n";

    $user_sent = wp_mail(
        $email,
        sprintf('Potvrzení přihlášky — %s', $course_title),
        $user_body,
        ['Content-Type: text/plain; charset=UTF-8']
    );

    if (!$admin_sent) {
        return new WP_Error('mail_failed', __('Odeslání se nezdařilo. Zkuste to prosím znovu.', 'coretraining'), ['status' => 500]);
    }

    return new WP_REST_Response([
        'success'       => true,
        'user_notified' => $user_sent,
    ], 200);
}
