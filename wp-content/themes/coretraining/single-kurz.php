<?php
/**
 * Template: Single kurz (/kurzy/{slug}/)
 */

global $coretraining_page_title;
$coretraining_page_title = get_the_title() . ' – CoreTraining';

coretraining_enqueue_forms();

get_header();

$meta   = coretraining_get_course_meta(get_the_ID());
$badge  = coretraining_get_course_badge(get_post());
$date   = coretraining_format_course_date($meta['date'], $meta['date_end']);
?>

<article <?php post_class('course-single'); ?>>
    <header class="page-hero page-hero--compact">
        <div class="container">
            <?php if ($badge) : ?>
                <span class="badge badge--<?php echo esc_attr($badge['slug']); ?>">
                    <?php echo esc_html($badge['label']); ?>
                </span>
            <?php endif; ?>
            <h1 class="page-hero__title"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <p class="page-hero__lead"><?php echo esc_html(get_the_excerpt()); ?></p>
            <?php endif; ?>
        </div>
    </header>

    <div class="container course-single__layout">
        <div class="course-single__content entry-content">
            <?php if (has_post_thumbnail()) : ?>
                <div class="course-single__image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
            <?php the_content(); ?>
        </div>

        <aside class="course-single__sidebar">
            <div class="sidebar-card">
                <h2 class="sidebar-card__title"><?php esc_html_e('Informace o kurzu', 'coretraining'); ?></h2>
                <dl class="sidebar-card__list">
                    <?php if ($date) : ?>
                        <div class="sidebar-card__item">
                            <dt><?php esc_html_e('Termín', 'coretraining'); ?></dt>
                            <dd><?php echo esc_html($date); ?></dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($meta['time']) : ?>
                        <div class="sidebar-card__item">
                            <dt><?php esc_html_e('Čas', 'coretraining'); ?></dt>
                            <dd><?php echo esc_html($meta['time']); ?></dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($meta['location']) : ?>
                        <div class="sidebar-card__item">
                            <dt><?php esc_html_e('Místo', 'coretraining'); ?></dt>
                            <dd><?php echo esc_html($meta['location']); ?></dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($meta['price']) : ?>
                        <div class="sidebar-card__item">
                            <dt><?php esc_html_e('Cena', 'coretraining'); ?></dt>
                            <dd><?php echo esc_html($meta['price']); ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>

                <div class="sidebar-card__payment">
                    <h3><?php esc_html_e('Platba', 'coretraining'); ?></h3>
                    <p><?php esc_html_e('Převod na účet:', 'coretraining'); ?><br>
                    <strong>Mbank 670100-2211277834/6210</strong></p>
                    <p class="text-muted"><?php esc_html_e('Splatnost 7 dní před konáním kurzu.', 'coretraining'); ?></p>
                </div>
            </div>

            <?php if (coretraining_is_course_upcoming(get_the_ID())) : ?>
                <div class="sidebar-card sidebar-card--form">
                    <h2 class="sidebar-card__title"><?php esc_html_e('Přihláška na kurz', 'coretraining'); ?></h2>
                    <?php get_template_part('template-parts/course-registration-form', null, ['course_id' => get_the_ID()]); ?>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</article>

<?php get_footer(); ?>
