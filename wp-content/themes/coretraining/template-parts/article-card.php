<?php
/**
 * Template part: article card
 *
 * @var WP_Post $post Optional. Defaults to global $post.
 */

$post = $args['post'] ?? get_post();
if (!$post) {
    return;
}
?>
<article <?php post_class('article-card', $post); ?>>
    <a href="<?php echo esc_url(get_permalink($post)); ?>" class="article-card__link">
        <?php if (has_post_thumbnail($post)) : ?>
            <div class="article-card__image">
                <?php echo get_the_post_thumbnail($post, 'medium_large', ['loading' => 'lazy']); ?>
            </div>
        <?php endif; ?>
        <div class="article-card__body">
            <time class="article-card__date" datetime="<?php echo esc_attr(get_the_date('c', $post)); ?>">
                <?php echo esc_html(get_the_date('j. n. Y', $post)); ?>
            </time>
            <h3 class="article-card__title"><?php echo esc_html(get_the_title($post)); ?></h3>
            <?php if (has_excerpt($post)) : ?>
                <p class="article-card__excerpt"><?php echo esc_html(get_the_excerpt($post)); ?></p>
            <?php endif; ?>
        </div>
    </a>
</article>
