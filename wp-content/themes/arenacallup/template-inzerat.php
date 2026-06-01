<?php
/**
 * Template: Detail inzerátu (/inzerat/{id}/)
 */

$job_id = sanitize_text_field(get_query_var('callup_job_id'));

if (!$job_id) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part('404');
    exit;
}

$result = callup_api_post('rpc/clp_job_get_detail', ['job_id_' => $job_id]);

if (is_wp_error($result) || empty($result)) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part('404');
    exit;
}

$job = is_array($result) && isset($result[0]) ? $result[0] : $result;

global $callup_page_title, $callup_meta_description, $callup_og_title, $callup_og_description;
$callup_page_title      = $job['title'] ?? '';
$callup_meta_description = $job['short_description'] ?? $job['description_short'] ?? '';
$callup_og_title        = $callup_page_title;
$callup_og_description  = $callup_meta_description;

get_header();
?>

<div class="container">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="back-link">← Zpět na nabídky</a>

    <article class="job-detail">

        <?php if (!empty($job['partner_logo'])): ?>
            <div class="job-detail__partner">
                <img
                    src="<?php echo esc_url($job['partner_logo']); ?>"
                    alt="<?php echo esc_attr($job['partner_name'] ?? ''); ?>"
                    class="job-detail__partner-logo"
                >
                <?php if (!empty($job['partner_name'])): ?>
                    <span class="job-detail__partner-name"><?php echo esc_html($job['partner_name']); ?></span>
                <?php endif; ?>
            </div>
        <?php elseif (!empty($job['partner_name'])): ?>
            <p class="job-detail__partner-name"><?php echo esc_html($job['partner_name']); ?></p>
        <?php endif; ?>

        <h1 class="job-detail__title"><?php echo esc_html($job['title'] ?? ''); ?></h1>

        <?php if (!empty($job['work_types'])): ?>
            <div class="job-detail__tags">
                <?php foreach ((array) $job['work_types'] as $wt): ?>
                    <span class="tag"><?php echo esc_html(is_array($wt) ? ($wt['label'] ?? $wt['name'] ?? '') : $wt); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($job['description'])): ?>
            <div class="job-detail__description">
                <?php echo wp_kses_post(nl2br(esc_html($job['description']))); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($job['region']) || !empty($job['address'])): ?>
            <div class="job-detail__location">
                <span class="job-detail__location-icon" aria-hidden="true">📍</span>
                <?php
                $location = array_filter([
                    $job['region']  ?? '',
                    $job['address'] ?? '',
                ]);
                echo esc_html(implode(', ', $location));
                ?>
            </div>
        <?php endif; ?>

        <div class="job-detail__actions">
            <a
                href="<?php echo esc_url(home_url('/mam-zajem/' . esc_attr($job_id) . '/')); ?>"
                class="btn btn--primary btn--lg"
            >Mám zájem</a>
            <a
                href="https://smeny.callup.stafio.cz"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn--outline btn--lg"
            >Přihlásit se</a>
        </div>

    </article>
</div>

<?php get_footer(); ?>
