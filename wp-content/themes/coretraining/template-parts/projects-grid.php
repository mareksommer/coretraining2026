<?php
/**
 * Template part: Projects grid
 */
$projects = coretraining_get_projects();
if (empty($projects)) {
    return;
}
?>
<div class="grid grid--projects">
    <?php foreach ($projects as $project) : ?>
        <a href="<?php echo esc_url($project['url']); ?>" class="project-card" target="_blank" rel="noopener noreferrer">
            <img
                src="<?php echo esc_url(coretraining_asset_url('images/projekty/' . $project['logo'])); ?>"
                alt="<?php echo esc_attr($project['name']); ?>"
                loading="lazy"
                width="120"
                height="60"
            >
            <span class="project-card__name"><?php echo esc_html($project['name']); ?></span>
        </a>
    <?php endforeach; ?>
</div>
