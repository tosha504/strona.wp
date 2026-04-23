<?php
/**
 * Block Name: Services
 */

$id = 'servicesItem-' . $block['id'];

$settings = get_field('settings');

if (!is_array($settings) || empty($settings['services'])) {
    return;
}

$services     = $settings['services'];
$awarded      = !empty($settings['awarded']);
$button_style = 'btn btn-arrow-new ';

$service_post_id = 0;

if ($services instanceof WP_Post) {
    $service_post_id = (int) $services->ID;
} elseif (is_array($services) && isset($services['ID'])) {
    $service_post_id = (int) $services['ID'];
} else {
    $service_post_id = (int) $services;
}

if (!$service_post_id) {
    return;
}

$top_badge_value = (string) get_field('top_badge', $service_post_id);
$top_badge_class = !empty($top_badge_value) ? 'topBadge' : 'topBadge white';

$button = get_field('link', $service_post_id);

$link_url    = '';
$link_target = '_self';

if (is_array($button)) {
    $link_url    = !empty($button['url']) ? $button['url'] : '';
    $link_target = !empty($button['target']) ? $button['target'] : '_self';
} elseif (is_string($button) && $button !== '') {
    $link_url = $button;
}
?>

<div id="<?php echo esc_attr($id); ?>" class="servicesItem">
    <div class="<?php echo esc_attr($top_badge_class); ?>">
        <?php echo esc_html($top_badge_value); ?>
    </div>

    <div class="servicesItem__box <?php echo $awarded ? 'awarded' : ''; ?>">
        <?php if ($link_url): ?>
            <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                <?php echo single_services($services, $button_style); ?>
            </a>
        <?php else: ?>
            <?php echo single_services($services, $button_style); ?>
        <?php endif; ?>
    </div>
</div>