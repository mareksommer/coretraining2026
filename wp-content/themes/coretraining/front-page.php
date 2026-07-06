<?php
/**
 * Template: Landing page — výpis inzerátů
 */

global $coretraining_page_title, $coretraining_meta_description;
$coretraining_page_title       = 'Brigády v O2 Areně – Coretraining';
$coretraining_meta_description = 'Pracovní příležitosti v O2 Areně a O2 Universu. Najdi si brigádu nebo práci na akcích světové úrovně.';

// Načti inzeráty (5min cache)
$jobs = get_transient('coretraining_home_jobs');
if ($jobs === false) {
    $result = coretraining_api_post('rpc/clp_page_get_home', ['limit_' => 99, 'offset_' => 0]);
    if (!is_wp_error($result)) {
        $jobs = $result;
        set_transient('coretraining_home_jobs', $jobs, 5 * MINUTE_IN_SECONDS);
    } else {
        $jobs       = null;
        $jobs_error = $result->get_error_message();
    }
}

get_header();
?>

<section class="hero">
    <div class="container">
        <h1 class="hero__title">Pracovní příležitosti v&nbsp;O2 Areně</h1>
        <p class="hero__subtitle">Připoj se k týmu Coretraining a pracuj na sportovních a kulturních akcích světové úrovně.</p>
    </div>
</section>

<section class="jobs-section">
    <div class="container">

        <?php if (!empty($jobs_error)): ?>
            <div class="alert alert--error">
                <?php echo esc_html($jobs_error); ?>
            </div>
        <?php elseif (empty($jobs)): ?>
            <p class="jobs-section__empty">Momentálně nejsou k dispozici žádné nabídky.</p>
        <?php else: ?>
            <h2 class="jobs-section__heading">Aktuální nabídky</h2>
            <ul class="job-list">
                <?php foreach ($jobs as $job): ?>
                    <?php
                    $id    = esc_attr($job['id'] ?? $job['job_id'] ?? '');
                    $title = esc_html($job['title'] ?? $job['name'] ?? '');
                    $desc  = esc_html($job['short_description'] ?? $job['description_short'] ?? '');
                    if (!$id) continue;
                    ?>
                    <li class="job-card">
                        <a href="<?php echo esc_url(home_url('/inzerat/' . $id . '/')); ?>" class="job-card__link">
                            <div class="job-card__content">
                                <h3 class="job-card__title"><?php echo $title; ?></h3>
                                <?php if ($desc): ?>
                                    <p class="job-card__desc"><?php echo $desc; ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="job-card__arrow" aria-hidden="true">›</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
