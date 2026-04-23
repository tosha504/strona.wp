<?php
/**
 * Block Name: Project Consult
 */

$id = 'projectConsult-' . $block['id'];

if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

$class_name = 'projectConsult';

if (!empty($block['className'])) {
    $class_name .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
    $class_name .= ' align' . $block['align'];
}

$eyebrow        = (string) get_field('eyebrow');
$title          = (string) get_field('title');
$description    = (string) get_field('description');
$cta_link       = get_field('cta_link');
$form_shortcode = trim((string) get_field('form_shortcode'));

$cta_url    = '';
$cta_title  = '';
$cta_target = '_self';

if (is_array($cta_link)) {
    $cta_url    = !empty($cta_link['url']) ? $cta_link['url'] : '';
    $cta_title  = !empty($cta_link['title']) ? $cta_link['title'] : '';
    $cta_target = !empty($cta_link['target']) ? $cta_link['target'] : '_self';
}
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class_name); ?>">
    <div class="container">
        <div class="projectConsult__inner">
            <div class="projectConsult__content">
                <?php if ($eyebrow): ?>
                    <div class="projectConsult__eyebrow">
                        <?php echo esc_html($eyebrow); ?>
                    </div>
                <?php endif; ?>

                <?php if ($title): ?>
                    <h2 class="projectConsult__title">
                        <?php echo nl2br(esc_html($title)); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($description): ?>
                    <div class="projectConsult__description">
                        <?php echo wpautop(wp_kses_post($description)); ?>
                    </div>
                <?php endif; ?>

                <?php if ($cta_url && $cta_title): ?>
                    <div class="projectConsult__actions">
                        <a class="projectConsult__link btn btn-arrow-new" href="<?php echo esc_url($cta_url); ?>"
                            target="<?php echo esc_attr($cta_target); ?>">
                            <?php echo esc_html($cta_title); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="projectConsult__form">
                <?php if ($form_shortcode): ?>
                    <?php echo do_shortcode($form_shortcode); ?>
                <?php elseif (is_admin()): ?>
                    <div class="projectConsult__formPlaceholder">
                        Dodaj shortcode formularza w ustawieniach bloku.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>