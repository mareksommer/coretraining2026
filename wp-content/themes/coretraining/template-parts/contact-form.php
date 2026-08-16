<?php
/**
 * Template part: Contact form
 *
 * @var string $default_subject Optional pre-filled subject.
 */

$default_subject = $args['default_subject'] ?? '';
if ($default_subject === '' && isset($_GET['predmet'])) {
    $default_subject = sanitize_text_field(wp_unslash($_GET['predmet']));
}
?>
<form class="ct-form" data-contact-form novalidate>
    <div class="ct-form__honeypot" aria-hidden="true">
        <label for="contact-website">Web</label>
        <input type="text" id="contact-website" name="website" tabindex="-1" autocomplete="off">
    </div>

    <div class="ct-form__field">
        <label for="contact-name"><?php esc_html_e('Jméno', 'coretraining'); ?> <span class="required">*</span></label>
        <input type="text" id="contact-name" name="name" required autocomplete="name">
    </div>

    <div class="ct-form__field">
        <label for="contact-email"><?php esc_html_e('E-mail', 'coretraining'); ?> <span class="required">*</span></label>
        <input type="email" id="contact-email" name="email" required autocomplete="email">
    </div>

    <div class="ct-form__field">
        <label for="contact-phone"><?php esc_html_e('Telefon', 'coretraining'); ?></label>
        <input type="tel" id="contact-phone" name="phone" autocomplete="tel">
    </div>

    <div class="ct-form__field">
        <label for="contact-subject"><?php esc_html_e('Předmět', 'coretraining'); ?></label>
        <input type="text" id="contact-subject" name="subject" value="<?php echo esc_attr($default_subject); ?>">
    </div>

    <div class="ct-form__field">
        <label for="contact-message"><?php esc_html_e('Zpráva', 'coretraining'); ?> <span class="required">*</span></label>
        <textarea id="contact-message" name="message" rows="5" required></textarea>
    </div>

    <div class="ct-form__field ct-form__field--checkbox">
        <label>
            <input type="checkbox" name="gdpr_consent" value="1" required>
            <?php
            printf(
                /* translators: %s: GDPR page URL */
                wp_kses(
                    __('Souhlasím se zpracováním osobních údajů dle <a href="%s">zásad ochrany osobních údajů</a>.', 'coretraining'),
                    ['a' => ['href' => []]]
                ),
                esc_url(home_url(CORETRAINING_GDPR_URL))
            );
            ?>
            <span class="required">*</span>
        </label>
    </div>

    <div class="ct-form__actions">
        <button type="submit" class="btn btn--primary"><?php esc_html_e('Odeslat zprávu', 'coretraining'); ?></button>
    </div>

    <p class="ct-form__status" data-form-status role="status" aria-live="polite"></p>
</form>
