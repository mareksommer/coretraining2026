<?php
/**
 * Template part: reference card (carousel slide / grid item)
 *
 * @var WP_Post $post Optional. Defaults to global $post.
 */

$post = $args['post'] ?? get_post();
if (!$post) {
    return;
}

$name   = (string) get_post_meta($post->ID, 'reference_name', true);
$rating = (int) get_post_meta($post->ID, 'reference_rating', true);
?>
<figure class="reference-card">
    <blockquote class="reference-card__quote">
        <p><?php echo esc_html(wp_strip_all_tags(get_the_content(null, false, $post))); ?></p>
    </blockquote>
    <figcaption class="reference-card__author">
        <?php if (has_post_thumbnail($post)) : ?>
            <div class="reference-card__avatar">
                <?php echo get_the_post_thumbnail($post, 'thumbnail', ['loading' => 'lazy']); ?>
            </div>
        <?php endif; ?>
        <div class="reference-card__info">
            <?php if ($name) : ?>
                <cite class="reference-card__name"><?php echo esc_html($name); ?></cite>
            <?php endif; ?>
            <?php if ($rating > 0) : ?>
                <?php coretraining_render_stars($rating); ?>
            <?php endif; ?>
        </div>
    </figcaption>
</figure>
