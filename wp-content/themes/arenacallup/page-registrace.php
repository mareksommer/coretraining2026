<?php
/**
 * Template Name: Registrace
 * Template: Stránka /registrace/
 */

global $callup_page_title;
$callup_page_title = 'Registrace – Callup';

$cost_centers_raw = get_option('callup_cost_centers', '[]');
$cost_centers     = json_decode($cost_centers_raw, true);
if (!is_array($cost_centers)) {
    $cost_centers = [];
}

get_header();
?>

<div class="container">
    <div class="form-page">
        <h1 class="form-page__title">Registrace</h1>
        <p class="form-page__subtitle">Vytvoř si účet a odpovídej na nabídky snadno.</p>

        <div id="form-message" class="alert" style="display:none"></div>

        <form id="register-form" class="callup-form" novalidate>

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

            <?php if (!empty($cost_centers)): ?>
            <div class="form-group">
                <fieldset class="form-fieldset">
                    <legend class="form-label">Chci pracovat jako <span class="required">*</span></legend>
                    <div class="form-radio-group">
                        <?php foreach ($cost_centers as $cc): ?>
                            <?php
                            $cc_id    = esc_attr($cc['id'] ?? '');
                            $cc_label = esc_html($cc['label'] ?? '');
                            if (!$cc_id) continue;
                            ?>
                            <label class="form-radio-label">
                                <input type="radio" name="cost_center" value="<?php echo $cc_id; ?>" class="form-radio" required>
                                <span><?php echo $cc_label; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <span class="form-error" id="cost_center-error"></span>
                </fieldset>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="password" class="form-label">Heslo <span class="required">*</span></label>
                <input type="password" id="password" name="password" class="form-input" required autocomplete="new-password"
                       minlength="8">
                <span class="form-hint">Minimálně 8 znaků</span>
                <span class="form-error" id="password-error"></span>
            </div>

            <div class="form-group">
                <label for="password_confirm" class="form-label">Heslo znovu <span class="required">*</span></label>
                <input type="password" id="password_confirm" name="password_confirm" class="form-input" required
                       autocomplete="new-password">
                <span class="form-error" id="password_confirm-error"></span>
            </div>

            <div class="form-group form-group--checkbox">
                <label class="form-checkbox-label">
                    <input type="checkbox" name="gdpr" id="gdpr" class="form-checkbox" required>
                    <span>Souhlasím se <a href="<?php echo esc_url(home_url('/podminky-pouzivani-sluzeb/')); ?>" target="_blank">zpracováním osobních údajů</a></span>
                </label>
                <span class="form-error" id="gdpr-error"></span>
            </div>

            <button type="submit" id="submit-btn" class="btn btn--primary btn--lg btn--full">
                Vytvořit účet
            </button>

            <p class="form-page__footer-note">
                Odesláním souhlasíte s <a href="<?php echo esc_url(home_url('/podminky-pouzivani-sluzeb/')); ?>">podmínkami používání služeb</a>.
            </p>
        </form>
    </div>
</div>

<?php get_footer(); ?>
