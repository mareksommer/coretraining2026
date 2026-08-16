<?php
/**
 * Template: Ochrana údajů (/ochrana-udaju/)
 */

global $coretraining_page_title;
$coretraining_page_title = __('Ochrana údajů a cookies', 'coretraining') . ' – CoreTraining';

get_header();
?>

<section class="page-hero page-hero--compact">
    <div class="container">
        <h1 class="page-hero__title"><?php esc_html_e('Ochrana údajů a cookies', 'coretraining'); ?></h1>
        <p class="page-hero__lead text-muted">
            <?php esc_html_e('Text zásad ochrany osobních údajů bude doplněn a schválen před spuštěním webu.', 'coretraining'); ?>
        </p>
    </div>
</section>

<section class="section">
    <div class="container content-block">
        <p><strong><?php esc_html_e('Správce údajů:', 'coretraining'); ?></strong> Martin Snášel, IČO <?php echo esc_html(CORETRAINING_ICO); ?>, <?php echo esc_html(CORETRAINING_CONTACT_EMAIL); ?></p>
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
