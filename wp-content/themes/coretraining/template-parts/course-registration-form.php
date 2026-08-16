<?php
/**
 * Template part: Course registration form
 *
 * @var int $course_id Required course post ID.
 */

$course_id = (int) ($args['course_id'] ?? 0);
if ($course_id <= 0) {
    return;
}
?>
<form class="ct-form" data-course-form data-course-id="<?php echo esc_attr((string) $course_id); ?>" novalidate>
    <div class="ct-form__honeypot" aria-hidden="true">
        <label for="course-website">Web</label>
        <input type="text" id="course-website" name="website" tabindex="-1" autocomplete="off">
    </div>

    <div class="ct-form__field">
        <label for="course-name"><?php esc_html_e('Jméno a příjmení', 'coretraining'); ?> <span class="required">*</span></label>
        <input type="text" id="course-name" name="name" required autocomplete="name">
    </div>

    <div class="ct-form__field">
        <label for="course-email"><?php esc_html_e('E-mail', 'coretraining'); ?> <span class="required">*</span></label>
        <input type="email" id="course-email" name="email" required autocomplete="email">
    </div>

    <div class="ct-form__field">
        <label for="course-phone"><?php esc_html_e('Telefon', 'coretraining'); ?> <span class="required">*</span></label>
        <input type="tel" id="course-phone" name="phone" required autocomplete="tel">
    </div>

    <div class="ct-form__field">
        <label for="course-address"><?php esc_html_e('Adresa (ulice, město, PSČ)', 'coretraining'); ?> <span class="required">*</span></label>
        <textarea id="course-address" name="address" rows="3" required></textarea>
    </div>

    <div class="ct-form__field">
        <label for="course-note"><?php esc_html_e('Poznámka', 'coretraining'); ?></label>
        <textarea id="course-note" name="note" rows="2"></textarea>
    </div>

    <div class="ct-form__field ct-form__field--checkbox">
        <label>
            <input type="checkbox" name="gdpr_consent" value="1" required>
            <?php
            printf(
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
        <button type="submit" class="btn btn--primary"><?php esc_html_e('Přihlásit se', 'coretraining'); ?></button>
    </div>

    <p class="ct-form__status" data-form-status role="status" aria-live="polite"></p>
</form>
