<?php
/**
 * Fallback template (archiv, blog).
 */

get_header();
?>

<section class="section">
    <div class="container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('post-card'); ?>>
                    <h2 class="post-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <?php if (has_excerpt()) : ?>
                        <p class="post-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>
                </article>
            <?php endwhile; ?>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e('Žádné příspěvky k zobrazení.', 'coretraining'); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
