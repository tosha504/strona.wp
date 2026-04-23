<?php
/**
 * Pricing Hero Banner block template
 *
 * @package thenewlook
 */

if (!defined('ABSPATH')) {
    exit;
}

$block_id = 'pricing-hero-banner-' . $block['id'];
$anchor   = !empty($block['anchor']) ? $block['anchor'] : $block_id;

$class_name = 'c-pricing-hero-banner';

if (!empty($block['className'])) {
    $class_name .= ' ' . sanitize_html_class($block['className']);
}

if (!empty($block['align'])) {
    $class_name .= ' align' . sanitize_html_class($block['align']);
}

$badge       = (string) get_field('badge');
$title       = (string) get_field('title');
$description = (string) get_field('description');
$features    = get_field('features');
$price_text  = (string) get_field('price_text');
$buttons     = get_field('buttons');
$image_id    = (int) get_field('image');

if (
    empty($badge) &&
    empty($title) &&
    empty($description) &&
    empty($features) &&
    empty($price_text) &&
    empty($buttons) &&
    empty($image_id)
) {
    return;
}
?>

<section id="<?php echo esc_attr($anchor); ?>" class="<?php echo esc_attr($class_name); ?>">
    <div class="c-pricing-hero-banner__inner">

        <div class="c-pricing-hero-banner__content">
            <?php if (!empty($badge)): ?>
                <div class="c-pricing-hero-banner__badge">
                    <?php echo esc_html($badge); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($title)): ?>
                <h2 class="c-pricing-hero-banner__title">
                    <?php echo ($title); ?>
                </h2>
            <?php endif; ?>

            <?php if (!empty($description)): ?>
                <div class="c-pricing-hero-banner__description wysiwyg-content">
                    <?php echo wp_kses_post($description); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($features) && is_array($features)): ?>
                <ul class="c-pricing-hero-banner__features"
                    aria-label="<?php echo esc_attr__('Lista korzyści', 'thenewlook'); ?>">
                    <?php foreach ($features as $feature): ?>
                        <?php
                        $feature_icon_id = !empty($feature['icon']) ? (int) $feature['icon'] : 0;
                        $feature_text    = !empty($feature['text']) ? (string) $feature['text'] : '';

                        if (empty($feature_text)) {
                            continue;
                        }
                        ?>
                        <li class="c-pricing-hero-banner__feature">
                            <?php if ($feature_icon_id): ?>
                                <span class="c-pricing-hero-banner__feature-icon" aria-hidden="true">
                                    <?php
                                    echo wp_get_attachment_image(
                                        $feature_icon_id,
                                        'thumbnail',
                                        false,
                                        [
                                            'class'   => 'c-pricing-hero-banner__feature-icon-image',
                                            'loading' => 'lazy',
                                        ]
                                    );
                                    ?>
                                </span>
                            <?php else: ?>
                                <span class="c-pricing-hero-banner__feature-check" aria-hidden="true"></span>
                            <?php endif; ?>

                            <span class="c-pricing-hero-banner__feature-text">
                                <?php echo esc_html($feature_text); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($price_text)): ?>
                <div class="c-pricing-hero-banner__price">
                    <?php echo ($price_text); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($buttons) && is_array($buttons)): ?>
                <div class="c-pricing-hero-banner__actions">
                    <?php foreach ($buttons as $button): ?>
                        <?php
                        $link  = $button['link'] ?? null;
                        $style = !empty($button['style']) ? sanitize_key($button['style']) : 'primary';

                        if (empty($link['url']) || empty($link['title'])) {
                            continue;
                        }

                        $button_class  = 'c-pricing-hero-banner__button';
                        $button_class .= $style === 'secondary'
                            ? ' c-pricing-hero-banner__button--secondary'
                            : ' c-pricing-hero-banner__button--primary';
                        ?>
                        <a class="<?php echo esc_attr($button_class); ?>" href="<?php echo esc_url($link['url']); ?>" <?php echo !empty($link['target']) ? 'target="' . esc_attr($link['target']) . '"' : ''; ?>         <?php echo !empty($link['target']) && $link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>>
                            <?php echo esc_html($link['title']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="c-pricing-hero-banner__media">
            <div class="c-pricing-hero-banner__media-inner">
                <?php if ($image_id): ?>
                    <?php
                    echo wp_get_attachment_image(
                        $image_id,
                        'full',
                        false,
                        [
                            'class'   => 'c-pricing-hero-banner__image',
                            'loading' => 'lazy',
                        ]
                    );
                    ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>