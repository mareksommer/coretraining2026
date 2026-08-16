<?php
/**
 * Template part: course card
 *
 * @var WP_Post $post Optional. Defaults to global $post.
 */

$post = $args['post'] ?? get_post();
if (!$post) {
    return;
}

$meta   = coretraining_get_course_meta($post->ID);
$badge  = coretraining_get_course_badge($post);
$date   = coretraining_format_course_date($meta['date'], $meta['date_end']);
$upcoming = coretraining_is_course_upcoming($post->ID);
?>
<article <?php post_class('course-card', $post); ?>>
    <a href="<?php echo esc_url(get_permalink($post)); ?>" class="course-card__link">
        <?php if (has_post_thumbnail($post)) : ?>
            <div class="course-card__image">
                <?php echo get_the_post_thumbnail($post, 'medium_large', ['loading' => 'lazy']); ?>
            </div>
        <?php endif; ?>
        <div class="course-card__body">
            <?php if ($badge) : ?>
                <span class="badge badge--<?php echo esc_attr($badge['slug']); ?>">
                    <?php echo esc_html($badge['label']); ?>
                </span>
            <?php endif; ?>
            <?php if ($date) : ?>
                <p class="course-card__date">
                    <?php echo esc_html($date); ?>
                    <?php if (!$upcoming) : ?>
                        <span class="course-card__past"><?php esc_html_e('(ukončeno)', 'coretraining'); ?></span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            <h3 class="course-card__title"><?php echo esc_html(get_the_title($post)); ?></h3>
            <?php if ($meta['location']) : ?>
                <p class="course-card__meta"><?php echo esc_html($meta['location']); ?></p>
            <?php endif; ?>
            <?php if ($meta['price']) : ?>
                <p class="course-card__price"><?php echo esc_html($meta['price']); ?></p>
            <?php endif; ?>
        </div>
    </a>
</article>
