<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ACF Block render template: Service Promo Card
 *
 * @var array $block
 * @var string $content
 * @var bool $is_preview
 * @var int|string $post_id
 */

$badge       = (string) get_field('badge');
$title       = (string) get_field('title');
$subbadge    = (string) get_field('subbadge');
$price_text  = (string) get_field('price_text');
$description = (string) get_field('description');
$theme       = (string) get_field('block_theme') ?: 'dark';

$icon_id  = (int) get_field('icon');
$image_id = (int) get_field('image');

$button_link = get_field('button_link');

/**
 * Opcjonalnie możesz dodać do ACF true/false field:
 * force_compact_layout
 * i wtedy wymuszać pionowy layout niezależnie od szerokości.
 */
$force_compact = (bool) get_field('force_compact_layout');

$classes = [
    'aw-service-promo-card',
    'aw-service-promo-card--' . sanitize_html_class($theme),
];

if ($force_compact) {
    $classes[] = 'aw-service-promo-card--compact';
}

if (!empty($block['className'])) {
    $classes[] = $block['className'];
}

if (!empty($block['align'])) {
    $classes[] = 'align' . $block['align'];
}

$anchor = !empty($block['anchor']) ? sanitize_title($block['anchor']) : '';

$link_url    = '';
$link_title  = '';
$link_target = '_self';

if (is_array($button_link) && !empty($button_link['url'])) {
    $link_url    = esc_url($button_link['url']);
    $link_title  = !empty($button_link['title'])
        ? (string) $button_link['title']
        : __('Czytaj więcej', 'thenewlook');
    $link_target = !empty($button_link['target'])
        ? (string) $button_link['target']
        : '_self';
}
?>

<section <?php echo $anchor ? 'id="' . esc_attr($anchor) . '"' : ''; ?>
    class="<?php echo esc_attr(implode(' ', array_filter($classes))); ?>">
    <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" <?php echo $link_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?> class="aw-service-promo-card__inner">
        <div class="aw-service-promo-card__content">
            <div class="aw-service-promo-card__top">
                <?php if ($icon_id): ?>
                    <div class="aw-service-promo-card__icon" aria-hidden="true">
                        <?php
                        echo wp_get_attachment_image(
                            $icon_id,
                            'thumbnail',
                            false,
                            [
                                'class'   => 'aw-service-promo-card__icon-image',
                                'loading' => 'lazy',
                                'alt'     => '',
                            ]
                        );
                        ?>
                    </div>
                <?php endif; ?>

                <?php if ($badge): ?>
                    <div class="aw-service-promo-card__badge">
                        <?php echo esc_html($badge); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($title): ?>
                <h2 class="aw-service-promo-card__title">
                    <?php echo nl2br(esc_html($title)); ?>
                </h2>
            <?php endif; ?>

            <?php if ($subbadge): ?>
                <div class="aw-service-promo-card__subbadge">
                    <?php echo esc_html($subbadge); ?>
                </div>
            <?php endif; ?>

            <?php if ($price_text): ?>
                <div class="aw-service-promo-card__price">
                    <?php echo esc_html($price_text); ?>
                </div>
            <?php endif; ?>

            <?php if ($description): ?>
                <div class="aw-service-promo-card__description">
                    <?php echo wp_kses_post($description); ?>
                </div>
            <?php endif; ?>

            <?php if ($link_url && $link_title): ?>
                <div class="aw-service-promo-card__actions">
                    <span class="aw-service-promo-card__link">
                        <span><?php echo esc_html($link_title); ?></span>
                        <span class="aw-service-promo-card__link-arrow" aria-hidden="true">→</span>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <div class="aw-service-promo-card__media">
            <?php if ($image_id): ?>
                <div class="aw-service-promo-card__image">
                    <?php
                    echo wp_get_attachment_image(
                        $image_id,
                        'full',
                        false,
                        [
                            'class'   => 'aw-service-promo-card__image-element',
                            'loading' => 'lazy',
                            'alt'     => '',
                        ]
                    );
                    ?>
                </div>
            <?php elseif ($is_preview): ?>
                <div class="aw-service-promo-card__image-placeholder">
                    <?php esc_html_e('Dodaj obrazek po prawej stronie', 'thenewlook'); ?>
                </div>
            <?php endif; ?>
        </div>
    </a>
</section>