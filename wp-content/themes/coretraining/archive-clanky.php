<?php
/**
 * Template: Články archive (/clanky/)
 */

global $coretraining_page_title;
$coretraining_page_title = __('Články', 'coretraining') . ' – CoreTraining';

get_header();
?>

<section class="page-hero page-hero--compact">
    <div class="container">
        <h1 class="page-hero__title"><?php esc_html_e('Články', 'coretraining'); ?></h1>
        <p class="page-hero__lead text-muted">
            <?php esc_html_e('Odborné články o pohybu, silovém tréninku a biomechanice.', 'coretraining'); ?>
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="grid grid--articles">
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/article-card'); ?>
                <?php endwhile; ?>
            </div>
            <nav class="pagination" aria-label="<?php esc_attr_e('Stránkování', 'coretraining'); ?>">
                <?php the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '←',
                    'next_text' => '→',
                ]); ?>
            </nav>
        <?php else : ?>
            <p class="text-muted"><?php esc_html_e('Zatím nejsou publikovány žádné články.', 'coretraining'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
