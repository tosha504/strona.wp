<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$eyebrow       = trim((string) get_field('eyebrow'));
$title         = trim((string) get_field('title'));
$text          = (string) get_field('text');
$header_link   = get_field('header_link');
$services_ids  = get_field('services_items');

$header_link        = is_array($header_link) ? $header_link : [];
$header_link_url    = !empty($header_link['url']) ? $header_link['url'] : '';
$header_link_title  = !empty($header_link['title']) ? $header_link['title'] : '';
$header_link_target = !empty($header_link['target']) ? $header_link['target'] : '_self';
$header_link_rel    = $header_link_target === '_blank' ? 'noopener noreferrer' : '';

$services_ids = is_array($services_ids) ? array_map('intval', $services_ids) : [];
$services_ids = array_filter($services_ids);

if (empty($services_ids)) {
    if (is_admin()) {
        echo '<div class="servicesCarouselBlock__placeholder">Wybierz usługi do karuzeli.</div>';
    }

    return;
}

$classes = ['servicesCarouselBlock'];

if (!empty($block['className'])) {
    $classes[] = $block['className'];
}

if (!empty($block['align'])) {
    $classes[] = 'align' . $block['align'];
}

$anchor       = !empty($block['anchor']) ? 'id="' . esc_attr($block['anchor']) . '"' : '';
$slides_count = count($services_ids);
?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="container">
        <?php if ($eyebrow !== '' || $title !== '' || $text !== '' || ($header_link_url && $header_link_title)): ?>
            <div class="servicesCarouselBlock__header">
                <div class="servicesCarouselBlock__headerLeft">
                    <?php if ($eyebrow !== ''): ?>
                        <p class="servicesCarouselBlock__eyebrow"><?php echo esc_html($eyebrow); ?></p>
                    <?php endif; ?>

                    <?php if ($title !== ''): ?>
                        <h2 class="servicesCarouselBlock__title"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>

                    <?php if ($text !== ''): ?>
                        <div class="servicesCarouselBlock__text">
                            <?php echo wp_kses_post(wpautop($text)); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($header_link_url && $header_link_title): ?>
                    <div class="servicesCarouselBlock__headerRight">
                        <a
                            class="btn btn-primary"
                            href="<?php echo esc_url($header_link_url); ?>"
                            target="<?php echo esc_attr($header_link_target); ?>"
                            rel="<?php echo esc_attr($header_link_rel); ?>"
                        >
                            <?php echo esc_html($header_link_title); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="servicesCarouselBlock__slider swiper js-services-carousel"
            data-slides-count="<?php echo esc_attr((string) $slides_count); ?>">
            <div class="swiper-wrapper">
                <?php foreach ($services_ids as $service_id): ?>
                    <div class="swiper-slide servicesCarouselBlock__slide">
                        <?php
                        $button      = get_field('link', $service_id);
                        $link_url    = '';
                        $link_target = '_self';

                        if (is_array($button) && !empty($button['url'])) {
                            $link_url    = $button['url'];
                            $link_target = !empty($button['target']) ? $button['target'] : '_self';
                        } elseif (is_string($button) && $button !== '') {
                            $link_url = $button;
                        }
                        ?>

                        <?php if ($link_url): ?>
                            <div class="servicesItem__box">
                                <a
                                    href="<?php echo esc_url($link_url); ?>"
                                    target="<?php echo esc_attr($link_target); ?>"
                                    <?php echo $link_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                                >
                                    <?php echo single_services($service_id, 'link'); ?>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="servicesItem__box">
                                <?php echo single_services($service_id, 'link'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($slides_count > 1): ?>
                <div class="servicesCarouselBlock__pagination swiper-pagination"></div>
            <?php endif; ?>
        </div>
    </div>
</section>