<?php
/**
 * Template: Kurzy archive (/kurzy/)
 */

global $coretraining_page_title;
$coretraining_page_title = __('Kurzy a semináře', 'coretraining') . ' – CoreTraining';

get_header();

$upcoming = coretraining_query_upcoming_courses(-1);
$past     = coretraining_query_past_courses();
?>

<section class="page-hero page-hero--compact">
    <div class="container">
        <h1 class="page-hero__title"><?php esc_html_e('Kurzy a semináře', 'coretraining'); ?></h1>
        <p class="page-hero__lead text-muted">
            <?php esc_html_e('Prezenční semináře, workshopy i online webináře pro trenéry, fyzioterapeuty i pokročilou veřejnost.', 'coretraining'); ?>
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php
        $has_upcoming = $upcoming->have_posts();
        $has_past     = $past->have_posts();
        ?>

        <?php if ($has_upcoming) : ?>
            <div class="grid grid--courses">
                <?php while ($upcoming->have_posts()) : $upcoming->the_post(); ?>
                    <?php get_template_part('template-parts/course-card'); ?>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>

        <?php if ($has_past) : ?>
            <?php if ($has_upcoming) : ?>
                <h2 class="section__subtitle"><?php esc_html_e('Ukončené kurzy', 'coretraining'); ?></h2>
            <?php endif; ?>
            <div class="grid grid--courses">
                <?php while ($past->have_posts()) : $past->the_post(); ?>
                    <?php get_template_part('template-parts/course-card'); ?>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>

        <?php if (!$has_upcoming && !$has_past) : ?>
            <p class="text-muted"><?php esc_html_e('Momentálně nejsou vypsány žádné kurzy.', 'coretraining'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
