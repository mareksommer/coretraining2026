<?php
/**
 * Template: Search results
 */

global $coretraining_page_title;
$coretraining_page_title = sprintf(
    /* translators: %s: search query */
    __('Výsledky hledání: %s', 'coretraining'),
    get_search_query()
) . ' – CoreTraining';

get_header();
?>

<section class="page-hero page-hero--compact">
    <div class="container">
        <h1 class="page-hero__title">
            <?php
            printf(
                /* translators: %s: search query */
                esc_html__('Výsledky hledání: %s', 'coretraining'),
                '<span>' . esc_html(get_search_query()) . '</span>'
            );
            ?>
        </h1>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="search-results">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('search-result'); ?>>
                        <p class="search-result__type text-muted">
                            <?php
                            if (get_post_type() === 'kurz') {
                                esc_html_e('Kurz', 'coretraining');
                            } else {
                                esc_html_e('Článek', 'coretraining');
                            }
                            ?>
                        </p>
                        <h2 class="search-result__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <?php if (has_excerpt()) : ?>
                            <p class="search-result__excerpt text-muted"><?php echo esc_html(get_the_excerpt()); ?></p>
                        <?php endif; ?>
                    </article>
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
            <p class="text-muted"><?php esc_html_e('Pro zadaný výraz nebyly nalezeny žádné výsledky.', 'coretraining'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
