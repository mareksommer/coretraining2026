<?php
/**
 * Template: Single článek (/clanky/{slug}/)
 */

global $coretraining_page_title;
$coretraining_page_title = get_the_title() . ' – CoreTraining';

get_header();

the_post();

$content_raw = get_the_content();
$content_raw = apply_filters('the_content', $content_raw);
$headings    = coretraining_parse_headings($content_raw);
$content     = coretraining_add_heading_ids($content_raw);
?>

<article <?php post_class('article-single'); ?>>
    <header class="page-hero page-hero--compact">
        <div class="container">
            <h1 class="page-hero__title"><?php the_title(); ?></h1>
            <p class="article-single__meta text-muted">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php echo esc_html(get_the_date('j. n. Y')); ?>
                </time>
                · Martin Snášel
            </p>
        </div>
    </header>

    <div class="container article-single__layout">
        <?php if (has_post_thumbnail()) : ?>
            <div class="article-single__image">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>

        <?php if (count($headings) >= 2) : ?>
            <nav class="article-toc" aria-label="<?php esc_attr_e('Obsah článku', 'coretraining'); ?>">
                <h2 class="article-toc__title"><?php esc_html_e('Obsah', 'coretraining'); ?></h2>
                <ol class="article-toc__list">
                    <?php foreach ($headings as $heading) : ?>
                        <li class="article-toc__item article-toc__item--h<?php echo esc_attr((string) $heading['level']); ?>">
                            <a href="#<?php echo esc_attr($heading['id']); ?>"><?php echo esc_html($heading['text']); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        <?php endif; ?>

        <div class="article-single__content entry-content">
            <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — filtered content ?>
        </div>

        <footer class="article-single__footer">
            <a href="<?php echo esc_url(home_url('/kurzy/')); ?>" class="btn btn--primary">
                <?php esc_html_e('Prohlédnout kurzy', 'coretraining'); ?>
            </a>
        </footer>
    </div>
</article>

<?php get_footer(); ?>
